<?php

namespace App\Http\Controllers\Dealer;

use App\Exports\RedemptionExport;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportRedemptions(Request $request)
    {
        $dealer = Auth::user()->dealer;
        if (!$dealer) {
            abort(403);
        }

        $shopIds = $dealer->retailShops()->pluck('id');

        $claimsQuery = Claim::with(['product', 'collectionPoint'])
            ->whereIn('retail_shop_id', $shopIds);

        // Apply filters from the request
        if ($request->filled('campaign_id')) {
            $claimsQuery->where('campaign_id', $request->input('campaign_id'));
        }
        if ($request->filled('status')) {
            $claimsQuery->where('status', $request->input('status'));
        }
        if ($request->filled('shop_id')) {
            $claimsQuery->where('retail_shop_id', $request->input('shop_id'));
        }

        $claims = $claimsQuery->latest()->get();

        $fileName = 'redemption_list_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new RedemptionExport($claims), $fileName);
    }
}
