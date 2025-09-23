<div class="space-y-8" wire:poll.15s="generateReport">
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Admin Dashboard</h1>

    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="campaignFilter"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Campaign</label>
                <select wire:model.live="selectedCampaignId" id="campaignFilter" class="mt-1 block w-full form-select">
                    <option value="">All Campaigns</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="stateFilter"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                <select wire:model.live="selectedStateId" id="stateFilter" class="mt-1 block w-full form-select">
                    <option value="">All States</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="cityFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                <select wire:model.live="selectedCityId" id="cityFilter" class="mt-1 block w-full form-select"
                    @if ($cities->isEmpty()) disabled @endif>
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                        Date</label>
                    <input wire:model.live="startDate" type="date" id="startDate"
                        class="mt-1 block w-full form-input">
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                        Date</label>
                    <input wire:model.live="endDate" type="date" id="endDate" class="mt-1 block w-full form-input">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generated</h3>
            <p class="text-5xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                {{ number_format($qrCodesGenerated) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total QR Codes</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ number_format($scanRate, 1) }}%
                &rarr;</div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Scanned</h3>
            <p class="text-5xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">{{ number_format($totalScanned) }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Conversion from Generated</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ number_format($claimRate, 1) }}%
                &rarr;</div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Claimed</h3>
            <p class="text-5xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($totalClaims) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Conversion from Scanned</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Claims</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalClaims) }}</p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Unique Participants</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($uniqueParticipants) }}
            </p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Campaigns</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $activeCampaigns }}</p>
        </div>
        <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-fg-soft">"Better Luck" Awarded</h3>
            <p class="mt-2 text-3xl font-bold text-fg-alt">{{ number_format($betterLuckCount) }}</p>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div
            class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Prizes Claimed</h2>
            @if ($this->chart)
                <x-chartjs-component :chart="$this->chart" />
            @else
                <div class="flex items-center justify-center h-80">
                    <p class="text-gray-500 dark:text-gray-400">No prize data available to display.</p>
                </div>
            @endif
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Claims</h2>
            <div class="space-y-4">
                @forelse ($recentClaims as $claim)
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-full p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <span class="font-bold">{{ $claim->name }}</span> claimed a <span
                                    class="font-bold">{{ $claim->prize_won }}</span>.
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $claim->created_at->diffForHumans() }} in {{ $claim->city }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                        No claims have been made yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function prizeChart(data) {
            return {
                drawChart() {
                    // If there's no data, don't try to draw the chart.
                    if (Object.keys(data).length === 0) {
                        return;
                    }

                    const labels = Object.keys(data);
                    const values = Object.values(data);
                    const isDarkMode = document.documentElement.classList.contains('dark');

                    // Destroy previous chart instance if it exists to prevent conflicts
                    if (this.$refs.chart.chart) {
                        this.$refs.chart.chart.destroy();
                    }

                    this.$refs.chart.chart = new Chart(this.$refs.chart, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Prizes Claimed',
                                data: values,
                                backgroundColor: isDarkMode ? 'rgba(129, 140, 248, 0.6)' :
                                    'rgba(79, 70, 229, 0.6)',
                                borderColor: isDarkMode ? 'rgba(129, 140, 248, 1)' : 'rgba(79, 70, 229, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: isDarkMode ? '#9ca3af' : '#6b7280'
                                    },
                                    grid: {
                                        color: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: isDarkMode ? '#9ca3af' : '#6b7280'
                                    },
                                    grid: {
                                        display: false
                                    }
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
