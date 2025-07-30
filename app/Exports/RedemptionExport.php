<?php

namespace App\Exports;

use App\Models\Claim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RedemptionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $claims;

    public function __construct($claims)
    {
        $this->claims = $claims;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->claims;
    }

    /**
     * @var Claim $claim
     */
    public function map($claim): array
    {
        return [
            $claim->claim_id,
            $claim->name,
            $claim->mobile,
            $claim->prize_won,
            $claim->collectionPoint->shop_name,
            $claim->collectionPoint->address,
            $claim->collectionPoint->city,
            $claim->status,
            $claim->updated_at->timezone('Asia/Kolkata')->format('d-m-Y H:i A'),
        ];
    }

    public function headings(): array
    {
        return [
            'Claim ID',
            'Winner Name',
            'Mobile',
            'Prize Won',
            'Shop Name',
            'Shop Address',
            'City',
            'Status',
            'Last Updated (IST)',
        ];
    }
}
