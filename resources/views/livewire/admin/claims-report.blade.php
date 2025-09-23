<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-fg-alt">Claims Report</h1>
        {{-- THE FIX: Add the Export All button --}}
        <button wire:click="exportAll" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700">
            Export All to Excel
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-fg-soft" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-10 pr-3 py-2 form-input rounded-md"
                    placeholder="Search by Name, Claim ID, Mobile, City, Prize, etc...">
            </div>
        </div>
        <div>
            <select wire:model.live="selectedCampaignId" class="w-full form-select rounded-md shadow-sm">
                <option value="">Filter by Campaign</option>
                @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="selectedProductId" class="w-full form-select rounded-md shadow-sm">
                <option value="">Filter by Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Claim Details</th>
                        <th class="px-6 py-3">Prize / Campaign</th>
                        <th class="px-6 py-3">Location (City / State)</th>
                        <th class="px-6 py-3">Collection Point</th>
                        <th class="px-6 py-3">Product / SKU</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($claims as $claim)
                        <tr class="hover:bg-bkg-soft {{ $claim->is_suspicious ? 'bg-red-500/10' : '' }}">
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg-alt">{{ $claim->name }}</div>
                                <div class="text-fg-soft">{{ $claim->mobile }}</div>
                                <div class="text-xs font-mono text-fg-soft pt-1">{{ $claim->claim_id }}</div>
                                @if ($claim->is_suspicious)
                                    <div class="mt-1">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-danger/20 text-danger">
                                            Flagged
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg-alt">{{ $claim->prize_won }}</div>
                                <div class="text-fg-soft">{{ $claim->campaign->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg-alt">{{ $claim->city }}</div>
                                <div class="text-fg-soft">{{ $claim->state }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($claim->collectionPoint)
                                    {{ $claim->collectionPoint->shop_name }}
                                @else
                                    <span class="text-fg-soft italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg-alt">{{ $claim->product->name }}</div>
                                <div class="text-fg-soft">{{ $claim->product->sku }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($claim->status === 'Fulfilled') bg-success/20 text-success
                                    @else bg-yellow-500/20 text-yellow-500 @endif">
                                    {{ $claim->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-fg-soft">No claims found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $claims->links() }}
        </div>
    </div>
</div>
