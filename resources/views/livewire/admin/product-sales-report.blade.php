<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Product Sales Report</h1>

    <div class="bg-bkg-alt p-4 rounded-lg shadow-sm mb-6 border border-border">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div>
                <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Campaign</label>
                <select id="campaignFilter" wire:model.live="filterCampaignId" class="mt-1 block w-full form-select">
                    <option value="">All Campaigns</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="stateFilter" class="block text-sm font-medium text-fg-soft">State</label>
                <select id="stateFilter" wire:model.live="filterStateId" class="mt-1 block w-full form-select">
                    <option value="">All States</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="cityFilter" class="block text-sm font-medium text-fg-soft">City</label>
                <select id="cityFilter" wire:model.live="filterCityId" class="mt-1 block w-full form-select"
                    @if ($cities->isEmpty()) disabled @endif>
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="agentFilter" class="block text-sm font-medium text-fg-soft">Agent</label>
                <select id="agentFilter" wire:model.live="filterAgentId" class="mt-1 block w-full form-select"
                    @if ($agents->isEmpty()) disabled @endif>
                    <option value="">All Agents</option>
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->shop_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="startDate" class="block text-sm font-medium text-fg-soft">Start Date</label>
                    <input wire:model.live="startDate" type="date" id="startDate"
                        class="mt-1 block w-full form-input">
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-medium text-fg-soft">End Date</label>
                    <input wire:model.live="endDate" type="date" id="endDate" class="mt-1 block w-full form-input">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Product Name</th>
                        <th class="px-6 py-3">SKU</th>
                        <th class="px-6 py-3 text-center">Total Sales (Claims)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($productSales as $sale)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $sale->product_name }}</td>
                            <td class="px-6 py-4">{{ $sale->product_sku }}</td>
                            <td class="px-6 py-4 text-center font-bold text-lg text-primary">{{ $sale->sales_count }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-fg-soft">No product sales data found for the
                                selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $productSales->links() }}
        </div>
    </div>
</div>
