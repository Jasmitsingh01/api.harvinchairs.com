<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class TypeMutator
{
    public function storeType($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\TypeController@store', $args);
    }
    public function updateType($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\TypeController@updateType', $args);
    }
}
