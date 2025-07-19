<?php


namespace App\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class SettingsQuery
{
    public function fetchSettings($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\SettingsController@index', $args);
    }
}
