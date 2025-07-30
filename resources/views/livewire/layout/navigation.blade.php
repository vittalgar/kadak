<div>
    @auth
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-center h-16 flex-shrink-0 bg-white">
                <a href="/">
                    <img class="h-10 w-auto" src="{{ asset('logo.png') }}" alt="Project Kadak Logo">
                </a>
            </div>

            <nav class="mt-5 flex-1 px-2 space-y-1">

                @if (strtolower(auth()->user()->role->name) === 'admin')
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="{{ request()->routeIs('products.index') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Product Manager
                    </a>
                    <a href="{{ route('campaigns.index') }}"
                        class="{{ request()->routeIs('campaigns.index') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.362-3.797z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.362 5.214C14.267 4.112 12.72 3.5 11.062 3.5c-1.658 0-3.203.612-4.3 1.714M15.362 5.214A3.375 3.375 0 0014.28 3.667 3.375 3.375 0 0012.12 5.82a3.375 3.375 0 002.24 2.24 3.375 3.375 0 001.002-.217z" />
                        </svg>
                        Campaign Manager
                    </a>
                    <a href="{{ route('qr.manager') }}"
                        class="{{ request()->routeIs('qr.manager') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 6.75h.75v.75h-.75zM6.75 16.5h.75v.75h-.75zM16.5 6.75h.75v.75h-.75zM13.5 16.5h.75v.75h-.75zM10.5 13.5h.75v.75h-.75zM10.5 19.5h.75v.75h-.75zM19.5 13.5h.75v.75h-.75zM19.5 19.5h.75v.75h-.75z" />
                        </svg>
                        QR Batch Manager
                    </a>
                    <a href="{{ route('dealers.index') }}"
                        class="{{ request()->routeIs('dealers.index') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM12 21v-5.272A5.969 5.969 0 016 15m0 0a5.969 5.969 0 00-7.5 0M15 15a5.969 5.969 0 016 0m0 0v.343a5.969 5.969 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                        Dealer Manager
                    </a>
                    <a href="{{ route('claims.report') }}"
                        class="{{ request()->routeIs('claims.report') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Claims Report
                    </a>
                    <a href="{{ route('winners.map') }}"
                        class="{{ request()->routeIs('winners.map') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l6.553-3.276A1 1 0 0021 12.618V4.382a1 1 0 00-1.447-.894L15 6m-6 1v10" />
                        </svg>
                        Winners Map
                    </a>
                @endif

                @if (strtolower(auth()->user()->role->name) === 'dealer')
                    <a href="{{ route('dealer.dashboard') }}"
                        class="{{ request()->routeIs('dealer.dashboard') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                        Dealer Dashboard
                    </a>
                    <a href="{{ route('dealer.redemptions.index') }}"
                        class="{{ request()->routeIs('dealer.redemptions.index') ? 'bg-gray-900/50 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Redemption List
                    </a>
                @endif

            </nav>
        </div>
    @endauth
</div>
