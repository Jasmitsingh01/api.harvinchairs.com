<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class RefundQuery
{
    public function fetchRefunds($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\RefundController@fetchRefunds', $args);
    }
}
