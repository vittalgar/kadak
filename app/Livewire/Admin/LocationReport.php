<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Location Report')]
class LocationReport extends Component
{
    public $campaigns = [];
    public string $selectedCampaignId = '';
    public $locationData = [];

    public function mount()
    {
        $this->campaigns = Campaign::orderBy('name')->get();
        $this->generateReport(); // Generate initial report
    }

    public function updatedSelectedCampaignId()
    {
        $this->generateReport();
    }

    public function generateReport()
    {
        $query = Claim::select('state', 'city', DB::raw('COUNT(*) as total_claims'))
            ->whereNotNull('state')
            ->whereNotNull('city');

        if (!empty($this->selectedCampaignId)) {
            $query->where('campaign_id', $this->selectedCampaignId);
        }

        $this->locationData = $query->groupBy('state', 'city')
            ->orderBy('state', 'asc')
            ->orderBy('total_claims', 'desc')
            ->get()
            ->groupBy('state')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.location-report');
    }
}
