<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.admin')]
#[Title('Product Manager')]
class ProductManager extends Component
{
    use WithPagination;

    public ProductForm $form;
    public bool $isEditing = false;

    public function edit(int $productId)
    {
        $product = Product::findOrFail($productId);
        $this->form->setProduct($product);
        $this->isEditing = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            session()->flash('success', 'Product updated successfully.');
        } else {
            $this->form->store();
            session()->flash('success', 'Product created successfully.');
        }
        $this->isEditing = false;
        $this->form->reset();
    }

    public function delete(int $productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();
        session()->flash('success', 'Product deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->form->reset();
        $this->isEditing = false;
    }

    public function render()
    {
        $products = Product::latest()->paginate(10);
        return view('livewire.admin.product-manager', [
            'products' => $products
        ]);
    }
}
