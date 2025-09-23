<?php

namespace App\Exports;

use App\Models\Claim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterClaimsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // THE FIX: The query now correctly loads the direct agent relationship.
        return Claim::with(['campaign', 'product', 'collectionPoint'])->get();
    }

    public function headings(): array
    {
        return [
            'Claim ID', 'Winner Name', 'Mobile', 'City', 'State', 'Prize Won',
            'Status', 'Claim Date', 'Product Selected', 'Campaign', 'Agent Shop Name', 'Agent Contact Person'
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
            // THE FIX: We now access the properties directly from the agent.
            $claim->collectionPoint->shop_name ?? 'N/A',
            $claim->collectionPoint->contact_person ?? 'N/A',
        ];
    }
}