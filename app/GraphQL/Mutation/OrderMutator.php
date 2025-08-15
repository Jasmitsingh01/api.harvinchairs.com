<?php


namespace App\GraphQL\Mutation;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Exceptions\MarvelException;
use App\Facades\Shop;

class OrderMutator
{

    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\OrderController@store', $args);
    }
    public function update($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\OrderController@updateOrderGql', $args);
    }
    public function generateInvoiceDownloadUrl($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\OrderController@downloadInvoiceUrl', $args);
    }
    public function createOrderPayment($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\OrderController@submitPayment', $args);
    }
}
