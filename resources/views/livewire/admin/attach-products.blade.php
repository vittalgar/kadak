<div class="space-y-8">
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Attach Products</h1>

    <div class="flex justify-between items-center">
        <div>
            <p class="mt-1 text-base text-fg-soft">Select which products are part of the "{{ $campaign->name }}"
                campaign.</p>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" wire:navigate
            class="text-sm font-medium text-primary hover:underline">&larr; Back to Campaign Manager</a>
    </div>

    <div class="bg-bkg-alt border border-border rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div wire:click="toggleProduct({{ $product->id }})"
                    class="relative flex items-center space-x-3 rounded-lg border px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 hover:border-gray-500 cursor-pointer
                        {{ in_array($product->id, $attachedProductIds) ? 'border-success bg-success/10' : 'border-border bg-bkg-soft' }}">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-fg-alt">{{ $product->name }}</p>
                        <p class="text-sm text-fg-soft truncate">{{ $product->sku ?? 'No SKU' }}</p>
                    </div>
                    @if (in_array($product->id, $attachedProductIds))
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
