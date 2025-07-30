<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-white">Attach Products</h1>
            <p class="mt-1 text-sm text-gray-400">Select which products are part of the "{{ $campaign->name }}" campaign.
            </p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="text-sm font-medium text-indigo-400 hover:text-indigo-300">
            &larr; Back to Campaign Manager
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/30 text-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-[#2a3042] shadow-sm sm:rounded-lg p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div wire:click="toggleProduct({{ $product->id }})"
                    class="relative flex items-center space-x-3 rounded-lg border {{ in_array($product->id, $attachedProductIds) ? 'border-green-500 bg-green-500/10' : 'border-gray-700 bg-gray-900/50' }} px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-500 cursor-pointer">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white">{{ $product->name }}</p>
                        <p class="text-sm text-gray-400 truncate">{{ $product->sku ?? 'No SKU' }}</p>
                    </div>
                    @if (in_array($product->id, $attachedProductIds))
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
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
