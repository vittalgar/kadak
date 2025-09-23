<?php

namespace App\Livewire\Agent;

use App\Models\Campaign;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Exports\ClaimsExport;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Redemption List')]
class RedemptionList extends Component
{
    use WithPagination;

    // --- Filter Properties ---
    public string $filterStatus = '';
    public string $filterCampaignId = '';
    public array $campaigns = [];
    public string $search = '';
    // --- Bulk Action Properties ---
    public array $selectedClaims = [];
    public bool $selectAll = false;

    public function mount()
    {
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function updating($key)
    {
        if (in_array($key, ['filterStatus', 'filterCampaignId', 'search'])) {
            $this->resetPage();
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedClaims = $this->getFilteredClaimsQuery()
                ->where('status', 'Processing')
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedClaims = [];
        }
    }

    public function updatedSelectedClaims()
    {
        $allProcessingCount = $this->getFilteredClaimsQuery()->where('status', 'Processing')->count();
        if ($allProcessingCount > 0) {
            $this->selectAll = count($this->selectedClaims) === $allProcessingCount;
        } else {
            $this->selectAll = false;
        }
    }

    public function markSelectedAsFulfilled()
    {
        if (empty($this->selectedClaims)) {
            return;
        }

        // THE FIX: The query is now much simpler and more secure.
        Claim::where('agent_id', Auth::user()->agent_id)
            ->whereIn('id', $this->selectedClaims)
            ->update(['status' => 'Fulfilled']);

        $this->dispatch('notify', message: count($this->selectedClaims) . ' claims marked as fulfilled.', type: 'success');
        $this->reset(['selectedClaims', 'selectAll']);
    }

    public function markAsFulfilled(int $claimId)
    {
        // THE FIX: The logic is simpler and correctly scoped to the agent.
        $claim = Claim::where('id', $claimId)
            ->where('agent_id', Auth::user()->agent_id)
            ->first();

        if (!$claim) {
            $this->dispatch('notify', message: 'Claim not found or you are not authorized to update it.', type: 'error');
            return;
        }

        $claim->status = 'Fulfilled';
        $claim->save();

        $this->dispatch('notify', message: "Claim #{$claim->claim_id} has been fulfilled.", type: 'success');
    }

    private function getFilteredClaimsQuery()
    {
        $agent = Auth::user()->agent;
        if (!$agent) {
            return Claim::whereRaw('1 = 0');
        }

        $claimsQuery = Claim::where('agent_id', $agent->id);

        if (!empty($this->filterCampaignId)) {
            $claimsQuery->where('campaign_id', $this->filterCampaignId);
        }
        if (!empty($this->filterStatus)) {
            $claimsQuery->where('status', $this->filterStatus);
        }

        if (!empty($this->search)) {
            $claimsQuery->where(function ($query) {
                $query->where('claim_id', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            });
        }

        return $claimsQuery;
    }
    
    public function export()
    {
        // This method is now correct, as it passes the filtered query
        // to our newly refactored ClaimsExport class.
        return Excel::download(new ClaimsExport($this->getFilteredClaimsQuery()), 'my-claims-export.xlsx');
    }

    public function render()
    {
        $claimsQuery = $this->getFilteredClaimsQuery();

        $prizeSummary = $claimsQuery->clone()
            ->selectRaw('prize_won, status, COUNT(*) as count')
            ->groupBy('prize_won', 'status')
            ->paginate(5, ['*'], 'prizePage');

        $claims = $claimsQuery->clone()
            ->with(['product', 'campaign'])
            ->latest()
            ->paginate(10, ['*'], 'claimsPage');

        return view('livewire.agent.redemption-list', [
            'claims' => $claims,
            'prizeSummary' => $prizeSummary,
        ]);
    }
}
