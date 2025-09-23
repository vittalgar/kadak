<div>
    @auth
        <div class="ml-4 flex items-center md:ml-6">
            {{-- <div x-data="{ open: false }" class="relative">
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
            </div> --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center text-sm font-medium text-fg-soft hover:text-fg-alt focus:outline-none">
                    <div class="h-8 w-8 rounded-full bg-[#042455] text-white flex items-center justify-center font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="ml-2">{{ Auth::user()->name }}</div>
                    <div class="ml-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-bkg-alt ring-1 ring-black ring-opacity-5"
                    style="display: none;">
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="block px-4 py-2 text-sm text-fg hover:bg-bkg-soft">My Profile</a>
                        <a href="{{ route('profile.password') }}" wire:navigate
                            class="block px-4 py-2 text-sm text-fg hover:bg-bkg-soft">Change Password</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block w-full text-left px-4 py-2 text-sm text-fg hover:bg-bkg-soft">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>
