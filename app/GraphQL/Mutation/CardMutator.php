<?php


namespace App\GraphQL\Mutation;


use App\Exceptions\MarvelException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class CardMutator
{
    /**
     * @throws MarvelException
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('App\Http\Controllers\PaymentMethodController@deletePaymentMethod', $args);
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * @throws MarvelException
     */
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('App\Http\Controllers\PaymentMethodController@store', $args);
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * @throws MarvelException
     */
    public function setDefaultPaymentMethod($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('App\Http\Controllers\PaymentMethodController@setDefaultPaymentMethod', $args);
        } catch (\Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
