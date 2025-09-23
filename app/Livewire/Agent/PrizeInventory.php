<?php

namespace App\Livewire\Agent;

use App\Models\Prize;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Prize Inventory Report')]
class PrizeInventory extends Component
{
    use WithPagination;

    public function render()
    {
        $now = now();
        if ($now->month < 10 && $now->year <= 2025) {
            $relevantMonth = 10;
        } elseif ($now->month > 12 || $now->year > 2025) {
            $relevantMonth = 12;
        } else {
            $relevantMonth = $now->month;
        }
        $currentMonthStockColumn = 'stock_' . strtolower(Carbon::create()->month($relevantMonth)->format('M'));

        $prizes = Prize::with('campaign')
            ->whereHas('campaign', fn($q) => $q->where('is_active', true))
            ->select('*', DB::raw('stock_oct + stock_nov + stock_dec as total_stock'))
            ->orderBy($currentMonthStockColumn, 'asc')
            ->paginate(10);

        return view('livewire.agent.prize-inventory', [
            'prizes' => $prizes,
            'currentMonthStockColumn' => $currentMonthStockColumn,
        ]);
    }
}
