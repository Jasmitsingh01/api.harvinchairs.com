<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class WishlistMutator
{
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\WishlistController@store', $args);
    }

    public function toggle($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\WishlistController@toggle', $args);
    }

    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\WishlistController@delete', $args);
    }
}
