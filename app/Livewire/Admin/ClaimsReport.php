<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterClaimsExport;

#[Layout('components.layouts.app')]
#[Title('Claims Report')]
class ClaimsReport extends Component
{
    use WithPagination;

    public string $search = '';
    public $campaigns = [];
    public string $selectedCampaignId = '';

    // --- Properties for Product Filtering ---
    public $products = [];
    public string $selectedProductId = '';

    public function mount()
    {
        $this->campaigns = Campaign::orderBy('name')->get();
        // Fetch all products for the new filter dropdown
        $this->products = Product::orderBy('name')->get();
    }

    // This hook resets pagination when any filter is changed
    public function updating($key)
    {
        if (in_array($key, ['search', 'selectedCampaignId', 'selectedProductId'])) {
            $this->resetPage();
        }
    }

    public function exportAll()
    {
        return Excel::download(new MasterClaimsExport, 'master_claims_report.xlsx');
    }

    public function render()
    {
        $claimsQuery = Claim::with(['product', 'collectionPoint', 'campaign'])
            // Filter by Campaign
            ->when($this->selectedCampaignId, function ($query) {
                $query->where('campaign_id', $this->selectedCampaignId);
            })
            // --- NEW: Apply the product filter if one is selected ---
            ->when($this->selectedProductId, function ($query) {
                $query->where('product_selected_id', $this->selectedProductId);
            })
            // Search functionality
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%')
                        ->orWhere('claim_id', 'like', '%' . $this->search . '%')
                        ->orWhere('prize_won', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%');
                });
            });

        $claims = $claimsQuery->latest()->paginate(20);

        return view('livewire.admin.claims-report', [
            'claims' => $claims,
        ]);
    }
}
