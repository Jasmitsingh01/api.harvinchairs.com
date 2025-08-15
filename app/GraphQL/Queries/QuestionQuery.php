<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class QuestionQuery
{
    public function fetchShops($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\QuestionController@index', $args);
    }
}
