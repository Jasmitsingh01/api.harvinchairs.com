<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class ReviewMutator
{
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ReviewController@store', $args);
    }

    public function update($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\ReviewController@updateReview', $args);
    }
}
