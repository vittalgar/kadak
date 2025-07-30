<?php

namespace App\Livewire\Admin;

use App\Models\Claim;
use App\Models\QrToken;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.admin')]
#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    // Property to hold the current filter period
    public string $filterPeriod = 'all_time';

    // Method to change the filter period when a button is clicked
    public function setFilter(string $period)
    {
        $this->filterPeriod = $period;
    }

    // This is a "computed property". Its value is automatically recalculated
    // whenever the properties it depends on (like $filterPeriod) change.
    public function getFilteredDataProperty(): array
    {
        $query = Claim::query();
        $tokenQuery = QrToken::query();

        $now = Carbon::now('Asia/Kolkata');

        switch ($this->filterPeriod) {
            case 'today':
                $query->whereDate('created_at', $now->today());
                $tokenQuery->whereDate('created_at', $now->today());
                break;
            case '7_days':
                $query->where('created_at', '>=', $now->subDays(7)->startOfDay());
                $tokenQuery->where('created_at', '>=', $now->subDays(7)->startOfDay());
                break;
            case '30_days':
                $query->where('created_at', '>=', $now->subDays(30)->startOfDay());
                $tokenQuery->where('created_at', '>=', $now->subDays(30)->startOfDay());
                break;
            case 'all_time':
            default:
                // No date constraints needed for all time
                break;
        }

        return [
            'totalClaims' => $query->count(),
            'uniqueParticipants' => (clone $query)->distinct('mobile')->count('mobile'),
            'totalTokensGenerated' => $tokenQuery->count(),
            'recentClaims' => $query->with('product')->latest()->take(5)->get(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'data' => $this->filteredData,
        ]);
    }
}
