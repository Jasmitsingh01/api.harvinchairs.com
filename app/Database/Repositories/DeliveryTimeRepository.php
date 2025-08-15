<?php


namespace App\Database\Repositories;

use App\Database\Models\DeliveryTime;

class DeliveryTimeRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryTime::class;
    }
}
