<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Live Winners Map')]
class WinnersMap extends Component
{
    // This property will hold the specific campaign we are displaying.
    public ?Campaign $currentCampaign = null;

    public array $claims = [];

    public function mount()
    {
        // --- THE FIX: Find the most recent, currently active campaign ---
        $this->currentCampaign = Campaign::where('is_active', true)->first();

        // If an active campaign is found, fetch its winner data.
        if ($this->currentCampaign) {
            $this->claims = Claim::where('campaign_id', $this->currentCampaign->id)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->select('name', 'city', 'prize_won', 'latitude', 'longitude')
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.admin.winners-map');
    }
}
