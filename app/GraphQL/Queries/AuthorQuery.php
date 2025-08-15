<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class AuthorQuery
{
    public function topAuthor($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\AuthorController@topAuthor', $args);
    }
}
