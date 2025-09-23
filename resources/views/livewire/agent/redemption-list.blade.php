<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Redemption List</h1>

    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-border-color">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full form-input rounded-md"
                    placeholder="Search by Claim ID or Mobile...">
            </div>
            <div>
                <label for="campaignFilter" class="sr-only">Campaign</label>
                <select id="campaignFilter" wire:model.live="filterCampaignId" class="w-full form-select">
                    <option value="">All Campaigns</option>
                    @foreach ($campaigns as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="statusFilter" class="sr-only">Status</label>
                <select id="statusFilter" wire:model.live="filterStatus" class="w-full form-select">
                    <option value="">All Statuses</option>
                    <option value="Processing">Processing</option>
                    <option value="Fulfilled">Fulfilled</option>
                </select>
            </div>
            <div class="text-right">
                <button wire:click="export"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">
                    Export to Excel
                </button>
            </div>
        </div>
    </div>

    <div x-data="{ selectedClaims: @entangle('selectedClaims').live }" class="bg-white rounded-lg shadow-sm border border-border-color">
        <div x-show="selectedClaims.length > 0" class="p-4 bg-gray-50 border-b flex items-center justify-between">
            <div class="text-sm font-semibold text-gray-700">
                {{-- THE FIX: We use x-text to display the count from the Alpine property --}}
                <span x-text="selectedClaims.length"></span> claims selected.
            </div>
            <button wire:click="markSelectedAsFulfilled"
                wire:confirm="Are you sure you want to mark all selected claims as fulfilled?"
                class="bg-primary text-white font-bold py-2 px-4 rounded-lg hover:bg-primary-hover">
                Mark All Selected as Fulfilled
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="p-4">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="form-checkbox rounded text-primary focus:ring-primary-hover">
                        </th>
                        <th scope="col" class="px-6 py-3">Claim ID</th>
                        <th scope="col" class="px-6 py-3">Winner Details</th>
                        <th scope="col" class="px-6 py-3">Prize</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($claims as $claim)
                        <tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-4">
                                @if ($claim->status === 'Processing')
                                    <input type="checkbox" wire:model.live="selectedClaims" value="{{ $claim->id }}"
                                        class="form-checkbox rounded text-primary focus:ring-primary-hover">
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-900">{{ $claim->claim_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $claim->name }} ({{ $claim->mobile }})</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $claim->prize_won }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $claim->status === 'Fulfilled' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $claim->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($claim->status === 'Processing')
                                    <button wire:click="markAsFulfilled({{ $claim->id }})"
                                        wire:confirm="Are you sure you have given this prize to the customer?"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Mark as Fulfilled
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No claims found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $claims->links() }}
        </div>
    </div>
    
    @if (!empty($prizeSummary))
        <div class="bg-bkg-alt rounded-lg shadow-sm mt-8 mb-8 border border-border">
            <div class="p-6 border-b border-border">
                <h2 class="text-xl font-semibold text-fg-alt">Prize Summary</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    {{-- ... your prize summary table head ... --}}
                    <tbody class="divide-y divide-border">
                        @php
                            // We need to process the paginated summary results
                            $groupedSummary = $prizeSummary->groupBy('prize_won');
                        @endphp
                        @forelse ($groupedSummary as $prizeName => $statuses)
                            <tr class="hover:bg-bkg-soft">
                                <td class="px-6 py-4 font-medium text-fg-alt">{{ $prizeName }}</td>
                                <td class="px-6 py-4 text-center font-medium text-yellow-500">
                                    {{ $statuses->firstWhere('status', 'Processing')->count ?? 0 }}</td>
                                <td class="px-6 py-4 text-center font-medium text-success">
                                    {{ $statuses->firstWhere('status', 'Fulfilled')->count ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-fg-soft">No prize data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $prizeSummary->links() }}
            </div>
        </div>
    @endif
    
</div>
