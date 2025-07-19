<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class ShopQuery
{
    public function fetchShops($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ShopController@fetchShops', $args);
    }

    public function fetchFollowedShops($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ShopController@followedShops', $args);
    }

    public function followedShopsPopularProducts($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ShopController@followedShopsPopularProducts', $args);
    }
}
