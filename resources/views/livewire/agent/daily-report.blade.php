<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Daily Claims Report</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label for="reportDate" class="block text-sm font-medium text-fg-soft">Select a Date</label>
            <input type="date" id="reportDate" wire:model.live="reportDate"
                class="mt-1 block w-full form-input rounded-md shadow-sm bg-bkg-soft border-border text-fg">
        </div>

        <div class="md:col-span-2">
            <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Filter by Campaign</label>
            <select id="campaignFilter" wire:model.live="selectedCampaignId"
                class="mt-1 block w-full form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
                <option value="">All Campaigns</option>
                @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="p-6 border-b border-border">
            <h2 class="text-xl font-semibold text-fg-alt">
                Showing Claims for: <span
                    class="text-primary">{{ \Carbon\Carbon::parse($reportDate)->format('d F, Y') }}</span>
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Winner Name</th>
                        <th class="px-6 py-3">Mobile</th>
                        <th class="px-6 py-3">Prize Won</th>
                        <th class="px-6 py-3">Campaign</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($claims as $claim)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $claim->name }}</td>
                            <td class="px-6 py-4">{{ $claim->mobile }}</td>
                            <td class="px-6 py-4 font-semibold text-fg">{{ $claim->prize_won }}</td>
                            <td class="px-6 py-4">{{ $claim->campaign->name }}</td>
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
                            <td colspan="5" class="text-center py-6 text-fg-soft">No prizes were claimed on this
                                date.</td>
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
