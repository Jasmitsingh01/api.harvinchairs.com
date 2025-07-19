<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class ManufacturerMutator
{
    public function storeManufacturer($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ManufacturerController@store', $args);
    }
    public function updateManufacturer($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ManufacturerController@updateManufacturer', $args);
    }
}
