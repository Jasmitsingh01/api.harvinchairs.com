<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class FeedbackMutator
{
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\FeedbackController@store', $args);
    }
}
