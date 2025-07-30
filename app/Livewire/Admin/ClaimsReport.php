<?php

namespace App\Livewire\Admin;

use App\Models\Claim;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Claims Report')]
class ClaimsReport extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        // This resets the pagination back to the first page
        // whenever the user types in the search box.
        $this->resetPage();
    }

    public function render()
    {
        $claims = Claim::with('product') // Eager load the product relationship
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('claim_id', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                    ->orWhere('prize_won', 'like', '%' . $this->search . '%');
            })
            ->latest() // Order by the newest claims first
            ->paginate(15); // Show 15 claims per page

        return view('livewire.admin.claims-report', [
            'claims' => $claims
        ]);
    }
}
