<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Rule as LivewireRule;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product;

    #[LivewireRule('required|string|max:255')]
    public string $name = '';

    // THE FIX: Allow the SKU property to be nullable.
    #[LivewireRule('nullable|string|max:255')]
    public ?string $sku = '';

    #[LivewireRule('required|boolean')]
    public bool $is_active = true;

    public function rules()
    {
        return [
            // When editing, the unique SKU rule should ignore the current product.
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore(isset($this->product) ? $this->product->id : null)],
        ];
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sku = $product->sku; // This will now correctly handle null values.
        $this->is_active = $product->is_active;
    }

    public function store()
    {
        $this->validate();
        $data = $this->all();
        $data['sku'] = empty($data['sku']) ? null : $data['sku'];
        Product::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $data = $this->all();
        $data['sku'] = empty($data['sku']) ? null : $data['sku'];
        $this->product->update($data);
        $this->reset();
    }
}
