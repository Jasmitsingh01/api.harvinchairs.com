<?php

namespace App\Database\Repositories;

use App\Jobs\SendMailJob;
use App\Models\Cart;
use App\Mail\ForgetPassword;
use App\Database\Models\Shop;
use App\Database\Models\User;
use App\Database\Models\Address;
use App\Database\Models\Profile;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use App\Enums\Permission as UserPermission;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'email' => 'like',
    ];

    /**
     * @var array
     */
    protected $dataArray = [
        'first_name',
        'last_name',
        'email',
        'is_active',
        'shop_id',
        'newsletter',
        'gender',
        'birthdate',
        'phone_code',
        'phone'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }

    public function storeUser($request)
    {
        try {
            $user = $this->create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $user->givePermissionTo(UserPermission::CUSTOMER);
            if (isset($request['address']) && count($request['address'])) {
                $user->address()->createMany($request['address']);
            }
            if (isset($request['profile'])) {
                $user->profile()->create($request['profile']);
            }
            $user->profile = $user->profile;
            $user->address = $user->address;
            $user->shop = $user->shop;
            $user->managed_shop = $user->managed_shop;
            return $user;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    public function updateUser($request, $user)
    {
        try {
            if (isset($request['address']) && count($request['address'])) {
                foreach ($request['address'] as $address) {
                    if (isset($address['id'])) {
                        Address::findOrFail($address['id'])->update($address);
                    } else {
                        $address['customer_id'] = $user->id;
                        Address::create($address);
                    }
                }
            }
            if(isset($request->permission)){
                $all_permissions = $user->getPermissionNames()->toArray();

                // Compare the current permissions with the new list of permissions
                $permissionsToAttach = array_diff($request->permission, $all_permissions);
                $permissionsToRevoke = array_diff($all_permissions, $request->permission);
                foreach ($permissionsToAttach as $permission) {
                    $user->givePermissionTo($permission);
                }

                // Revoke existing permissions
                foreach ($permissionsToRevoke as $permission) {
                    $user->revokePermissionTo($permission);
                }


            }
            if (isset($request['profile'])) {
                if (isset($request['profile']['id'])) {
                    Profile::findOrFail($request['profile']['id'])->update($request['profile']);
                } else {
                    $profile = $request['profile'];
                    $profile['customer_id'] = $user->id;
                    Profile::create($profile);
                }
            }
            $user->update($request->only($this->dataArray));
            $user->profile = $user->profile;
            $user->address = $user->address;
            $user->shop = $user->shop;
            $user->managed_shop = $user->managed_shop;
            return $user;
        } catch (ValidationException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    public function sendResetEmail($email, $resetUrl)
    {
        try {
            //Mail::to($email)->send(new ForgetPassword($resetUrl));
            SendMailJob::dispatch($email,new ForgetPassword($resetUrl));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
