<?php


namespace App\GraphQL\Mutation;


use App\Exceptions\MarvelException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class PaymentIntentMutator
{
    /**
     * @throws MarvelException
     */
    public function savePaymentMethod($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('App\Http\Controllers\PaymentMethodController@savePaymentMethod', $args);
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
