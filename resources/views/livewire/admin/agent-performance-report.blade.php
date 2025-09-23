<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Agent Performance Report</h1>

    <div class="mb-6 max-w-sm">
        <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Filter by Campaign</label>
        <select id="campaignFilter" wire:model.live="selectedCampaignId"
            class="mt-1 block w-full form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            <option value="">All Campaigns</option>
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Rank</th>
                        <th class="px-6 py-3">Shop Name</th>
                        <th class="px-6 py-3">Contact Person</th>
                        <th class="px-6 py-3">Location (City/State)</th>
                        <th class="px-6 py-3 text-center">Total Claims</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($agents as $index => $agent)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4 font-medium text-fg-alt">
                                {{ $agents->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $agent->shop_name }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg">{{ $agent->contact_person }}</div>
                                <div class="text-xs text-fg-soft">{{ $agent->phone_number_1 }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $agent->city }}, {{ $agent->state }}</td>
                            <td class="px-6 py-4 text-center text-2xl font-bold text-primary">
                                {{ number_format($agent->claims_count) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-fg-soft">No agent data available for this
                                selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $agents->links() }}
        </div>
    </div>
</div>
