<?php


namespace App\GraphQL\Mutation;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class RefundMutator
{

    public function createRefund($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\RefundController@store', $args);
    }

    public function updateRefund($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\RefundController@updateRefund', $args);
    }
}
