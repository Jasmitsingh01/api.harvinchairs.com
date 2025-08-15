<?php


namespace App\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class CardQuery
{
    public function fetchCards($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\PaymentMethodController@index', $args);
    }
}
