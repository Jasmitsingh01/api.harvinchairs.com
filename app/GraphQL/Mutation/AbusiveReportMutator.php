<?php


namespace App\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class AbusiveReportMutator
{
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\AbusiveReportController@store', $args);
    }

    public function accept($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\AbusiveReportController@accept', $args);
    }

    public function reject($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\AbusiveReportController@reject', $args);
    }
}
