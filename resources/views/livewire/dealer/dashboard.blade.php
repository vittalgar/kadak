<div class="space-y-8">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-[#2a3042] overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Pending Redemptions</dt>
                            <dd>
                                <div class="text-lg font-medium text-white">{{ number_format($pendingRedemptions) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#2a3042] overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Fulfilled Today</dt>
                            <dd>
                                <div class="text-lg font-medium text-white">{{ number_format($fulfilledToday) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#2a3042] overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Fulfilled This Month</dt>
                            <dd>
                                <div class="text-lg font-medium text-white">{{ number_format($fulfilledThisMonth) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-[#2a3042] shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold text-white">Recent Activity</h2>
        <p class="mt-1 text-sm text-gray-400">The last 5 prizes you have fulfilled.</p>
        <div class="mt-4">
            <ul role="list" class="divide-y divide-gray-700">
                @forelse ($recentClaims as $claim)
                    <li class="py-4">
                        <div class="flex space-x-3">
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-white">{{ $claim->name }} claimed a
                                        {{ $claim->prize_won }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $claim->updated_at->timezone('Asia/Kolkata')->diffForHumans() }}</p>
                                </div>
                                <p class="text-sm text-gray-400">
                                    At {{ $claim->collectionPoint->shop_name }}, {{ $claim->collectionPoint->city }}
                                </p>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-center text-sm text-gray-400">No prizes have been fulfilled yet.</li>
                @endforelse
            </ul>
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('dealer.redemptions.index') }}"
                class="text-sm font-medium text-indigo-400 hover:text-indigo-300">
                View Full Redemption List &rarr;
            </a>
        </div>
    </div>
</div>
