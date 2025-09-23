<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Prize;
use Illuminate\Support\Facades\DB; // <-- Import the DB facade
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Prize Inventory Report')]
class PrizeInventoryReport extends Component
{
    use WithPagination;

    public $campaigns = [];
    public string $selectedCampaignId = '';

    public function mount()
    {
        // Fetch campaigns as a simple array for the filter
        $this->campaigns = Campaign::orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function updatingSelectedCampaignId()
    {
        $this->resetPage();
    }

    public function render()
    {
        // 1. Determine the relevant month for the report.
        $now = now();
        if ($now->month < 10 && $now->year <= 2025) {
            // If we are before October, show stats for the start of the campaign.
            $relevantMonth = 10;
        } elseif ($now->month > 12 || $now->year > 2025) {
            // If we are after December, show stats for the end of the campaign.
            $relevantMonth = 12;
        } else {
            // Otherwise, use the current month.
            $relevantMonth = $now->month;
        }

        // 2. Create the correct stock column name based on the relevant month.
        $currentMonthStockColumn = 'stock_' . strtolower(Carbon::create()->month($relevantMonth)->format('M'));

        $prizesQuery = Prize::with('campaign')
            // Add a computed column for the total stock
            ->select('*', DB::raw('stock_oct + stock_nov + stock_dec as total_stock'))
            ->when($this->selectedCampaignId, function ($query) {
                $query->where('campaign_id', $this->selectedCampaignId);
            });

        // 3. Order by the stock of the relevant month.
        $prizes = $prizesQuery->orderBy('category', 'asc')->orderBy('total_stock', 'asc')->paginate(10);

        return view('livewire.admin.prize-inventory-report', [
            'prizes' => $prizes,
            'currentMonthStockColumn' => $currentMonthStockColumn,
        ]);
    }
}
