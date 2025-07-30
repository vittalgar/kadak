<?php

namespace App\Livewire\Admin;

use App\Imports\PrizesImport;
use App\Livewire\Forms\PrizeForm;
use App\Models\Campaign;
use App\Models\Prize;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.admin')]
#[Title('Prize Manager')]
class PrizeManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public Campaign $campaign;
    public PrizeForm $form;
    public bool $isEditing = false;
    public $upload;

    // --- NEW: Properties for Search and Filtering ---
    public string $search = '';
    public string $filterCategory = '';

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function edit(Prize $prize)
    {
        $this->form->setPrize($prize);
        $this->isEditing = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            session()->flash('success', 'Prize updated successfully.');
        } else {
            $this->form->store($this->campaign->id);
            session()->flash('success', 'Prize created successfully.');
        }
        $this->isEditing = false;
        $this->form->reset();
    }

    public function delete(Prize $prize)
    {
        $prize->delete();
        session()->flash('success', 'Prize deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->form->reset();
        $this->isEditing = false;
    }

    public function importPrizes()
    {
        $this->validate([
            'upload' => 'required|mimes:csv,xls,xlsx|max:10240' // 10MB Max
        ]);

        try {
            Excel::import(new PrizesImport($this->campaign->id), $this->upload);
            session()->flash('success', 'Prizes imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            session()->flash('import_errors', $errorMessages);
        }

        $this->reset('upload');
    }

    // --- NEW: Livewire lifecycle hooks to reset pagination on search/filter ---
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $prizesQuery = Prize::where('campaign_id', $this->campaign->id);

        // Apply search query
        if (!empty($this->search)) {
            $prizesQuery->where('name', 'like', '%' . $this->search . '%');
        }

        // Apply category filter
        if (!empty($this->filterCategory)) {
            $prizesQuery->where('category', $this->filterCategory);
        }

        $prizes = $prizesQuery->latest()->paginate(10);

        return view('livewire.admin.prize-manager', [
            'prizes' => $prizes,
        ]);
    }
}
