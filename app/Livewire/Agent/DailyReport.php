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
#[Title('Daily Claims Report')]
class DailyReport extends Component
{
    use WithPagination;
    // The date for which to show the report. Defaults to today.
    public string $reportDate;

    public $campaigns = [];
    public string $selectedCampaignId = '';

    public function mount()
    {
        $this->reportDate = now()->format('Y-m-d');
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get();
    }

    // --- THE FIX IS HERE ---
    // 3. This method automatically resets the page when a filter changes.
    public function updating($key)
    {
        if (in_array($key, ['reportDate', 'selectedCampaignId'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $agent = Auth::user()->agent;

        // Start with a query builder instance
        $claimsQuery = Claim::query()->whereRaw('1 = 0'); // Default to an empty query

        if ($agent && !empty($this->reportDate)) {
            $claimsQuery = Claim::where('agent_id', $agent->id)
                ->whereDate('created_at', $this->reportDate);

            if (!empty($this->selectedCampaignId)) {
                $claimsQuery->where('campaign_id', $this->selectedCampaignId);
            }
        }

        return view('livewire.agent.daily-report', [
            // 4. Paginate the final query
            'claims' => $claimsQuery->with('campaign')->latest()->paginate(10),
        ]);
    }
}
