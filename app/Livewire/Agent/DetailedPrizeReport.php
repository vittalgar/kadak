<?php

namespace App\Livewire\Agent;

use App\Models\Campaign;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Detailed Prize Report')]
class DetailedPrizeReport extends Component
{
    use WithPagination;
    // --- Simplified Filter Properties ---
    public string $filterCampaignId = '';
    public string $filterStatus = '';
    public $campaigns = [];

    public function mount()
    {
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get();
    }
    
    public function updating($key)
    {
        if (in_array($key, ['filterCampaignId', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $agent = Auth::user()->agent;
        $prizeSummary = [];

        if ($agent) {
            $query = Claim::where('agent_id', $agent->id)
                ->selectRaw('prize_won, status, COUNT(*) as count')
                ->groupBy('prize_won', 'status');

            if ($this->filterCampaignId) {
                $query->where('campaign_id', $this->filterCampaignId);
            }
            if ($this->filterStatus) {
                $query->where('status', $this->filterStatus);
            }

            $prizeSummary = $query->orderBy('prize_won')->paginate(10);;
        }

        return view('livewire.agent.detailed-prize-report', [
            'prizeSummary' => $prizeSummary,
        ]);
    }
}
