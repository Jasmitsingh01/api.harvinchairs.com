<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class UserQuery
{
    public function fetchStaff($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\UserController@fetchStaff', $args);
    }
    public function me($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\UserController@me', $args);
    }
    public function fetchDownloadableFiles($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\DownloadController@fetchFiles', $args);
    }

    public function fetchWishlists($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ProductController@fetchWishlists', $args);
    }

    public function inWishlist($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\WishlistController@inWishlist', $args);
    }
}
