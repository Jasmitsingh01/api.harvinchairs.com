<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class ManufacturerQuery
{
    public function topManufacturer($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ManufacturerController@topManufacturer', $args);
    }
}
