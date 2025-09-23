<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Prize Inventory Report</h1>
    <p class="text-fg-soft mb-6">This report shows the current stock levels for all available prizes in active
        campaigns.</p>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Prize / Campaign</th>
                        <th class="px-6 py-3 text-center">Total Stock</th>
                        <th class="px-6 py-3 text-center">Stock for {{ now()->format('F') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($prizes as $prize)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4">
                                <div class="font-medium text-fg-alt">{{ $prize->name }}</div>
                                <div class="text-xs text-fg-soft">{{ $prize->campaign->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-lg text-primary">
                                {{ number_format($prize->total_stock) }}</td>
                            <td class="px-6 py-4 text-center font-medium">
                                {{ number_format($prize->{$currentMonthStockColumn}) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-fg-soft">No prize inventory found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $prizes->links() }}</div>
    </div>
</div>
