<div wire:poll.10s>
    <div class="flex space-x-2 mb-6">
        <button wire:click="setFilter('today')"
            class="{{ $filterPeriod === 'today' ? 'bg-indigo-500 text-white' : 'bg-[#2a3042] text-gray-300 hover:bg-gray-700' }} px-4 py-2 text-sm font-medium rounded-md shadow-sm">Today</button>
        <button wire:click="setFilter('7_days')"
            class="{{ $filterPeriod === '7_days' ? 'bg-indigo-500 text-white' : 'bg-[#2a3042] text-gray-300 hover:bg-gray-700' }} px-4 py-2 text-sm font-medium rounded-md shadow-sm">Last
            7 Days</button>
        <button wire:click="setFilter('30_days')"
            class="{{ $filterPeriod === '30_days' ? 'bg-indigo-500 text-white' : 'bg-[#2a3042] text-gray-300 hover:bg-gray-700' }} px-4 py-2 text-sm font-medium rounded-md shadow-sm">Last
            30 Days</button>
        <button wire:click="setFilter('all_time')"
            class="{{ $filterPeriod === 'all_time' ? 'bg-indigo-500 text-white' : 'bg-[#2a3042] text-gray-300 hover:bg-gray-700' }} px-4 py-2 text-sm font-medium rounded-md shadow-sm">All
            Time</button>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-[#2a3042] overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0"><svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg></div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Total Prizes Claimed</dt>
                            <dd>
                                <div class="text-lg font-medium text-white">{{ number_format($data['totalClaims']) }}
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
                    <div class="flex-shrink-0"><svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M5.625 6.375a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0z" />
                        </svg></div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Unique Participants</dt>
                            <dd>
                                <div class="text-lg font-medium text-white">
                                    {{ number_format($data['uniqueParticipants']) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#2a3042] overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0"><svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 6.75h.75v.75h-.75zM6.75 16.5h.75v.75h-.75zM16.5 6.75h.75v.75h-.75zM13.5 16.5h.75v.75h-.75zM10.5 13.5h.75v.75h-.75zM10.5 19.5h.75v.75h-.75zM19.5 13.5h.75v.75h-.75zM19.5 19.5h.75v.75h-.75z" />
                        </svg></div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Total QR Codes Generated</dt>
                            <dd>
                                <div class="text-lg font-medium text-white">
                                    {{ number_format($data['totalTokensGenerated']) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle">
                <div class="overflow-hidden shadow ring-1 ring-white ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-[#2a3042]">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Winner</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Prize Won</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Date & Time (IST)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#2a3042] divide-y divide-gray-700">
                            @forelse ($data['recentClaims'] as $claim)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">{{ $claim->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $claim->city }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $claim->prize_won }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $claim->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                            </tr>@empty<tr>
                                    <td colspan="3"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-400">No claims
                                        found for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-right"><a href="{{ route('claims.report') }}"
                        class="text-sm font-medium text-indigo-400 hover:text-indigo-300">View All Claims &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
