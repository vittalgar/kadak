<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use App\Models\QrBatch;
use App\Models\QrToken;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    // --- Filter Properties ---
    public string $selectedCampaignId = '';
    public ?int $selectedStateId = null;
    public ?int $selectedCityId = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    // --- Dropdown Options ---
    public $campaigns = [];
    public $states = [];
    public $cities = [];

    // --- Report Metrics ---
    public int $totalClaims = 0;
    public int $uniqueParticipants = 0;
    public int $qrCodesGenerated = 0;
    public int $betterLuckCount = 0;
    public int $activeCampaigns = 0;
    public int $totalScanned = 0;
    public float $scanRate = 0.0;
    public float $claimRate = 0.0;
    public $recentClaims;
    public $datasets;

    public function mount()
    {
        // Fetch all campaigns for the filter dropdown
        $this->campaigns = Campaign::orderBy('name')->get();
        $this->activeCampaigns = Campaign::where('is_active', true)->whereDate('end_date', '>=', now())->count();
        $this->states = State::orderBy('name')->get();
        $this->cities = collect();
        // Generate the initial report for ALL campaigns
        $this->generateReport();
    }

    // This method runs whenever the dropdown selection changes
    public function updatedSelectedCampaignId()
    {
        $this->generateReport();
    }
    public function updatedSelectedStateId($stateId)
    {
        if ($stateId) {
            $this->cities = City::where('state_id', $stateId)->orderBy('name')->get();
        } else {
            $this->cities = collect();
        }
        $this->reset('selectedCityId');
        $this->generateReport();
    }
    public function updatedSelectedCityId()
    {
        $this->generateReport();
    }
    public function updatedStartDate()
    {
        $this->generateReport();
    }
    public function updatedEndDate()
    {
        $this->generateReport();
    }

    // A dedicated method to calculate the report data
    public function generateReport()
    {
        // Base queries for Claims and QR Tokens
        $claimsQuery = Claim::query();
        $qrQuery = QrToken::query();

        // --- Apply Filters ---
        if (!empty($this->selectedCampaignId)) {
            $claimsQuery->where('campaign_id', $this->selectedCampaignId);
            $qrQuery->whereHas('batch', fn($q) => $q->where('campaign_id', $this->selectedCampaignId));
        }
        if (!empty($this->selectedCityId)) {
            $city = City::find($this->selectedCityId);
            if ($city) $claimsQuery->where('city', $city->name);
        } elseif (!empty($this->selectedStateId)) {
            $state = State::find($this->selectedStateId);
            if ($state) $claimsQuery->where('state', $state->name);
        }
        if ($this->startDate) {
            $claimsQuery->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $claimsQuery->whereDate('created_at', '<=', $this->endDate);
        }

        // --- Calculate Metrics ---
        $this->totalClaims = $claimsQuery->clone()->count();
        $this->uniqueParticipants = $claimsQuery->clone()->distinct('mobile')->count('mobile');

        // QR metrics also need to be filtered
        $this->qrCodesGenerated = QrBatch::when(!empty($this->selectedCampaignId), fn($q) => $q->where('campaign_id', $this->selectedCampaignId))->sum('quantity');
        $this->totalScanned = $qrQuery->clone()->where('status', '!=', 'NEW')->count();
        $this->claimRate = ($this->totalScanned > 0) ? ($this->totalClaims / $this->totalScanned) * 100 : 0;
        $this->scanRate = ($this->qrCodesGenerated > 0) ? ($this->totalScanned / $this->qrCodesGenerated) * 100 : 0;
        $this->betterLuckCount = $claimsQuery->clone()->where('prize_won', 'Better Luck Next Time')->count();
        // Recent Claims
        $this->recentClaims = $claimsQuery->clone()->with(['campaign', 'collectionPoint'])->latest()->take(5)->get();

        // Prize Distribution Chart
        $prizeDistribution = $claimsQuery->clone()->select('prize_won', DB::raw('COUNT(*) as count'))
            ->groupBy('prize_won')->orderByDesc('count')->take(10)->pluck('count', 'prize_won');


        $data = $prizeDistribution->pluck("count")->toArray();
        $labels = $prizeDistribution->pluck("prize_won")->toArray();

        $this->datasets = [
            'datasets' => [
                [
                    "label" => "Prize Claimed Count",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data
                ]
            ],
            'labels' => $labels
        ];
    }

    #[Computed]
    public function chart()
    {
        return Chartjs::build()
            ->name("PrizeWonChart")
            ->livewire()
            ->model("datasets")
            ->type("bar")
            ->labels($this->datasets['labels'])
            ->datasets($this->datasets['datasets']);
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
