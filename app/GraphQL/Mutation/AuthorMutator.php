<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class AuthorMutator
{
    public function storeAuthor($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\AuthorController@store', $args);
    }
    public function updateAuthor($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\AuthorController@updateAuthor', $args);
    }
}
