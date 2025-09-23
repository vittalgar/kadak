<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Prize Inventory Report</h1>

    <div class="mb-6">
        <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Filter by Campaign</label>
        <select id="campaignFilter" wire:model.live="selectedCampaignId"
            class="mt-1 block w-full md:w-1/3 form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            <option value="">All Campaigns</option>
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign['id'] }}">{{ $campaign['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Prize / Campaign / Category</th>
                        <th class="px-6 py-3 text-center">Total Stock</th>
                        <th class="px-6 py-3 text-center">Stock (Oct)</th>
                        <th class="px-6 py-3 text-center">Stock (Nov)</th>
                        <th class="px-6 py-3 text-center">Stock (Dec)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($prizes as $prize)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg-alt">{{ $prize->name }}</div>
                                <div class="text-xs text-fg-soft">{{ $prize->campaign->name }}</div>
                                <div class="font-semibold text-fg-soft">{{ $prize->category }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-lg text-primary">
                                {{ number_format($prize->total_stock) }}</td>

                            {{-- Highlight the current month's stock --}}
                            <td @class([
                                'px-6 py-4 text-center font-medium',
                                'bg-primary/10 text-primary' => now()->month == 10,
                            ])>{{ number_format($prize->stock_oct) }}</td>
                            <td @class([
                                'px-6 py-4 text-center font-medium',
                                'bg-primary/10 text-primary' => now()->month == 11,
                            ])>{{ number_format($prize->stock_nov) }}</td>
                            <td @class([
                                'px-6 py-4 text-center font-medium',
                                'bg-primary/10 text-primary' => now()->month == 12,
                            ])>{{ number_format($prize->stock_dec) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-fg-soft">No prize inventory found for the
                                selected campaign.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $prizes->links() }}
        </div>
    </div>
</div>
