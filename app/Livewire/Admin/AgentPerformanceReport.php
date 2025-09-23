<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Agent;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Agent Performance Report')]
class AgentPerformanceReport extends Component
{
    use WithPagination;

    public $campaigns = [];
    public string $selectedCampaignId = '';

    public function mount()
    {
        $this->campaigns = Campaign::orderBy('name')->get();
    }

    public function updatingSelectedCampaignId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $agentsQuery = Agent::query();

        $agentsQuery->withCount(['claims' => function ($query) {
            if (!empty($this->selectedCampaignId)) {
                $query->where('campaign_id', $this->selectedCampaignId);
            }
        }]);

        $agents = $agentsQuery->orderBy('claims_count', 'desc')
            ->paginate(20);

        return view('livewire.admin.agent-performance-report', [
            'agents' => $agents,
        ]);
    }
}
