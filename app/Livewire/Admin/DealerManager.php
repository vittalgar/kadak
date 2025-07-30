<?php

namespace App\Livewire\Admin;

use App\Imports\DealersImport;
use App\Livewire\Forms\DealerForm;
use App\Models\Dealer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.admin')]
#[Title('Dealer Manager')]
class DealerManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public DealerForm $form;
    public bool $isEditing = false;
    public $upload;

    public function edit(Dealer $dealer)
    {
        // THE FIX: Eager load the user relationship to ensure it's available.
        $dealer->load('user');
        $this->form->setDealer($dealer);
        $this->isEditing = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            session()->flash('success', 'Dealer updated successfully.');
        } else {
            $this->form->store();
            session()->flash('success', 'Dealer created successfully.');
        }
        $this->isEditing = false;
    }

    public function delete(Dealer $dealer)
    {
        // THE FIX: The deletion logic is now handled by the boot() method in the Dealer model.
        $dealer->delete();
        session()->flash('success', 'Dealer and their user account have been deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->form->reset();
        $this->isEditing = false;
    }

    public function importDealers()
    {
        $this->validate(['upload' => 'required|mimes:csv,xls,xlsx|max:10240']);
        try {
            Excel::import(new DealersImport, $this->upload);
            session()->flash('success', 'Dealers imported successfully.');
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

    public function render()
    {
        // THE FIX: Eager loading `with('user')` ensures the email is always available for the list.
        $dealers = Dealer::with('user')->latest()->paginate(10);
        return view('livewire.admin.dealer-manager', [
            'dealers' => $dealers,
        ]);
    }
}
