<?php


namespace App\GraphQL\Mutation;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class CheckoutMutator
{

    public function verify($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\CheckoutController@verify', $args);
    }
}
