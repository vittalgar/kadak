<?php

namespace App\Imports;

use App\Models\Prize;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PrizesImport implements ToModel, WithHeadingRow, WithValidation
{
    protected int $campaignId;

    public function __construct(int $campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // This method is called for each row in the spreadsheet.
        return new Prize([
            'campaign_id'     => $this->campaignId, // Link to the correct campaign
            'name'            => $row['name'],
            'category'        => $row['category'],
            'total_stock'     => $row['total_stock'],
            'remaining_stock' => $row['total_stock'], // Remaining stock is same as total on creation
            'weight'          => $row['weight'],
            'is_active'       => true,
        ]);
    }

    /**
     * Define the validation rules for each row in the spreadsheet.
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'category'      => 'required|string|in:Common,Mid-Value,High-Value,Grand',
            'total_stock'   => 'required|integer|min:0',
            'weight'        => 'required|integer|min:1',
        ];
    }
}
