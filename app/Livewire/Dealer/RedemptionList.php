<?php

namespace App\Livewire\Dealer;

use App\Models\Campaign;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.admin')]
#[Title('Redemption List')]
class RedemptionList extends Component
{
    use WithPagination;

    // Properties for filtering
    public string $filterStatus = '';
    public ?int $filterShopId = null;
    public ?int $filterCampaignId = null; // <-- NEW

    public $shops;
    public $campaigns; // <-- NEW

    public function mount()
    {
        $dealer = Auth::user()->dealer;

        if ($dealer) {
            $this->shops = $dealer->retailShops()->orderBy('shop_name')->get();
        } else {
            $this->shops = collect();
        }

        // Get all active campaigns for the filter dropdown
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get();
    }

    public function markAsFulfilled(Claim $claim)
    {
        if ($claim->collectionPoint->dealer_id !== Auth::user()->dealer_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $claim->status = 'Fulfilled';
        $claim->save();

        session()->flash('success', "Claim #{$claim->claim_id} has been marked as fulfilled.");
    }

    public function render()
    {
        $dealer = Auth::user()->dealer;
        $claimsQuery = Claim::query();

        if ($dealer) {
            $shopIds = $dealer->retailShops()->pluck('id');
            $claimsQuery->whereIn('retail_shop_id', $shopIds);
        } else {
            $claimsQuery->whereRaw('1 = 0');
        }

        // Apply Campaign filter
        if (!empty($this->filterCampaignId)) {
            $claimsQuery->where('campaign_id', $this->filterCampaignId);
        }

        // Apply status filter
        if (!empty($this->filterStatus)) {
            $claimsQuery->where('status', $this->filterStatus);
        }

        // Apply shop filter
        if (!empty($this->filterShopId)) {
            $claimsQuery->where('retail_shop_id', $this->filterShopId);
        }

        $claims = $claimsQuery->with(['product', 'collectionPoint', 'campaign'])->latest()->paginate(20);

        return view('livewire.dealer.redemption-list', [
            'claims' => $claims,
        ]);
    }
}
