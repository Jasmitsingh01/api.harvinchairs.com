<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait AttributeCombinationTrait
{
    function generateCombinations($attributes)
    {
        $combinations = [];
        $keys = array_keys($attributes);
        $totalKeys = count($keys);
        $totalCombinations = array_product(array_map('count', $attributes));

        for ($i = 0; $i < $totalCombinations; $i++) {
            $combination = [];

            foreach ($keys as $index => $key) {
                $valueIndex = floor($i / array_product(array_slice(array_map('count', $attributes), $index + 1))) % count($attributes[$key]);
                $combination[$key] = $attributes[$key][$valueIndex];
            }

            $combinations[($i)] = $combination;
        }

        return $combinations;
    }
}
