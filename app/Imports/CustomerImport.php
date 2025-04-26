<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Customer([
            'name' => $row['name'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
