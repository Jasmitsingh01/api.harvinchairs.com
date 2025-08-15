<?php


namespace App\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class ProductQuery
{
    public function relatedProducts($rootValue, array $args, GraphQLContext $context)
    {
        $args['slug'] = $rootValue->slug;
        return Shop::call('App\Http\Controllers\ProductController@relatedProducts', $args);
    }
    public function fetchProducts($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ProductController@fetchProducts', $args);
    }
    public function fetchDigitalFilesForProduct($rootValue, array $args, GraphQLContext $context)
    {
        $args['parent_id'] = $rootValue->id;
        return Shop::call('App\Http\Controllers\ProductController@fetchDigitalFilesForProduct', $args);
    }
    public function fetchDigitalFilesForVariation($rootValue, array $args, GraphQLContext $context)
    {
        $args['parent_id'] = $rootValue->id;
        return Shop::call('App\Http\Controllers\ProductController@fetchDigitalFilesForVariation', $args);
    }
}
