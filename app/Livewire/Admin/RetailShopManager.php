<?php

namespace App\Livewire\Admin;

use App\Imports\RetailShopsImport;
use App\Livewire\Forms\RetailShopForm;
use App\Models\Dealer;
use App\Models\RetailShop;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.admin')]
#[Title('Retail Shop Manager')]
class RetailShopManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public Dealer $dealer;
    public RetailShopForm $form;
    public bool $isEditing = false;
    public $upload;

    public function mount(Dealer $dealer)
    {
        $this->dealer = $dealer;
    }

    public function edit(int $shopId)
    {
        $shop = RetailShop::findOrFail($shopId);
        $this->form->setShop($shop);
        $this->isEditing = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            session()->flash('success', 'Shop updated successfully.');
        } else {
            $this->form->store($this->dealer->id);
            session()->flash('success', 'Shop added successfully.');
        }
        $this->isEditing = false;
    }

    public function delete(int $shopId)
    {
        $shop = RetailShop::findOrFail($shopId);
        $shop->delete();
        session()->flash('success', 'Shop deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->form->reset();
        $this->isEditing = false;
    }

    public function importShops()
    {
        $this->validate([
            'upload' => 'required|mimes:csv,xls,xlsx|max:10240'
        ]);

        try {
            Excel::import(new RetailShopsImport($this->dealer->id), $this->upload);
            session()->flash('success', 'Retail shops imported successfully.');
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
        $shops = RetailShop::where('dealer_id', $this->dealer->id)
            ->latest()
            ->paginate(10);

        return view('livewire.admin.retail-shop-manager', [
            'shops' => $shops,
        ]);
    }
}
