<?php

namespace App\Livewire\Dealer;

use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Collection; // Import the Collection class

#[Layout('components.layouts.admin')]
#[Title('Dealer Dashboard')]
class Dashboard extends Component
{
    public int $pendingRedemptions = 0;
    public int $fulfilledToday = 0;
    public int $fulfilledThisMonth = 0;

    // THE FIX: Initialize $recentClaims as an empty Collection.
    public Collection $recentClaims;

    public function mount()
    {
        // Initialize with an empty collection to prevent errors
        $this->recentClaims = new Collection();

        $dealer = Auth::user()->dealer;

        if ($dealer) {
            // Get the IDs of all shops belonging to this dealer
            $shopIds = $dealer->retailShops()->pluck('id');

            // Calculate stats based only on claims from this dealer's shops
            $this->pendingRedemptions = Claim::whereIn('retail_shop_id', $shopIds)
                ->where('status', 'Processing')
                ->count();

            $this->fulfilledToday = Claim::whereIn('retail_shop_id', $shopIds)
                ->where('status', 'Fulfilled')
                ->whereDate('updated_at', today())
                ->count();

            $this->fulfilledThisMonth = Claim::whereIn('retail_shop_id', $shopIds)
                ->where('status', 'Fulfilled')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count();

            // Get the 5 most recently fulfilled claims for the activity feed
            $this->recentClaims = Claim::whereIn('retail_shop_id', $shopIds)
                ->where('status', 'Fulfilled')
                ->with('collectionPoint')
                ->latest('updated_at')
                ->take(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dealer.dashboard');
    }
}
