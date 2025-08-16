<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Exception;
use Newsletter;
use Carbon\Carbon;
use App\Enums\Permission;
use App\Mail\ContactAdmin;
use App\Models\UserDevice;
use App\Traits\EmailTrait;
use Illuminate\Support\Str;
use App\Traits\WalletsTrait;
use Illuminate\Http\Request;
use App\Database\Models\Shop;
use App\Database\Models\User;
use Illuminate\Http\Response;
use App\Database\Models\Wallet;
use App\Database\Models\Product;
use App\Database\Models\Profile;
use App\Otp\Gateways\OtpGateway;
use App\Models\PhoneVerification;
use Illuminate\Support\Facades\DB;
use App\Database\Models\Attachment;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use App\Database\Models\OrderedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CheckTwoFactorRequest;
use App\Database\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Database\Models\Permission as ModelsPermission;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use App\Models\Role;
use Illuminate\Validation\Rule;
use App\Models\UserDeleteRequest;
use App\Http\Requests\CreateUserDeleteRequest;

class UserController extends CoreController
{
    use WalletsTrait,EmailTrait;

    public $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
    //    $users = $this->repository->where('old_id', '!=', 0)->get();
    //     foreach ($users as $user) {
    //         $user->password = Hash::make($user->password);
    //         $user->save();
    //     }

        return $this->repository->with(['profile', 'address', 'permissions'])->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *Í
     * @param UserCreateRequest $request
     * @return bool[]
     */
    public function store(UserCreateRequest $request)
    {
        return $this->repository->storeUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        try {
            $user = $this->repository->with(['profile', 'address', 'shops', 'managed_shop'])->findOrFail($id);
            $user->permissions = $user->getPermissionNames();
            return $user;
        } catch (Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param int $id
     * @return array
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN)) {
            $user = $this->repository->findOrFail($id);
            return $this->repository->updateUser($request, $user);
        } elseif ($request->user()->id == $id) {
            $user = $request->user();
            return $this->repository->updateUser($request, $user);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();
        if (isset($user)) {
            return $this->repository->with(['profile', 'wallet', 'address'])->find($user->id);
        }
        throw new MarvelException(NOT_AUTHORIZED);
    }

    public function token(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password'   => 'required',
            'device_token' => 'sometimes',
        ]);

        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->identifier)
                  ->orWhere('phone', $request->identifier);
        })
        ->where('is_active', true)
        ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ["token" => null, "permissions" => [], "messages" => trans('auth.failed')];
        }
        if(isset($request->device_token)){
            $userDevice = new UserDevice();
            $userDevice->user_id = $user->id;
            $userDevice->device_token = $request->device_token;
            $userDevice->save();
        }
        $user->generateTwoFactorCode();
         /* 2fa you mail start */
         $tags = [
            'app_url' => env("APP_URL"),
            "app_name" => env("APP_NAME"),
            'name' => $user->first_name." " . $user->last_name,
            "user" => $user,
            "url" => config('app.url').'/shop'
        ];
        $toIds=array($user->email);
        // $this->sendEmailNotification('TwoFactorAuthMail', $toIds,$tags);

        $plain_token = $user->createToken('auth_token')->plainTextToken;
        // Remove token expiration code since personal_access_tokens table doesn't have expires_at column
        // $token = $user->tokens()
        // ->where('name', 'auth_token')
        // ->latest()
        // ->first();
        // if ($token) {
        //     $token->expires_at = now()->addHours(10);
        //     $token->save();
        // }

        $userDetail = $this->repository->with(['profile', 'wallet', 'address'])->find($user->id);

        /* 2fa mail end */
        // $user->notify(new TwoFactorCodeNotification());
        // return ["token" => $user->createToken('auth_token')->plainTextToken, "permissions" => $user->getPermissionNames(), "messages" => "Two Factor Authentication mail sent to your Email."];
        return ["token" => $plain_token,'userDetail' => $userDetail, "permissions" => $user->getPermissionNames(), "messages" => trans('auth.success')];

        // return ["token" => $user->createToken('auth_token')->plainTextToken, "permissions" => $user->getPermissionNames(), "messages" => trans('auth.success')];
    }
    public function resendTwoFactorCode(Request $request)
    {
        $user =  $request->user();
        if (isset($user)) {
            $user->generateTwoFactorCode();
            /* 2fa you mail start */
            $tags = [
                'app_url' => env("APP_URL"),
                "app_name" => env("APP_NAME"),
                'name' => $user->first_name." " . $user->last_name,
                "user" => $user,
                "url" => config('app.url').'/shop'
            ];
            $toIds=array($user->email);
            //$this->sendEmailNotification('TwoFactorAuthMail', $toIds,$tags);
            SendEmailJob::dispatch('TwoFactorAuthMail', $toIds,$tags);
            /* 2fa mail end */
            // $user->notify(new TwoFactorCodeNotification());
            return ["messages" => "Two Factor Authentication mail sent to your Email."];
        }
        throw new MarvelException(NOT_AUTHORIZED);

        // return ["token" => $user->createToken('auth_token')->plainTextToken, "permissions" => $user->getPermissionNames(), "messages" => trans('auth.success')];
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return true;
        }
        return $request->user()->currentAccessToken()->delete();
    }

    public function register(UserCreateRequest $request)
    {
        return $this->registerProcess($request);
    }
    public function verify2fa(CheckTwoFactorRequest $request)
    {
        $user =  $request->user();
        if ($request->input('two_factor_code') == $user->two_factor_code) {
            $user->resetTwoFactorCode();
            $plain_token = $user->createToken('auth_token')->plainTextToken;
            $token = $user->tokens()
            ->where('name', 'auth_token')
            ->latest()
            ->first();
            if ($token) {
                $token->expires_at = now()->addHours(10);
                // $token->expires_at = now()->addMinutes(1);
                $token->save();
            }
            return ["token" => $plain_token, "permissions" => $user->getPermissionNames(), "messages" => "Two Factor Authentication Verified."];
        }

        return ["token" => null, "permissions" => [] , "messages" => trans('auth.failed')];
    }
    public function banUser(Request $request)
    {
        $user = $request->user();
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && $user->id != $request->id) {
            $banUser =  User::find($request->id);
            $banUser->is_active = false;
            $banUser->save();
            $this->inactiveUserShops($banUser->id);
            return $banUser;
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }
    function inactiveUserShops($userId)
    {
        $shops = Shop::where('owner_id', $userId)->get();
        foreach ($shops as $shop) {
            $shop->is_active = false;
            $shop->save();
            Product::where('shop_id', '=', $shop->id)->update(['status' => 'draft']);
        }
    }

    public function activeUser(Request $request)
    {
        $user = $request->user();
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && $user->id != $request->id) {
            $activeUser =  User::find($request->id);
            $activeUser->is_active = true;
            $activeUser->save();
            return $activeUser;
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    public function forgetPassword(Request $request)
    {
        $user = $this->repository->findByField('email', $request->email);
        if (count($user) < 1) {
            return ['messages' => config('message.EMAIL_NOT_FOUND'), 'success' => false];
        }
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();
        $token = Str::random(16);
        $resetUrl = (config('shop.shop_url') .'/forgotPassword/'. $token);
        // $resetUrl = 'http://localhost:3000/'.'forgot-password/'. $token;
        if (!$tokenData) {
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'expires_at' => now()->addMinutes(30), // Set expiration time (30 minutes)
                'created_at' => Carbon::now(),
            ]);
        }
        else {
            // Update the existing token and expiration time
            DB::table('password_resets')
                ->where('email', $request->email)
                ->update([
                    'token' => $token,
                    'expires_at' => now()->addMinutes(30),
                    'created_at' => Carbon::now(),
                ]);
        }

        if ($this->repository->sendResetEmail($request->email, $resetUrl)) {
            return ['messages' => CHECK_INBOX_FOR_PASSWORD_RESET_EMAIL, 'success' => true];
        } else {
            return ['messages' => SOMETHING_WENT_WRONG, 'success' => false];
        }
    }
    public function verifyForgetPasswordToken(Request $request)
    {
        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$tokenData) {
            return ['messages' => INVALID_TOKEN, 'success' => false];
        }
        if (now()->gt($tokenData->expires_at)) {
            return ['messages' => "Token Expired.", 'success' => false];
        }
        $user = $this->repository->findByField('email', $tokenData->email);
        if (count($user) < 1) {
            return ['messages' => NOT_FOUND, 'success' => false];
        }
        return ['messages' => TOKEN_IS_VALID, 'success' => true, 'email'=>$tokenData->email];
    }
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => ['required','string',Password::defaults()],
                'token' => 'required|string'
            ]);

            // Check if the token has expired
            $tokenData = DB::table('password_resets')->where('token', $request->token)->first();

            if (!$tokenData || now()->gt($tokenData->expires_at)) {
                return ['messages' => INVALID_TOKEN, 'success' => false];
            }
            $user = User::where('email', $tokenData->email)->first();
            if (!$user) {
                return response()->json(['messages' => 'User not found'], 404);
            }
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_resets')->where('email', $user->email)->delete();

            return ['messages' => PASSWORD_RESET_SUCCESSFUL, 'success' => true,'email'=>$tokenData->email];
        } catch (ValidationException $e) {
            // If validation fails, you can handle the errors here.
            return response()->json(['messages' => $e->validator->errors()->first(), 'success' => false],422);
        } catch (\Exception $th) {
            return ['messages' => SOMETHING_WENT_WRONG, 'success' => false];
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();
            if (Hash::check($request->oldPassword, $user->password)) {
                $user->password = Hash::make($request->newPassword);
                $user->save();
                return ['messages' => PASSWORD_RESET_SUCCESSFUL, 'success' => true];
            } else {
                return ['messages' => OLD_PASSWORD_INCORRECT, 'success' => false];
            }
        } catch (\Exception $th) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function contactAdmin(Request $request)
    {
        try {
            $details = $request->only('subject', 'name', 'email', 'description');
            Mail::to(config('shop.admin_email'))->send(new ContactAdmin($details));
            return ['messages' => EMAIL_SENT_SUCCESSFUL, 'success' => true];
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    public function fetchStaff(Request $request)
    {
        if (!isset($request->shop_id)) {
            throw new MarvelException(NOT_AUTHORIZED);
        }
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->with(['profile'])->where('shop_id', '=', $request->shop_id);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    public function staffs(Request $request)
    {
        $query = $this->fetchStaff($request);
        $limit = $request->limit ?? 15;
        return $query->paginate($limit);
    }

    public function socialLogin(Request $request)
    {
        $provider = $request->provider;
        $token = $request->access_token;
        $this->validateProvider($provider);
        try {
            // $guzzleClient = new \GuzzleHttp\Client(['base_uri' => 'https://www.googleapis.com/oauth2/v1/']);
            // $response = $guzzleClient->get("tokeninfo?access_token={$token}");
            // $tokenInfo = json_decode($response->getBody(), true);
            // dd($response);
            $user = Socialite::driver($provider)->userFromToken($token);
            $userExist = User::where('email',  $user->email)->exists();

            $name = $user->getName();

            Log::info('social media name - '.$name);
            $split_name = explode(' ',$name);

            $userCreated = User::firstOrCreate(
                [
                    'email' => $user->getEmail()
                ],
                [
                    'email_verified_at' => now(),
                    'first_name' => (current($split_name)) ? current($split_name) : '',
                    'last_name'  => (end($split_name)) ? end($split_name) : ''
                ]
            );

            $userCreated->providers()->updateOrCreate(
                [
                    'provider' => $provider,
                    'provider_user_id' => $user->getId(),
                ]
            );

            $avatar = [
                'thumbnail' => $user->getAvatar(),
                'original' => $user->getAvatar(),
            ];

            $userCreated->profile()->updateOrCreate(
                [
                    'avatar' => $avatar
                ]
            );

            if (!$userCreated->hasPermissionTo(Permission::CUSTOMER)) {
                $userCreated->givePermissionTo(Permission::CUSTOMER);
            }

            if (empty($userExist)) {
                $this->giveSignupPointsToCustomer($userCreated->id);
            }

            $userDetail = $this->repository->with(['profile', 'wallet', 'address'])->find($userCreated->id);

            return ["token" => $userCreated->createToken('auth_token')->plainTextToken,'userDetail' => $userDetail,"permissions" => $userCreated->getPermissionNames(),"messages" => trans('auth.success')];
        } catch (\Exception $e) {
            //dd($e);
            throw new MarvelException(INVALID_CREDENTIALS);
        }
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            throw new MarvelException(PLEASE_LOGIN_USING_FACEBOOK_OR_GOOGLE);
        }
    }

    protected function getOtpGateway()
    {
        $gateway = config('auth.active_otp_gateway');
        $gateWayClass = "App\\Otp\\Gateways\\" . ucfirst($gateway) . 'Gateway';
        return new OtpGateway(new $gateWayClass());
    }

    protected function verifyOtp(Request $request)
    {
        $id = $request->otp_id;
        $code = $request->code;
        $phoneNumber = $request->phone_number;
        try {
            $otpGateway = $this->getOtpGateway();
            $verifyOtpCode = $otpGateway->checkVerification($id, $code, $phoneNumber);
            if ($verifyOtpCode->isValid()) {
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function sendOtpCode(Request $request)
    {
        $phoneNumber = $request->phone_number;
        try {
            $user = User::where('phone', $request->phoneNumber)
            ->where('is_active', true)
            ->first();
            $user->generateTwoFactorCode();
            $plain_token = $user->createToken('auth_token')->plainTextToken;
            $token = $user->tokens()
            ->where('name', 'auth_token')
            ->latest()
            ->first();
            if ($token) {
                $token->expires_at = now()->addHours(10);
                $token->save();
            }

            $userDetail = $this->repository->with(['profile', 'wallet', 'address'])->find($user->id);
            /* 2fa you mail start */
            $tags = [
                'app_url' => env("APP_URL"),
                "app_name" => env("APP_NAME"),
                'name' => $user->first_name." " . $user->last_name,
                "user" => $user,
                "url" => config('app.url').'/shop'
            ];
            $toIds=array($user->email);
            //TODO: currently sending email, update code for sms gateway
            $this->sendEmailNotification('TwoFactorAuthMail', $toIds,$tags);

            return ["token" => $plain_token,'userDetail' => $userDetail, "permissions" => $user->getPermissionNames(), "messages" => trans('auth.success')];

        } catch (\Exception $e) {
            throw new MarvelException(INVALID_GATEWAY);
        }
    }

    public function verifyOtpCode(Request $request)
    {
        try {
            if ($this->verifyOtp($request)) {
                return [
                    "messages" => OTP_SEND_SUCCESSFUL,
                    "success" => true,
                ];
            }
            throw new MarvelException(OTP_VERIFICATION_FAILED);
        } catch (\Throwable $e) {
            throw new MarvelException(OTP_VERIFICATION_FAILED);
        }
    }

    public function otpLogin(Request $request)
    {
        $phoneNumber = $request->phone_number;

        try {
            if ($this->verifyOtp($request)) {
                // check if phone number exist
                $profile = Profile::where('contact', $phoneNumber)->first();
                $user = '';
                if (!$profile) {
                    // profile not found so could be a new user
                    $name = $request->name;
                    $email = $request->email;
                    if ($name && $email) {
                        $userExist = User::where('email',  $email)->exists();
                        $user = User::firstOrCreate([
                            'email'     => $email
                        ], [
                            'name'    => $name,
                        ]);
                        $user->givePermissionTo(Permission::CUSTOMER);
                        $user->profile()->updateOrCreate(
                            ['customer_id' => $user->id],
                            [
                                'contact' => $phoneNumber
                            ]
                        );
                        if (empty($userExist)) {
                            $this->giveSignupPointsToCustomer($user->id);
                        }
                    } else {
                        return ['messages' => REQUIRED_INFO_MISSING, 'success' => false];
                    }
                } else {
                    $user = User::where('id', $profile->customer_id)->first();
                }

                return [
                    "token" => $user->createToken('auth_token')->plainTextToken,
                    "permissions" => $user->getPermissionNames()
                ];
            }
            return ['messages' => OTP_VERIFICATION_FAILED, 'success' => false];
        } catch (\Throwable $e) {
            return response()->json(['error' => INVALID_GATEWAY], 422);
        }
    }

    public function updateContact(Request $request)
    {
        $phoneNumber = $request->phone_number;
        $user_id = $request->user_id;

        try {
            if ($this->verifyOtp($request)) {
                $user = User::find($user_id);
                $user->profile()->updateOrCreate(
                    ['customer_id' => $user_id],
                    [
                        'contact' => $phoneNumber
                    ]
                );
                return [
                    "messages" => CONTACT_UPDATE_SUCCESSFUL,
                    "success" => true,
                ];
            }
            return ['messages' => CONTACT_UPDATE_FAILED, 'success' => false];
        } catch (\Exception $e) {
            return response()->json(['error' => INVALID_GATEWAY], 422);
        }
    }

    public function addPoints(Request $request)
    {
        $request->validate([
            'points' => 'required|numeric',
            'customer_id' => ['required', 'exists:App\Database\Models\User,id']
        ]);
        $points = $request->points;
        $customer_id = $request->customer_id;

        $wallet = Wallet::firstOrCreate(['customer_id' => $customer_id]);
        $wallet->total_points = $wallet->total_points + $points;
        $wallet->available_points = $wallet->available_points + $points;
        $wallet->save();
    }

    public function makeOrRevokeAdmin(Request $request)
    {
        $user = $request->user();
        if ($this->repository->hasPermission($user)) {
            $user_id = $request->user_id;
            try {
                $newUser = $this->repository->findOrFail($user_id);
                if ($newUser->hasPermissionTo(Permission::SUPER_ADMIN)) {
                    $newUser->revokePermissionTo(Permission::SUPER_ADMIN);
                    return true;
                }
            } catch (Exception $e) {
                throw new MarvelException(USER_NOT_FOUND);
            }
            $newUser->givePermissionTo(Permission::SUPER_ADMIN);

            return true;
        }

        throw new MarvelException(NOT_AUTHORIZED);
    }
    public function subscribeToNewsletter(Request $request)
    {
        try {
            $email = $request->email;
            Newsletter::subscribe($email);
            return true;
        } catch (\Throwable $th) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function sendOtpToEmail(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => false,'messages' => $error_message], 422);
        }
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json(['status' => false,'messages' => "User not exists."], 422);
        }
        try{
            $user->generateTwoFactorCode();
            /* 2fa you mail start */
            $tags = [
               'app_url' => env("APP_URL"),
               "app_name" => env("APP_NAME"),
               'name' => $user->first_name." " . $user->last_name,
               "user" => $user,
               "url" => config('app.url').'/home'
           ];
           $toIds=array($user->email);
           SendEmailJob::dispatch('TwoFactorAuthMail', $toIds,$tags);
           //$this->sendEmailNotification('TwoFactorAuthMail', $toIds,$tags);

        //    $plain_token = $user->createToken('auth_token')->plainTextToken;
        //    $token = $user->tokens()
        //    ->where('name', 'auth_token')
        //    ->latest()
        //    ->first();
        //    if ($token) {
        //        $token->expires_at = now()->addHours(10);
        //        // $token->expires_at = now()->addMinutes(1);
        //        $token->save();
        //    }

            /* send otp message */
            return response()->json(['status' => true,'token' => null,'messages' => 'OTP sent successfully.'], 200);


        } catch(\Exception $e){
            return response()->json(['status' => false,'messages' => __('message.internal_server')], 500);
        }
    }

    public function verifyEmailOtp(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => false,'messages' => $error_message], 422);
        }

        try{

            // $phoneVerification = PhoneVerification::where('phone_number',$request->phone_number)->first();
            // if(!$phoneVerification){
            //     return response()->json(['status' => false,'messages' => "Phone number is not exists."], 422);
            // }
            // if($phoneVerification->otp != $request->otp){
            //     return response()->json(['status' => false,'messages' => "Invalid OTP provided."], 422);
            // }
            // //check for expire code
            // $current_time = \Carbon\Carbon::now()->timestamp;
            // $created_time = $phoneVerification->updated_at->timestamp;
            // if(($current_time - $created_time) > 1000){
            //     //return response()->json(['status' => true,'messages' => __('message.expire_otp')], 200);
            // }
            // // end check for expire code

            /* start check phone exist or not */
            $user = User::where('email',$request->email)->where('deleted_at',null)->first();
            if($user){
                // $plain_token = $user->createToken('auth_token')->plainTextToken;
                if ($request->input('otp') == $user->two_factor_code) {
                    $user->resetTwoFactorCode();
                    $plain_token = $user->createToken('auth_token')->plainTextToken;
                    $token = $user->tokens()
                    ->where('name', 'auth_token')
                    ->latest()
                    ->first();
                    if ($token) {
                        $token->expires_at = now()->addHours(10);
                        // $token->expires_at = now()->addMinutes(1);
                        $token->save();
                    }

                    $userDetail = $this->repository->with(['profile', 'wallet', 'address'])->find($user->id);
                    return ["token" => $plain_token,"userDetail" => $userDetail, "permissions" => $user->getPermissionNames(), "messages" => trans('auth.success')];
                }

                return ["token" => null,"permissions" => null, "messages" => trans('auth.failed')];
            }
            else{
                return response()->json(['status' => false,'messages' => "User not exists."], 422);

            }
            /* end check phone exist or not */
            return response()->json(['status' => true,'token'=> null,'messages' => __('message.verify_otp')], 200);
        } catch(\Exception $e){
            return response()->json(['status' => false,'messages' => __('message.internal_server')], 500);
        }
    }

    public function sendOtpForRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required',
            'mobile' =>'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => false,'messages' => $error_message], 422);
        }
        
        // Remove user existence check - user will be created only after OTP verification
        // $user = User::where('email',$request->email)->first();
        // if($user){
        //     return response()->json(['status' => false,'messages' => "User Already Exists."], 422);
        // }
        
        try{
            // Generate random 6 digit OTP - SAME OTP FOR ALL CHANNELS
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in database FIRST - only email and phone for verification
            $phoneVerification = PhoneVerification::where('email',$request->email)->first();
            if($phoneVerification){
                $phoneVerify = PhoneVerification::find($phoneVerification->id);
                $phoneVerify->otp = $otp;
                $phoneVerify->phone_number = $request->mobile;
                $phoneVerify->save();
            }else{
                $phone_verification = new PhoneVerification();
                $phone_verification->email = $request->email;
                $phone_verification->phone_number = $request->mobile;
                $phone_verification->otp = $otp;
                $phone_verification->save();
            }
            
            // Send SMS OTP using Twilio with the SAME OTP
            try {
                $twilioGateway = new \App\Otp\Gateways\TwilioSmsGateway();
                $result = $twilioGateway->startVerification($request->mobile, $otp); // Pass the OTP
                
                if ($result->isValid()) {
                    // SMS sent successfully - also send email as backup
                    $tags = [
                        'app_url' => env("APP_URL"),
                        "app_name" => env("APP_NAME"),
                        'name' => $request->email,
                        "otp" => $otp,
                        'expiresInMinutes' => '14',
                        "url" => config('app.url').'/home'
                    ];
                    $toIds=array($request->email);
                    SendEmailJob::dispatch('TwoFactorAuthMail', $toIds,$tags);
                    
                    return response()->json([
                        'status' => true,
                        'token' => null,
                        'messages' => 'OTP sent successfully to both SMS and email.',
                        'debug_otp' => $otp // For testing purposes
                    ], 200);
                } else {
                    // SMS failed, send email only
                    $tags = [
                        'app_url' => env("APP_URL"),
                        "app_name" => env("APP_NAME"),
                        'name' => $request->email,
                        "otp" => $otp,
                        'expiresInMinutes' => '14',
                        "url" => config('app.url').'/home'
                    ];
                    $toIds=array($request->email);
                    SendEmailJob::dispatch('TwoFactorAuthMail', $toIds,$tags);
                    
                    return response()->json([
                        'status' => true,
                        'token' => null,
                        'messages' => 'SMS failed. OTP sent to your email.',
                        'debug_otp' => $otp // For testing purposes
                    ], 200);
                }
            } catch (\Exception $smsException) {
                // SMS failed, send email only
                $tags = [
                    'app_url' => env("APP_URL"),
                    "app_name" => env("APP_NAME"),
                    'name' => $request->email,
                    "otp" => $otp,
                    'expiresInMinutes' => '14',
                    "url" => config('app.url').'/home'
                ];
                $toIds=array($request->email);
                SendEmailJob::dispatch('TwoFactorAuthMail', $toIds,$tags);
                
                return response()->json([
                    'status' => true,
                    'token' => null,
                    'messages' => 'SMS failed. OTP sent to your email.',
                    'debug_otp' => $otp // For testing purposes
                ], 200);
            }

        } catch(\Exception $e){
            return response()->json(['status' => false,'messages' => __('message.internal_server')], 500);
        }
    }

    public function verifyOtpForRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'email', 'unique:users'], // Add unique validation back
            'mobile' => ['required'],
            'otp' => 'required',
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['nullable', 'string', 'max:255'],
            'password'      => ['required', 'string',Password::defaults()],
            'newsletter'    => ['nullable'],
            'gender'        => ['nullable',Rule::in(['Mr','Mrs','Other'])],
            'birthdate'     => ['nullable','date'],
            'shop_id'       => ['nullable', 'exists:App\Database\Models\Shop,id'],
            'profile'       => ['array'],
            'address'       => ['array'],
            'permission'    => ['nullable'],
            'phone'         => ['nullable'],
            'pincode'       => ['nullable'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = $errors->first();
            return response()->json(['status' => false,'messages' => $error_message], 422);
        }

        try{
            // Check if user already exists before OTP verification
            $existingUser = User::where('email', $request->email)->first();
            if($existingUser){
                return response()->json(['status' => false,'messages' => "User Already Exists. Please login instead."], 422);
            }
            
            // verify otp and register user
            $phoneVerification = PhoneVerification::where('email',$request->email)
                ->where('phone_number', $request->mobile)
                ->first();
            if(!$phoneVerification){
                return response()->json(['status' => false,'messages' => "Email or phone number not found."], 422);
            }
            if($phoneVerification->otp != $request->otp){
                return response()->json(['status' => false,'messages' => "Invalid OTP provided."], 422);
            }
            //check for expire code
            $current_time = \Carbon\Carbon::now()->timestamp;
            $created_time = $phoneVerification->updated_at->timestamp;
            // check time for 15 minutes
            if(($current_time - $created_time) > 900){
                return response()->json(['status' => true,'messages' => __('message.expire_otp')], 200);
            }
            // end check for expire code
            
            // Clear the OTP after successful verification
            $phoneVerification->delete();
            
            // register user only after successful OTP verification
            return $this->registerProcess($request);

        } catch(\Exception $e){
            return response()->json(['status' => false,'messages' => __('message.internal_server')], 500);
        }

    }

    public function registerProcess($request){
        // Check if user already exists before creating
        $existingUser = User::where('email', $request->email)->first();
        if($existingUser){
            // User already exists, return success response with existing user data
            $plain_token = $existingUser->createToken('auth_token')->plainTextToken;
            // Remove token expiration code since personal_access_tokens table doesn't have expires_at column
            // $token = $existingUser->tokens()
            // ->where('name', 'auth_token')
            // ->latest()
            // ->first();
            // if ($token) {
            //     $token->expires_at = now()->addHours(10);
            //     $token->save();
            // }

            $userDetail = $this->repository->with(['profile', 'wallet', 'address'])->find($existingUser->id);

            return ["token" => $plain_token,'userDetail' => $userDetail, "permissions" => $existingUser->getPermissionNames(),'messages' => 'User Register & Logged In Successfully...'];
        }

        $notAllowedPermissions = [Permission::SUPER_ADMIN];
        if ((isset($request->permission->value) && in_array($request->permission->value, $notAllowedPermissions)) || (isset($request->permission) && in_array($request->permission, $notAllowedPermissions))) {
            throw new MarvelException(NOT_AUTHORIZED);
        }
        $permissions = [Permission::CUSTOMER];
        if (isset($request->permission)) {
            $permissions[] = isset($request->permission->value) ? $request->permission->value : $request->permission;
        }
        $newsletter = false;
        if($request->newsletter){
            $newsletter = true;
        }

        $user = $this->repository->create([
            'first_name'     => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'birthdate'     => $request->birthdate,
            'gender'        => $request->gender,
            'phone_code'     => $request->phone_code ?? '+91',
            'phone'        => $request->mobile,
            'password'      => Hash::make($request->password),
            'newsletter'    => $newsletter
        ]);
        $user->givePermissionTo($permissions);

        //add permission in add_user_role - Fixed to use correct role ID
        $role_id = \App\Models\Role::where('title','User')->pluck('id')->first();
        if (!$role_id) {
            // Fallback to first available role if 'User' not found
            $role_id = \App\Models\Role::first()->id;
        }
        
        if ($role_id) {
            \DB::table('admin_role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $role_id
            ]);
        }
        //add permission in add_user_role end
        $this->giveSignupPointsToCustomer($user->id);
        // $user->generateTwoFactorCode();
        //  /* 2fa you mail start */
        //  $tags = [
        //     'app_url' => env("APP_URL"),
        //     "app_name" => env("APP_NAME"),
        //     'name' => $user->first_name." " . $user->last_name,
        //     "user" => $user,
        //     "url" => config('app.url').'/shop'
        // ];
        // $toIds=array($user->email);
        //SendEmailJob::dispatch('TwoFactorAuthMail', $toIds,$tags);
        /* 2fa mail end */
        /* register thank you mail start */
        $tags = [
            'app_url' => env("APP_URL"),
            "app_name" => env("APP_NAME"),
            'name' => $user->first_name." " . $user->last_name,
            "user" => $user,
            "url" => config('shop.shop_url').'/home'
        ];
        $toIds=array($user->email);
        SendEmailJob::dispatch('NewCustomerRegistration', $toIds,$tags);
        //$this->sendEmailNotification('NewCustomerRegistration', $toIds,$tags);
        /* register thank you mail end */
        $plain_token = $user->createToken('auth_token')->plainTextToken;
        // Remove token expiration code since personal_access_tokens table doesn't have expires_at column

        $userDetail = $this->repository->with(['profile', 'wallet', 'address'])->find($user->id);

        return ["token" => $plain_token,'userDetail' => $userDetail, "permissions" => $user->getPermissionNames(),'messages' => 'User Register & Logged In Successfully...'];
    }

    public function deleteUserRequest(CreateUserDeleteRequest $request){
        try{
            $user = UserDeleteRequest::create($request->all());
            return response()->json(['status' => true,'messages' => 'Request submitted successfully.'], 200);
        } catch(\Exception $e){
            dd($e->getMessage());
            return response()->json(['status' => false,'messages' => __('message.internal_server')], 500);
        }
    }
}
