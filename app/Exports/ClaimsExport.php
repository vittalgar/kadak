<?php

namespace App\Exports;

use App\Models\Claim;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class ClaimsExport implements FromQuery, WithHeadings, WithMapping
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        // THE FIX: The query is now much simpler.
        return $this->query->with(['campaign', 'product']);
    }

    public function headings(): array
    {
        return [
            'Claim ID', 'Winner Name', 'Mobile', 'City', 'State',
            'Prize Won', 'Status', 'Claim Date', 'Product Selected', 'Campaign',
        ];
    }

    public function map($claim): array
    {
        return [
            $claim->claim_id,
            $claim->name,
            $claim->mobile,
            $claim->city,
            $claim->state,
            $claim->prize_won,
            $claim->status,
            $claim->created_at->format('Y-m-d H:i'),
            $claim->product->name ?? 'N/A',
            $claim->campaign->name ?? 'N/A',
        ];
    }
}