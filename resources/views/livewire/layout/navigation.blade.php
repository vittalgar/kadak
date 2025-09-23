<div class="flex flex-col h-full">
    <div class="flex items-center justify-center h-16 flex-shrink-0 bg-bkg-alt border-b border-border">
        <a href="/">
            <img class="h-10 w-auto" src="{{ asset('images/logo.png') }}" alt="Bharath Beverages">
        </a>
    </div>

    <div class="flex flex-1 flex-col">
        <nav class="flex-1 space-y-1 px-2 py-2 overflow-y-auto">
            @if (strtolower(auth()->user()->role->name) === 'admin')
                <x-nav-link-icon :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </x-slot>
                    Admin Dashboard
                </x-nav-link-icon>

                <div class="border-t border-gray-700 dark:border-gray-600 pt-4">
                    <p class="px-2 text-xs font-bold uppercase text-fg-alt tracking-wider">Manage</p>
                    <div class="mt-1 space-y-1">
                        <x-nav-link-icon :href="route('admin.products.index')" :active="request()->routeIs('admin.products.index')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 16.811c0 .864-.933 1.405-1.683 .977l-7.108-4.062a1.125 1.125 0 00-1.391 0l-7.108 4.062c-.75 .429-1.683-.113-1.683-.977V7.19c0-.864 .933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 001.391 0l7.108-4.062c.75-.429 1.683 .113 1.683 .977v9.622z" />
                                </svg>
                            </x-slot>
                            Products
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.campaigns.index')" :active="request()->routeIs('admin.campaigns.index')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28 .53v15.88a.75.75 0 01-1.28 .53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                                </svg>
                            </x-slot>
                            Campaigns
                        </x-nav-link-icon>
                        {{-- <x-nav-link-icon :href="route('admin.dealers.index')" :active="request()->routeIs('admin.dealers.index')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </x-slot>
                            Dealers
                        </x-nav-link-icon> --}}
                        <x-nav-link-icon :href="route('admin.agents.index')" :active="request()->routeIs('admin.agents.index')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </x-slot>
                            Agents
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.qr-batches.index')" :active="request()->routeIs('admin.qr-batches.index')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z" />
                                </svg>
                            </x-slot>
                            QR Code Batches
                        </x-nav-link-icon>
                    </div>
                </div>
                <div class="border-t border-gray-700 dark:border-gray-600 pt-4">
                    <p class="px-2 text-xs font-bold uppercase text-fg-alt tracking-wider">Reports</p>
                    <div class="mt-1 space-y-1">
                        <x-nav-link-icon :href="route('admin.reports.claims')" :active="request()->routeIs('admin.reports.claims')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75 .75 0 00.75-.75 2.3 2.3 0 00-.1-.664m-5.2 0a2.2 2.2 0 00-.1.664.75 .75 0 00.75.75h4.5a.75 .75 0 00.75-.75 2.3 2.3 0 00-.1-.664m-5.2 0a2.2 2.2 0 00-.1.664m5.2 0h4.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3v18h18V3H3zm8.25 9.75h1.5v1.5h-1.5v-1.5z" />
                                </svg>
                            </x-slot>
                            Claims Report
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.prize-inventory')" :active="request()->routeIs('admin.reports.prize-inventory')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </x-slot>
                            Prize Inventory
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.agent-performance')" :active="request()->routeIs('admin.reports.agent-performance')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.5l6.19 6.19a6.75 6.75 0 009.42 0l.001-.001a6.75 6.75 0 000-9.42L13.5 3M3 20.25v-15a.75 .75 0 01.75-.75h16.5a.75 .75 0 01.75 .75v15a.75 .75 0 01-.75.75H3.75a.75 .75 0 01-.75-.75z" />
                                </svg>
                            </x-slot>
                            Agent Performance
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.campaign-performance')" :active="request()->routeIs('admin.reports.campaign-performance')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                </svg>
                            </x-slot>
                            Campaign Performance
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.location-performance')" :active="request()->routeIs('admin.reports.location-performance')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V15.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                                </svg>
                            </x-slot>
                            Location Performance
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.winners-map')" :active="request()->routeIs('admin.reports.winners-map')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </x-slot>
                            Live Winners Map
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.product-sales')" :active="request()->routeIs('admin.reports.product-sales')">
                            <x-slot name="icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM10.5 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM10.5 18a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg></x-slot>
                            Product Sales
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('admin.reports.time-analytics')" :active="request()->routeIs('admin.reports.time-analytics')">
                            <x-slot name="icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg></x-slot>
                            Time Analytics
                        </x-nav-link-icon>
                    </div>
                </div>
            @elseif (strtolower(auth()->user()->role->name) === 'agent')
                <x-nav-link-icon :href="route('agent.dashboard')" :active="request()->routeIs('agent.dashboard')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </x-slot>
                    Agent Dashboard
                </x-nav-link-icon>

                <x-nav-link-icon :href="route('agent.redemptions.index')" :active="request()->routeIs('agent.redemptions.index')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot>
                    Redemption List
                </x-nav-link-icon>

                <div class="border-t border-gray-700 dark:border-gray-600 pt-4">
                    <p class="px-2 text-xs font-bold uppercase text-fg-alt tracking-wider">Reports</p>
                    <div class="mt-1 space-y-1">
                        <x-nav-link-icon :href="route('agent.reports.daily')" :active="request()->routeIs('agent.reports.daily')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12V13.5zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12V17.25zm0 2.25h.008v.008H12V19.5zm2.25-4.5h.008v.008h-.008V15zm0 2.25h.008v.008h-.008V17.25zm0 2.25h.008v.008h-.008V19.5zm2.25-4.5h.008v.008h-.008V15zm0 2.25h.008v.008h-.008V17.25zm0 2.25h.008v.008h-.008V19.5zM9 15h.008v.008H9V15zm0 2.25h.008v.008H9V17.25zm0 2.25h.008v.008H9V19.5z" />
                                </svg>
                            </x-slot>
                            Daily Claims
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('agent.reports.prize-inventory')" :active="request()->routeIs('agent.reports.prize-inventory')">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 22"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </x-slot>
                            Prize Inventory
                        </x-nav-link-icon>
                        <x-nav-link-icon :href="route('agent.reports.detailed-prize')" :active="request()->routeIs('agent.reports.detailed-prize')">
                            <x-slot name="icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM10.5 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM10.5 18a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg></x-slot>
                            Detailed Report
                        </x-nav-link-icon>
                    </div>
                </div>
            @endif
        </nav>
    </div>
</div>
