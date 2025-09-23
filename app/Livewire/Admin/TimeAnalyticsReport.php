<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.app')]
#[Title('Time-Based Analytics Report')]
class TimeAnalyticsReport extends Component
{
    // --- NEW: Properties for Campaign Filtering ---
    public $campaigns = [];
    public string $selectedCampaignId = '';

    public $claimsByDayChart = null;
    public $claimsByHourChart = null;

    public $datasets1;
    public $datasets2;

    public function mount()
    {
        // Fetch all campaigns for the filter dropdown
        $this->campaigns = Campaign::orderBy('name')->get();
        // Generate the initial report for ALL campaigns
        $this->generateReport();
    }

    // This method runs whenever the dropdown selection changes
    public function updatedSelectedCampaignId()
    {
        $this->generateReport();
    }

    // This method contains all the logic and is now filter-aware
    public function generateReport()
    {
        // --- Data for Claims by Day of Week Chart ---
        $claimsByDayQuery = Claim::select(
            DB::raw("TO_CHAR(created_at, 'Day') as day_of_week"),
            DB::raw("EXTRACT(DOW FROM created_at) as day_number"),
            DB::raw('COUNT(*) as count')
        );

        // THE FIX: Apply the campaign filter if one is selected
        if (!empty($this->selectedCampaignId)) {
            $claimsByDayQuery->where('campaign_id', $this->selectedCampaignId);
        }

        $claimsByDay = $claimsByDayQuery->groupBy('day_of_week', 'day_number')
            ->orderBy('day_number')
            ->get()
            ->pluck('count', 'day_of_week');

        if ($claimsByDay->isNotEmpty()) {

            $data1 = $claimsByDay->values()->all();
            $labels1 = $claimsByDay->keys()->all();

            $this->datasets1 = [
                'datasets' => [
                    [
                        "label" => "Claims by Day of the Week",
                        "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                        "borderColor" => "rgba(38, 185, 154, 0.7)",
                        "data" => $data1
                    ]
                ],
                'labels' => $labels1
            ];
        }

        // --- Data for Claims by Hour of Day Chart ---
        $claimsByHourQuery = Claim::select(
            DB::raw("EXTRACT(HOUR FROM created_at) as hour_of_day"),
            DB::raw('COUNT(*) as count')
        );

        // THE FIX: Apply the campaign filter if one is selected
        if (!empty($this->selectedCampaignId)) {
            $claimsByHourQuery->where('campaign_id', $this->selectedCampaignId);
        }

        $claimsByHour = $claimsByHourQuery->groupBy('hour_of_day')
            ->orderBy('hour_of_day')
            ->get()
            ->pluck('count', 'hour_of_day');

        if ($claimsByHour->isNotEmpty()) {
            $hours = array_fill(0, 24, 0);
            foreach ($claimsByHour as $hour => $count) {
                $hours[(int)$hour] = $count;
            }

            // $chartHour = new \Chart(array_values($hours), array_map(fn($h) => sprintf('%02d:00', $h), array_keys($hours)));
            // $chartHour->setType('line')->setTitle('Claims by Hour of the Day (IST)')->setResponsive(true);
            // $this->claimsByHourChart = $chartHour;

            $data2 = array_values($hours);
            $labels2 =  array_keys($hours);

            $this->datasets2 = [
                'datasets' => [
                    [
                        "label" => "Claims by Hour of the Day (IST)",
                        "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                        "borderColor" => "rgba(38, 185, 154, 0.7)",
                        "data" => $data2
                    ]
                ],
                'labels' => $labels2
            ];
        }
    }

    #[Computed]
    public function chart1()
    {
        return Chartjs::build()
            ->name("ClaimsByDayChart")
            ->livewire()
            ->model("datasets1")
            ->type("bar")
            ->labels($this->datasets1['labels'])
            ->datasets($this->datasets1['datasets']);
    }

    #[Computed]
    public function chart2()
    {
        return Chartjs::build()
            ->name("ClaimsByHourChart")
            ->livewire()
            ->model("datasets2")
            ->type("bar")
            ->labels($this->datasets2['labels'])
            ->datasets($this->datasets2['datasets']);
    }

    public function render()
    {
        return view('livewire.admin.time-analytics-report');
    }
}
