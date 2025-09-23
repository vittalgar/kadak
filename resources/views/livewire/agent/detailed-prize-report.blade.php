<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Detailed Prize Report</h1>

    <div class="bg-bkg-alt p-4 rounded-lg shadow-sm mb-6 border border-border">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                <label for="statusFilter" class="block text-sm font-medium text-fg-soft">Status</label>
                <select id="statusFilter" wire:model.live="filterStatus" class="mt-1 block w-full form-select">
                    <option value="">All Statuses</option>
                    <option value="Processing">Processing</option>
                    <option value="Fulfilled">Fulfilled</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Prize Name</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Count</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($prizeSummary as $summary)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $summary->prize_won }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($summary->status === 'Fulfilled') bg-success/20 text-success 
                                    @else bg-yellow-500/20 text-yellow-500 @endif">
                                    {{ $summary->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-lg text-primary">{{ $summary->count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-fg-soft">No prize data found for the
                                selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $prizeSummary->links() }}</div>
    </div>
</div>
