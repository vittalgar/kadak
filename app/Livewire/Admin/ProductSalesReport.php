<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Claim;
use App\Models\State;
use App\Models\City;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Product Sales Report')]
class ProductSalesReport extends Component
{
    use WithPagination;

    // --- Filter Properties ---
    public string $filterCampaignId = '';
    public ?int $filterStateId = null;
    public ?int $filterCityId = null;
    public ?int $filterAgentId = null; // <-- Changed from Dealer
    public ?string $startDate = null;
    public ?string $endDate = null;

    // --- Dropdown Options ---
    public $campaigns = [];
    public $states = [];
    public $cities = [];
    public $agents = []; // <-- Changed from Dealers

    public $salesChart = null;

    public function mount()
    {
        $this->campaigns = Campaign::orderBy('name')->get();
        $this->states = State::orderBy('name')->get();
        $this->cities = collect();
        $this->agents = collect();
    }

    // --- Dependent Dropdown Logic ---
    public function updatedFilterStateId($stateId)
    {
        $this->cities = $stateId ? City::where('state_id', $stateId)->orderBy('name')->get() : collect();
        $this->reset(['filterCityId', 'filterAgentId']);
        $this->agents = collect();
    }

    public function updatedFilterCityId($cityId)
    {
        if ($cityId) {
            $city = City::find($cityId);
            $this->agents = Agent::where('city', $city->name)->orderBy('shop_name')->get();
        } else {
            $this->agents = collect();
        }
        $this->reset('filterAgentId');
    }

    private function getFilteredQuery()
    {
        $query = Claim::query();

        // THE FIX: We explicitly specify `claims.column_name` for all filters
        // to prevent any ambiguity with joined tables.

        if ($this->filterCampaignId) {
            $query->where('claims.campaign_id', $this->filterCampaignId);
        }

        if ($this->filterAgentId) {
            $query->where('claims.agent_id', $this->filterAgentId);
        } elseif ($this->filterCityId) {
            $city = City::find($this->filterCityId);
            if ($city) $query->where('claims.city', $city->name);
        } elseif ($this->filterStateId) {
            $state = State::find($this->filterStateId);
            if ($state) $query->where('claims.state', $state->name);
        }

        if ($this->startDate) {
            $query->whereDate('claims.created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('claims.created_at', '<=', $this->endDate);
        }

        return $query;
    }

    public function render()
    {
        $query = $this->getFilteredQuery()
            ->leftJoin('products', 'claims.product_selected_id', '=', 'products.id')
            ->select(
                DB::raw('COALESCE(products.name, \'N/A - Better Luck\') as product_name'),
                DB::raw('COALESCE(products.sku, \'N/A\') as product_sku'),
                DB::raw('COUNT(claims.id) as sales_count')
            )
            ->groupBy('product_name', 'product_sku')
            ->orderBy('sales_count', 'desc');

        $productSales = $query->paginate(20);

        return view('livewire.admin.product-sales-report', [
            'productSales' => $productSales,
        ]);
    }
}
