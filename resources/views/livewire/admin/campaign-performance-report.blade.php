<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Campaign Performance Report</h1>
    <div class="bg-bkg-alt p-4 rounded-lg shadow-sm mb-6 border border-border">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Select Campaign</label>
                <select id="campaignFilter" wire:model.live="selectedCampaignId"
                    class="mt-1 block w-full form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
                    <option value="">-- All Campaigns --</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="startDate" class="block text-sm font-medium text-fg-soft">Start Date</label>
                <input type="date" id="startDate" wire:model.live="startDate"
                    class="mt-1 block w-full form-input rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            </div>
            <div>
                <label for="endDate" class="block text-sm font-medium text-fg-soft">End Date</label>
                <input type="date" id="endDate" wire:model.live="endDate"
                    class="mt-1 block w-full form-input rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            </div>
        </div>
    </div>

    @if ($selectedCampaignId)
        <div class="space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-fg-soft">Total Claims</h3>
                    <p class="mt-2 text-3xl font-bold text-fg-alt">{{ number_format($reportData['totalClaims']) }}</p>
                </div>
                <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-fg-soft">Unique Participants</h3>
                    <p class="mt-2 text-3xl font-bold text-fg-alt">
                        {{ number_format($reportData['uniqueParticipants']) }}</p>
                </div>
            </div>

            <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-fg-alt">Claims Over Time</h2>
                <div class="mt-4 h-160">
                    @if ($this->chart)
                        <x-chartjs-component :chart="$this->chart" />
                    @else
                        <div class="flex items-center justify-center h-80">
                            <p class="text-gray-500 dark:text-gray-400">No prize data available to display.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-fg-alt mb-4">Top Prizes Won</h2>
                    <ul class="space-y-3">
                        @forelse ($reportData['topPrizes'] as $prize => $count)
                            <li class="flex justify-between items-center text-sm">
                                <span class="font-medium text-fg">{{ $prize }}</span>
                                <span
                                    class="font-bold text-primary px-2 py-1 bg-primary/10 rounded-full">{{ $count }}</span>
                            </li>
                        @empty
                            <li class="text-center py-4 text-fg-soft">No prize data available.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-fg-alt mb-4">Top Locations (Cities)</h2>
                    <ul class="space-y-3">
                        @forelse ($reportData['topLocations'] as $city => $count)
                            <li class="flex justify-between items-center text-sm">
                                <span class="font-medium text-fg">{{ $city }}</span>
                                <span
                                    class="font-bold text-primary px-2 py-1 bg-primary/10 rounded-full">{{ $count }}</span>
                            </li>
                        @empty
                            <li class="text-center py-4 text-fg-soft">No location data available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm text-center text-fg-soft">
            <p>Please select a campaign to view its performance report.</p>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function claimsChart(data) {
            return {
                drawChart() {
                    // Ensure chart doesn't re-initialize on every poll
                    if (this.$refs.chart.chart) {
                        this.$refs.chart.chart.destroy();
                    }

                    const labels = Object.keys(data);
                    const values = Object.values(data);

                    this.$refs.chart.chart = new Chart(this.$refs.chart, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Claims per Day',
                                data: values,
                                backgroundColor: 'hsl(var(--primary) / 0.2)',
                                borderColor: 'hsl(var(--primary))',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
@endpush
