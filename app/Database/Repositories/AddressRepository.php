<?php


namespace App\Database\Repositories;

use Exception;
use App\Models\Country;
use App\Enums\Permission;
use App\Database\Models\Address;
use App\Exceptions\MarvelException;

class AddressRepository extends BaseRepository
{
     /**
     * @var array
     */
    protected $fieldSearchable = [
        'title'        => 'like',
        'type',
        'customer_id',
    ];

    protected $dataArray = [
        'first_name',
        'last_name',
        'address_name',
        'email',
        'mobile_number',
        'alternate_mobile_number',
        'postal_code',
        'type',
        'society',
        'area',
        'landmark',
        'city',
        'state',
        'is_service_lift',
        'customer_id'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Address::class;
    }
    public function storeAddress($request)
    {
        try {
            //$useWalletPoints = isset($request->use_wallet_points) ? $request->use_wallet_points : false;
            if ($request->user() && $request->user()->hasPermissionTo(Permission::SUPER_ADMIN) && isset($request['customer_id'])) {
                $request['customer_id'] =  $request['customer_id'];
            } else {
                $request['customer_id'] = $request->user()->id ?? null;
            }
            $addressInput = $request->only($this->dataArray);
            // // dd($request->address);
            // if(isset($request->address)){

            //     $addressInput['zipcode'] = $request->address['zip'] ? $request->address['zip'] : null;
            //     $addressInput['country'] = $request->address['country'] ? $request->address['country'] : null;
            // }
            // $country = Country::where('name','like',$request->address['country'])->first();
            // $addressInput['zone_id'] = 1;
            // if(isset($country->zone_id)){
            //     $addressInput['zone_id'] =  $country->zone_id;
            // }
            return $this->create($addressInput);
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
    public function updateAddress($request, $address)
    {
        $addressInput =$request->only($this->dataArray);
        // if(isset($request->address)){

        //     $addressInput['zipcode'] = $request->address['zip'] ? $request->address['zip'] : null;
        //     $addressInput['country'] = $request->address['country'] ? $request->address['country'] : null;
        // }
        // $country = Country::where('name','like',$request->address['country'])->first();
        // if(isset($country->zone_id)){
        //     $addressInput['zone_id'] =  $country->zone_id;
        // }
        $address->update($addressInput);
        return $this->with('customer')->findOrFail($address->id);
    }
}
