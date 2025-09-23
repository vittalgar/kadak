<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Product Manager')]
class ProductManager extends Component
{
    use WithPagination;

    public ProductForm $form;
    public bool $showModal = false;
    public bool $isEditing = false;

    // THE FIX: We keep track of the product being edited here.
    public ?Product $editingProduct = null;

    public function create()
    {
        $this->isEditing = false;
        $this->form->reset();
        $this->editingProduct = null; // Ensure we're not holding an old product
        $this->showModal = true;
    }

    public function edit(Product $product)
    {
        $this->form->resetValidation();
        $this->isEditing = true;
        $this->editingProduct = $product;
        $this->form->setProduct($product);
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            // THE FIX: Pass the product directly to the update method.
            $this->form->update($this->editingProduct);
            $this->dispatch('notify', message: 'Product updated successfully.', type: 'success');
        } else {
            $this->form->store();
            $this->dispatch('notify', message: 'Product created successfully.', type: 'success');
        }
        $this->closeModal();
    }

    public function delete(Product $product)
    {
        $product->delete();
        $this->dispatch('notify', message: 'Product deleted successfully.', type: 'success');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingProduct = null;
        $this->form->reset();
    }

    public function render()
    {
        $products = Product::latest()->paginate(10);
        return view('livewire.admin.product-manager', [
            'products' => $products
        ]);
    }
}
