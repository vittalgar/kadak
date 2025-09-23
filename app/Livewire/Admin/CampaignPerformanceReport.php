<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use Livewire\Component;
use Livewire\Attributes\Computed;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Campaign Performance Report')]
class CampaignPerformanceReport extends Component
{
    public $campaigns = [];
    public ?int $selectedCampaignId = null;
    public ?string $startDate = null;
    public ?string $endDate = null;
    public $datasets;

    public function mount()
    {
        // Fetch all campaigns for the filter dropdown
        $this->campaigns = Campaign::orderBy('name')->get();
        // Default to the most recent campaign
        if (!empty($this->campaigns)) {
            $this->selectedCampaignId = $this->campaigns[0]['id'] ?? null;
        }
    }

    public function render()
    {
        $reportData = [
            'totalClaims' => 0,
            'uniqueParticipants' => 0,
            'claimsOverTime' => [],
            'topPrizes' => [],
            'topLocations' => [],
        ];

        // Only generate the report if a campaign is selected
        if ($this->selectedCampaignId) {
            $query = Claim::where('campaign_id', $this->selectedCampaignId);

            // Apply date filters if they are set
            if ($this->startDate) {
                $query->whereDate('created_at', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('created_at', '<=', $this->endDate);
            }

            // --- GATHER REPORT DATA ---
            $claims = $query->get();
            $reportData['totalClaims'] = $claims->count();
            $reportData['uniqueParticipants'] = $claims->unique('mobile')->count();

            // Data for "Claims Over Time" Chart
            // 1. Perform the grouping and counting as before, but keep it as a Collection.
            $claimsOverTime = $claims->groupBy(function ($claim) {
                return $claim->created_at->format('Y-m-d');
            })
                ->map(fn($group) => $group->count())
                ->sortKeys();

            // 2. Use the `keys()` and `values()` methods to separate the data.
            $reportData['claimsOverTimeLabels'] = $claimsOverTime->keys()->toArray();
            $reportData['claimsOverTimeData'] = $claimsOverTime->values()->toArray();

            $this->datasets = [
                'datasets' => [
                    [
                        "label" => "Claims Over Time",
                        "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                        "borderColor" => "rgba(38, 185, 154, 0.7)",
                        "data" => $reportData['claimsOverTimeData']
                    ]
                ],
                'labels' => $reportData['claimsOverTimeLabels']
            ];

            // Data for "Top Prizes" Table
            $reportData['topPrizes'] = $claims->groupBy('prize_won')
                ->map(fn($group) => $group->count())
                ->sortDesc()
                ->take(5)
                ->toArray();

            // Data for "Top Locations" Table
            $reportData['topLocations'] = $claims->groupBy('city')
                ->map(fn($group) => $group->count())
                ->sortDesc()
                ->take(5)
                ->toArray();
        }

        return view('livewire.admin.campaign-performance-report', [
            'reportData' => $reportData,
        ]);
    }

    #[Computed]
    public function chart()
    {
        return Chartjs::build()
            ->name("ClaimsOverTimeChart")
            ->livewire()
            ->model("datasets")
            ->type("bar")
            ->labels($this->datasets['labels'])
            ->datasets($this->datasets['datasets']);
    }
}
