<?php

namespace App\Imports;

use App\Models\RetailShop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RetailShopsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected int $dealerId;

    public function __construct(int $dealerId)
    {
        $this->dealerId = $dealerId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new RetailShop([
            'dealer_id'  => $this->dealerId, // Link to the correct dealer
            'shop_name'  => $row['shop_name'],
            'address'    => $row['address'],
            'city'       => $row['city'],
            'state'      => $row['state'],
            'pincode'    => $row['pincode'],
        ]);
    }

    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|max:255',
            'address'   => 'required|string',
            'city'      => 'required|string|max:100',
            'state'     => 'required|string|max:100',
            'pincode'   => 'required|numeric|digits:6',
        ];
    }
}
