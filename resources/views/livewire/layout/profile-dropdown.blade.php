<div>
    @auth
        <div class="ml-4 flex items-center md:ml-6">
            <div x-data="{ open: false }" class="relative">
                <div>
                    <button @click="open = !open" type="button"
                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#042455]">
                        <span class="sr-only">Open user menu</span>
                        <div class="h-8 w-8 rounded-full bg-[#042455] text-white flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>
                </div>
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute right-0 z-10 mt-2 w-48 origin-to-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="block px-4 py-2 text-sm text-gray-700 border-b">
                        Signed in as<br>
                        <span class="font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('profile.password') }}"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Change Password
                    </a>
                    <button wire:click="logout" type="button"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Sign out
                    </button>
                </div>
            </div>
        </div>
    @endauth
</div>
