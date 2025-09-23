<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Attach Products')]
class AttachProducts extends Component
{
    public Campaign $campaign;
    public $products;
    public $attachedProductIds = [];

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->products = Product::where('is_active', true)->orderBy('name')->get();
        // Load the IDs of products already attached to this campaign
        $this->attachedProductIds = $this->campaign->products()->pluck('product_id')->toArray();
    }

    public function toggleProduct($productId)
    {
        // The sync() method is a convenient way to manage many-to-many relationships.
        // It will add or remove the product from the campaign.
        $this->campaign->products()->toggle($productId);

        // Refresh the list of attached product IDs
        $this->attachedProductIds = $this->campaign->products()->pluck('product_id')->toArray();
        $this->dispatch('notify', message: 'Product list updated successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.attach-products');
    }
}
