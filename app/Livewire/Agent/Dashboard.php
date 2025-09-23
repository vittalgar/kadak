<?php

namespace App\Livewire\Agent;

use App\Models\Claim;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Agent Dashboard')]
class Dashboard extends Component
{
    public $campaigns = [];
    public string $selectedCampaignId = '';

    public int $pendingRedemptions = 0;
    public int $fulfilledToday = 0;
    public int $fulfilledThisMonth = 0;
    public $recentClaims;
    public $topPrizes;

    public function mount()
    {
        // Fetch all active campaigns for the filter dropdown
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get();

        // Generate the initial report for ALL campaigns
        $this->generateReport();
    }

    // This method runs whenever the dropdown selection changes
    public function updatedSelectedCampaignId()
    {
        $this->generateReport();
    }

    // A dedicated method to calculate the report data based on the filter
    public function generateReport()
    {
        $agent = Auth::user()->agent;
        if (!$agent) {
            return;
        }

        // Start with the base query, securely scoped to the dealer's shops
        $claimsQuery = Claim::where('agent_id', $agent->id);

        // If a specific campaign is selected, add a 'where' clause to the query
        if (!empty($this->selectedCampaignId)) {
            $claimsQuery->where('campaign_id', $this->selectedCampaignId);
        }

        // Calculate Stat Card Data using the filtered query
        $this->pendingRedemptions = $claimsQuery->clone()->where('status', 'Processing')->count();
        $this->fulfilledToday = $claimsQuery->clone()->where('status', 'Fulfilled')->whereDate('updated_at', today())->count();
        $this->fulfilledThisMonth = $claimsQuery->clone()->where('status', 'Fulfilled')->whereYear('updated_at', today()->year)->whereMonth('updated_at', today()->month)->count();

        // Fetch Recent Claims using the filtered query
        $this->recentClaims = $claimsQuery->clone()->where('status', 'Fulfilled')
            ->with('collectionPoint')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Fetch Top Prizes using the filtered query
        $this->topPrizes = $claimsQuery->clone()->select('prize_won', DB::raw('COUNT(*) as count'))
            ->groupBy('prize_won')
            ->orderByDesc('count')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.agent.dashboard');
    }
}
