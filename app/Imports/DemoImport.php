<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DemoImport implements ToModel, WithHeadingRow, WithStartRow
{
    protected $headerNames;

    public function headingRow(): int
    {
        return 1;
    }
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (!$this->headerNames) {
            $this->headerNames = $row;
            return [];
        }

        // Now $this->headerNames contains header names
        // Use it to get values from the corresponding columns

        $data = [];
        foreach ($this->headerNames as $key => $headerName) {
            $data[$headerName] = $row[$key] ?? null;
        }

        // Find the 4th column dynamically
    //     $fourthColumnIndex = array_search('dimension_image', array_keys($this->headerNames));
    //    // dd($fourthColumnIndex);
    //     $fourthColumnValue = $fourthColumnIndex !== false ? $row[$fourthColumnIndex] : null;
        //dd($fourthColumnValue);
        //$numberOfColumns = count($row);
        //dd($numberOfColumns);

        $totalColumn = count($data);

        $thirdColumnIndex = 2; // Assuming you want to find the header name at the 3rd index
        $thirdColumnName = array_keys($this->headerNames)[$thirdColumnIndex] ?? null;
        dd($thirdColumnName);

    }
}
