<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\QrToken;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('QR Code Engagement Report')]
class EngagementReport extends Component
{
    public $campaigns = [];
    public string $selectedCampaignId = '';

    // Report metrics
    public int $totalGenerated = 0;
    public int $totalScanned = 0; // (Status is SPINNING or CLAIMED)
    public int $totalClaimed = 0;
    public float $scanRate = 0.0;
    public float $claimRate = 0.0;

    public function mount()
    {
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get();
        // Default to the first campaign if one exists
        if (!empty($this->campaigns)) {
            $this->selectedCampaignId = $this->campaigns[0]['id'];
            $this->generateReport(); // Generate report for the default campaign
        }
    }

    // This runs when the user selects a new campaign from the dropdown
    public function updatedSelectedCampaignId()
    {
        $this->generateReport();
    }

    public function generateReport()
    {
        if (empty($this->selectedCampaignId)) {
            $this->reset('totalGenerated', 'totalScanned', 'totalClaimed', 'scanRate', 'claimRate');
            return;
        }

        // 1. Total QR Codes Generated for this campaign
        $this->totalGenerated = QrToken::whereHas('batch', function ($query) {
            $query->where('campaign_id', $this->selectedCampaignId);
        })->count();

        // 2. Total Scanned (any token that is no longer 'NEW')
        $this->totalScanned = QrToken::whereHas('batch', function ($query) {
            $query->where('campaign_id', $this->selectedCampaignId);
        })->where('status', '!=', 'NEW')->count();

        // 3. Total Successfully Claimed
        $this->totalClaimed = QrToken::whereHas('batch', function ($query) {
            $query->where('campaign_id', $this->selectedCampaignId);
        })->where('status', 'CLAIMED')->count();

        // 4. Calculate Conversion Rates
        $this->scanRate = ($this->totalGenerated > 0) ? ($this->totalScanned / $this->totalGenerated) * 100 : 0;
        $this->claimRate = ($this->totalScanned > 0) ? ($this->totalClaimed / $this->totalScanned) * 100 : 0;
    }

    public function render()
    {
        return view('livewire.admin.engagement-report');
    }
}
