<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Project Kadak' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen lg:flex">

        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col flex-grow overflow-y-auto">
                    <livewire:layout.navigation />
                </div>
            </div>
        </div>

        <div class="flex flex-col flex-1 lg:pl-2">
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow items-center">
                <button @click="sidebarOpen = true" type="button"
                    class="px-4 border-r border-gray-200 text-gray-500 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-end">
                    <livewire:layout.profile-dropdown />
                </div>
            </div>

            <main class="flex-1 relative overflow-y-auto focus:outline-none bg-gray-100">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mt-4">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="fixed inset-0 z-50 flex lg:hidden"
            role="dialog" aria-modal="true" style="display: none;">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative flex flex-col w-64 max-w-xs bg-[#042455] text-white">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false" type="button"
                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <livewire:layout.navigation />
            </div>
        </div>
    </div>

    <div x-data="{
        toasts: [],
        add(toast) {
            this.toasts.push(toast);
            setTimeout(() => this.remove(toast.id), 10000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
        @notify.window="add({ id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'success' })"
        class="fixed top-5 right-5 z-[100] space-y-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-8"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0 transform translate-x-8"
                class="p-4 rounded-lg shadow-lg flex items-center"
                :class="{
                    'bg-green-500/90 border border-green-600 text-white': toast.type === 'success',
                    'bg-red-500/90 border border-red-600 text-white': toast.type === 'error',
                    'bg-blue-500/90 border border-blue-600 text-white': toast.type === 'info',
                
                }">
                <span x-text="toast.message"></span>
                <button @click="remove(toast.id)" class="ml-4 text-white/70 hover:text-white">&times;</button>
            </div>
        </template>
    </div>

    <div x-data="{
        show: false,
        title: '',
        message: '',
        confirmAction: () => {}
    }"
        @open-confirm.window="
            show = true;
            title = $event.detail.title;
            message = $event.detail.message;
            confirmAction = $event.detail.confirmAction;
        "
        x-show="show" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-[100]"
        @keydown.escape.window="show = false">
        <div class="bg-bkg-alt rounded-lg shadow-xl p-8 w-full max-w-md" @click.away="show = false">
            <h2 class="text-2xl font-bold mb-4 text-fg-alt" x-text="title"></h2>
            <p class="mb-6 text-fg-soft" x-text="message"></p>
            <div class="flex items-center justify-end space-x-4">
                <button @click="show = false"
                    class="bg-bkg-soft text-fg font-bold py-2 px-4 rounded-lg hover:opacity-80">
                    Cancel
                </button>
                {{-- FIX: Use the theme-aware 'bg-danger' class --}}
                <button @click="confirmAction(); show = false;"
                    class="bg-danger text-white font-bold py-2 px-4 rounded-lg hover:opacity-90">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
