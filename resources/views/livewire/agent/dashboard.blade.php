<div class="space-y-8">
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Agent Dashboard</h1>

    <div class="max-w-sm">
        <label for="campaignFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Show Stats
            For</label>
        <select wire:model.live="selectedCampaignId" id="campaignFilter"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">All Campaigns</option>
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Pending
                                Redemptions</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $pendingRedemptions }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Prizes Fulfilled
                                Today</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $fulfilledToday }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0H21" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Prizes Fulfilled
                                This Month</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $fulfilledThisMonth }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3 lg:grid-flow-col-dense">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Activities</h2>
                <div class="mt-4 space-y-4 flex-grow">
                    @forelse ($recentClaims as $claim)
                        <div
                            class="flex items-center space-x-4 border-b border-gray-200 dark:border-gray-700 pb-3 last:border-b-0 last:pb-0">
                            <div
                                class="bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 rounded-full p-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <span class="font-bold">{{ $claim->name }}</span> claimed a <span
                                        class="font-bold">{{ $claim->prize_won }}</span>.
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $claim->updated_at->diffForHumans() }} at
                                    {{ $claim->collectionPoint->shop_name }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-4 text-gray-500 dark:text-gray-400 h-full flex items-center justify-center">
                            <p>No recent fulfilled claims to show.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Top Redeemed Prizes</h2>
                <ul class="mt-4 space-y-3">
                    @forelse ($topPrizes as $prize)
                        <li class="flex justify-between items-center text-sm">
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $prize->prize_won }}</span>
                            <span
                                class="font-bold text-indigo-600 dark:text-indigo-400 px-2 py-1 bg-indigo-50 dark:bg-indigo-500/10 rounded-full">{{ $prize->count }}</span>
                        </li>
                    @empty
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                            <p>No prizes have been redeemed yet.</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
