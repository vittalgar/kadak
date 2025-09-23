<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Prize;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
#[Title('Prize Manager')]
class PrizeManager extends Component
{
    use WithPagination;

    public Campaign $campaign;
    public bool $showModal = false;
    public ?Prize $currentPrize = null;
    public bool $isEditing = false;

    // --- Form Fields Updated for New Logic ---
    #[Rule('required|string|min:3|max:255')]
    public string $name = '';
    #[Rule('required|boolean')]
    public bool $show = true; // true for "On Wheel", false for "Hidden"
    #[Rule('required|string|max:255')]
    public string $category = 'Low-Level';
    #[Rule('required|integer|min:0')]
    public int $stock_oct = 0;
    #[Rule('required|integer|min:0')]
    public int $stock_nov = 0;
    #[Rule('required|integer|min:0')]
    public int $stock_dec = 0;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetValidation();
        $this->resetExcept('campaign');
        $this->show = true;
        $this->showModal = true;
    }

    public function edit(Prize $prize)
    {
        $this->isEditing = true;
        $this->resetValidation();
        $this->currentPrize = $prize;
        $this->name = $prize->name;
        $this->show = $prize->show;
        $this->category = $prize->category;
        $this->stock_oct = $prize->stock_oct;
        $this->stock_nov = $prize->stock_nov;
        $this->stock_dec = $prize->stock_dec;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate();

        // --- NEW: Automatic Weight Calculation ---
        $totalStock = $this->stock_oct + $this->stock_nov + $this->stock_dec;
        $validated['total_stock'] = $totalStock;

        // Weight is the total stock if shown on wheel, otherwise 0
        $validated['weight'] = $this->show ? $totalStock : 0;

        // Prevent a weight of 0 for an item shown on the wheel
        if ($this->show && $validated['weight'] < 1) {
            $validated['weight'] = 1;
        }

        if ($this->isEditing) {
            $this->currentPrize->update($validated);
            session()->flash('success', 'Prize updated successfully.');
        } else {
            $this->campaign->prizes()->create($validated);
            session()->flash('success', 'Prize created successfully.');
        }
        $this->closeModal();
    }

    public function delete(Prize $prize)
    {
        $prize->delete();
        $this->dispatch('notify', message: 'Prize deleted successfully.', type: 'error');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        $prizes = $this->campaign->prizes()->latest()->paginate(10);
        return view('livewire.admin.prize-manager', [
            'prizes' => $prizes,
        ]);
    }
}
