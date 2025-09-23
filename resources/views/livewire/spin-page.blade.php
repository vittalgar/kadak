@push('styles')
    <style>
        html,
        body {
            height: 100%;
            background: #000;
        }

        body {
            overflow-x: hidden;
            overscroll-behavior-y: none;
            /* iOS 16.4+ */
            -webkit-font-smoothing: antialiased;
        }
    </style>
@endpush

<div>
    {{-- Audio elements --}}
    <audio id="tick-sound" src="{{ asset('sounds/tick.wav') }}" preload="auto"></audio>
    <audio id="win-sound" src="{{ asset('sounds/win.mp3') }}" preload="auto"></audio>
    <audio id="confetti-sound" src="{{ asset('sounds/confetti.mp3') }}" preload="auto"></audio>

    {{-- Backgrounds --}}
    <div class="fixed inset-0 z-0 bg-black pointer-events-none" aria-hidden="true">
        <video autoplay muted loop playsinline webkit-playsinline class="fixed inset-0 w-full h-full object-cover"
            style="height: var(--app-h, 100dvh);">
            <source src="{{ asset('videos/bg.mp4') }}" type="video/mp4">
        </video>
    </div>

    {{-- Main container for positioning --}}
    <div class="relative z-10 w-full" style="min-height: var(--app-h, 100dvh);">

        <div x-data="spinWheelManager()" x-init="initWheelData({{ json_encode($initialPrizes) }})" @spin-to.window="startSpin($event.detail)" class="w-full">

            <div x-show="$wire.currentState === 'loading'"
                class="fixed inset-0 z-40 flex flex-col items-center justify-center bg-black/50 backdrop-blur-sm">
                <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-4 text-white text-lg">Validating your code...</p>
            </div>


            {{-- Initial Details Form --}}
            <div x-show="$wire.currentState === 'collectUserDetails'"
                class="fixed inset-0 z-30 flex p-2 sm:p-6 bg-black/80 backdrop-blur-xl overflow-y-auto">
                <div
                    class="relative mx-auto my-auto w-[min(92vw,600px)] max-h-[88svh] overflow-y-auto p-6 sm:p-10 bg-gray-900/90 rounded-2xl border-t-4 border-blue-500 shadow-2xl">
                    <div class="text-center">
                        <h2 class="text-4xl sm:text-5xl font-bold text-blue-300">Welcome!</h2>
                        <p class="mt-4 text-gray-300">Please provide your details to spin the wheel.</p>
                    </div>
                    <form wire:submit="submitInitialDetails" class="mt-6 space-y-4">
                        <div>
                            <label for="claimMobile" class="text-sm font-medium text-gray-300">Mobile Number</label>
                            <input wire:model.blur="claimMobile" type="tel" id="claimMobile"
                                placeholder="Enter Your 10-Digit Mobile"
                                class="w-full mt-1 p-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-400">
                            @error('claimMobile')
                                <span class="text-red-600 text-base font-semibold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="selectedProductId" class="text-sm font-medium text-gray-300">Product
                                Purchased</label>
                            <select wire:model.live="selectedProductId" id="selectedProductId"
                                class="w-full mt-1 p-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-400">
                                <option value="">Select a Product...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                @endforeach
                            </select>
                            @error('selectedProductId')
                                <span class="text-red-600 text-base font-semibold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full px-4 py-3 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Proceed
                            to Spin</button>
                    </form>
                </div>
            </div>


            {{-- SPIN STATE --}}
            <div x-show="$wire.currentState === 'spin'" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="absolute left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center w-full p-0 sm:p-4"
                style="top: 70.0%;">

                <div wire:ignore class="relative mx-auto w-[min(75vw,65svh)] h-[min(75vw,65svh)]">

                    <div id="pointer" class="absolute top-[-4%] left-1/2 -translate-x-1/2 z-30" style="width: 14%;">
                        <svg viewBox="0 0 100 120" class="drop-shadow-lg">
                            <defs>
                                <linearGradient id="pointerGold" x1="0%" y1="0%" x2="100%"
                                    y2="100%">
                                    <stop offset="0%" stop-color="#FFFBEB" />
                                    <stop offset="50%" stop-color="#FDE047" />
                                    <stop offset="100%" stop-color="#EAB308" />
                                </linearGradient>
                            </defs>
                            <polygon points="50,120 0,0 100,0" fill="url(#pointerGold)" />
                            <polygon points="50,120 100,0 50,0" fill="#FBBF24" />
                        </svg>
                    </div>

                    <!-- Center-locked GIF overlay (follows all parent shifts, does NOT rotate) -->
                    <div class="absolute inset-0 grid place-items-center z-20 pointer-events-none">
                        <!-- Scale via width % so it remains proportional to the wheel wrapper -->
                        <img src="{{ asset('images/spin.gif') }}" alt="" class="w-[35%] h-auto" />
                    </div>

                    <div id="wheel-container" class="w-full h-full origin-center [will-change:transform] z-10">
                        <svg id="wheel-svg" viewBox="-25 -25 450 450" preserveAspectRatio="xMidYMid meet"
                            class="w-full h-full"></svg>
                    </div>
                </div>

                <button @click="handleSpinClick()" :disabled="isSpinning"
                    class="mt-8 px-8 py-4 text-white font-bold text-2xl bg-red-600 rounded-2xl shadow-2xl hover:bg-red-700 hover:scale-110 transition-transform duration-300 disabled:bg-gray-500 disabled:cursor-not-allowed disabled:scale-100">
                    <span x-show="!isSpinning">SPIN!</span>
                    <span x-show="isSpinning" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Spinning...
                    </span>
                </button>
            </div>

            {{-- OTHER STATES (CLAIM, AGENT, ETC.) --}}
            <div x-show="$wire.currentState === 'claim' || $wire.currentState === 'thankYou' || $wire.currentState === 'error' || $wire.currentState === 'betterLuck'"
                class="min-h-dvh w-full flex items-center justify-center p-2 sm:p-4">
                <div class="w-full max-w-md">

                    {{-- Claim Form --}}
                    <div x-show="$wire.currentState === 'claim'"
                        class="fixed inset-0 z-30 flex p-2 sm:p-6 bg-black/80 backdrop-blur-xl overflow-y-auto">
                        <div
                            class="relative mx-auto my-auto w-[min(92vw,600px)] max-h-[88svh] overflow-y-auto p-6 sm:p-10 bg-gray-900/90 rounded-2xl border-t-4 border-yellow-500 shadow-2xl">
                            <div class="text-center">
                                <h2 class="text-4xl sm:text-5xl font-bold text-yellow-300">🎉 Congratulations!</h2>
                                <p class="mt-2 text-xl text-white">You've won:</p>
                                <span
                                    class="text-3xl sm:text-4xl font-bold uppercase tracking-wider text-yellow-500">{{ $winningPrize }}</span>
                                <p class="mt-4 text-gray-300">Complete your details to finalize your claim.</p>
                            </div>
                            <form wire:submit="submitFinalDetails" class="mt-6 space-y-4">
                                <div>
                                    <label for="claimName" class="text-sm font-medium text-gray-300">Full
                                        Name</label>
                                    <input wire:model.blur="claimName" type="text" id="claimName"
                                        placeholder="Enter Your Full Name"
                                        class="w-full mt-1 p-3 bg-white rounded-lg text-gray-900">
                                    @error('claimName')
                                        <span class="text-red-600 text-base font-semibold mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="state" class="text-sm font-medium text-gray-300">State</label>
                                        <select wire:model.live="selectedStateId" id="state"
                                            class="w-full mt-1 p-3 bg-white rounded-lg text-gray-900">
                                            <option value="">Select State...</option>
                                            @foreach ($states as $state)
                                                <option wire:key="state-{{ $state['id'] }}"
                                                    value="{{ $state['id'] }}">{{ $state['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedStateId')
                                            <span
                                                class="text-red-600 text-base font-semibold mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="city" class="text-sm font-medium text-gray-300">City</label>
                                        <select wire:model.live="selectedCityId" id="city"
                                            class="w-full mt-1 p-3 bg-white rounded-lg text-gray-900"
                                            @if ($cities->isEmpty()) disabled @endif>
                                            <option value="">Select City...</option>
                                            @foreach ($cities as $city)
                                                <option wire:key="city-{{ $city->id }}"
                                                    value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedCityId')
                                            <span
                                                class="text-red-600 text-base font-semibold mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div x-data @agent-list-updated.window="$refs.agentSelect.value = ''">
                                    <label for="agentSelect" class="text-sm font-medium text-gray-300">Agent
                                        Collection Point</label>
                                    <select x-ref="agentSelect" id="agentSelect" wire:model.live="selectedAgentId"
                                        size="8"
                                        class="w-full text-lg p-3 bg-white text-gray-900 border rounded-lg max-h-[40vh]">
                                        @forelse ($agents as $agent)
                                            <option wire:key="agent-{{ $agent['id'] }}"
                                                value="{{ $agent['id'] }}">
                                                {{ $agent['shop_name'] }} ({{ $agent['contact_person'] }}) -
                                                {{ $agent['location'] }}, {{ $agent['city'] }},
                                                {{ $agent['state'] }}
                                            </option>
                                        @empty
                                            <option value="" disabled>
                                                {{ $selectedStateId ? 'No agents found in this state.' : 'Please select a state first.' }}
                                            </option>
                                        @endforelse
                                    </select>
                                    @error('selectedAgentId')
                                        <span
                                            class="text-red-600 text-base font-semibold mt-2 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="w-full px-4 py-3 font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Confirm and Claim My Prize!
                                </button>
                            </form>
                        </div>
                    </div>


                    {{-- Thank You --}}
                    <div x-show="$wire.currentState === 'thankYou'"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="fixed inset-0 z-30 flex p-2 sm:p-6 bg-black/80 backdrop-blur-xl overflow-y-auto">
                        <div x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="relative mx-auto my-auto w-[min(92vw,600px)] max-h-[95svh] overflow-y-auto p-6 sm:p-8 text-center bg-gray-900/90 rounded-2xl border-t-4 border-green-500 shadow-2xl">

                            <svg class="w-16 h-16 mx-auto mb-4 text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Thank You,
                                {{ strtok($claimName, ' ') }}!</h2>

                            <p class="mt-4 text-lg text-gray-300">Your claim for the</p>
                            <p class="mt-1 text-2xl sm:text-3xl font-bold text-yellow-300 uppercase tracking-wide">
                                {{ $winningPrize }}</p>
                            <p class="mt-1 text-lg text-gray-300">has been recorded.</p>

                            <div class="mt-6 p-4 bg-black/50 rounded-lg border border-gray-600">
                                <p class="text-sm text-gray-400 uppercase tracking-wider">Your Claim ID is:</p>
                                <p class="text-2xl sm:text-3xl font-mono tracking-widest text-white mt-1">
                                    {{ $finalClaimId }}</p>
                            </div>

                            @if ($selectedAgentDetails)
                                <div class="mt-6 text-left p-4 bg-black/50 rounded-lg border border-gray-600">
                                    <p class="text-sm text-gray-400 uppercase tracking-wider text-center">Please
                                        Collect Your Prize From:</p>
                                    <div class="mt-2 text-white">
                                        <p class="font-bold text-lg">{{ $selectedAgentDetails->shop_name }}</p>
                                        <p class="text-gray-300">{{ $selectedAgentDetails->contact_person }}</p>
                                        <p class="text-gray-300">{{ $selectedAgentDetails->location }},
                                            {{ $selectedAgentDetails->city }}, {{ $selectedAgentDetails->state }}
                                        </p>
                                        <p class="text-gray-300">Phone:
                                            {{ $selectedAgentDetails->phone_number_1 }}</p>
                                        <p>Timing: 11:00 AM to 7:00 PM</p>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-8 border border-gray-700 p-6">
                                <a href="https://bharathbeverages.com" target="_blank"
                                    class="text-indigo-400 hover:text-indigo-300 hover:underline transition-colors text-lg">
                                    Visit our website to view all our products!
                                </a>
                            </div>

                            <div class="mt-6 text-center text-sm text-gray-400 border-t border-gray-700 pt-4">
                                <p>For enquiries, call our hotline:</p>
                                <p class="text-lg font-bold text-white">8985303030</p>
                                <p>(11:00 AM to 7:00 PM)</p>
                            </div>

                            {{-- Share Button --}}
                            <div class="mt-6">
                                <button
                                    @click="shareClaim(@js($winningPrize), @js($finalClaimId), @js($selectedAgentDetails))"
                                    class="inline-flex items-center gap-2 px-6 py-3 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                    </svg>
                                    Share Your Win
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- Error --}}
                    <div x-show="$wire.currentState === 'error'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        class="fixed inset-0 z-30 flex p-2 sm:p-6 bg-black/80 backdrop-blur-xl overflow-y-auto">
                        <div x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="relative mx-auto my-auto w-[min(92vw,600px)] max-h-[88svh] overflow-y-auto p-10 sm:p-12 text-center bg-gray-900/90 rounded-2xl border-t-4 border-red-500 shadow-2xl">
                            <svg class="w-20 h-20 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h2 class="text-5xl sm:text-6xl font-extrabold text-white">Oops!</h2>
                            <p class="mt-4 text-xl sm:text-2xl text-gray-200">{{ $errorMessage }}</p>
                        </div>
                    </div>

                    {{-- Better Luck Next Time --}}
                    <div x-show="$wire.currentState === 'betterLuck'"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="fixed inset-0 z-30 flex p-2 sm:p-6 bg-black/80 backdrop-blur-xl overflow-y-auto">
                        <div x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="relative mx-auto my-auto w-[min(92vw,600px)] max-h-[88svh] overflow-y-auto p-10 sm:p-12 text-center bg-gray-900/90 rounded-2xl border-t-4 border-blue-500 shadow-2xl">

                            {{-- Winking Face Icon --}}
                            <svg class="w-20 h-20 mx-auto mb-4 text-yellow-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 11h-.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.5 9.5l-1 1 1 1"></path>
                            </svg>

                            <h2 class="text-5xl sm:text-6xl font-extrabold text-white">So Close!</h2>
                            <p class="mt-4 text-xl sm:text-2xl text-gray-200">
                                While a prize wasn't won this time, every spin is a new opportunity to win big!
                            </p>
                            <p class="mt-8 text-base text-gray-400 border-t border-gray-700 pt-6">
                                Thank you for participating. Enjoy your purchase and we hope to see you spin again
                                soon!
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!--<svg width="0" height="0" style="position:absolute"><defs>-->
<!--  <symbol id="chev" viewBox="0 0 20 20" fill="currentColor">-->
<!--    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>-->
<!--  </symbol>-->
<!--</defs></svg>-->

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script>
        // iOS/Safari-safe viewport height
        (function() {
            const setAppHeight = () => {
                // Use innerHeight to avoid address bar jitter on iOS
                document.documentElement.style.setProperty('--app-h', `${window.innerHeight}px`);
            };
            setAppHeight();

            // Recompute on resize/orientation changes
            window.addEventListener('resize', setAppHeight, {
                passive: true
            });
            window.addEventListener('orientationchange', setAppHeight, {
                passive: true
            });

            // Optional: after keyboard hide/show on iOS
            window.addEventListener('focusout', () => setTimeout(setAppHeight, 50), {
                passive: true
            });
        })();

        function spinWheelManager() {
            return {
                isSpinning: false,
                prizes: [],
                init() {
                    this.initWheelData(@json($initialPrizes));
                    // Watch for the 'thankYou' state to play the win sound
                    this.$watch('$wire.currentState', (newState) => {
                        if (newState === 'thankYou') {
                            const winSound = document.getElementById('win-sound');
                            if (winSound) {
                                winSound.currentTime = 0;
                                try {
                                    winSound.play();
                                } catch (e) {}
                            }
                        }
                        if (newState === 'claim') {
                            // Capture location as soon as we enter claim
                            this.getUserLocation();
                        }
                    });
                },
                initWheelData(initialPrizes) {
                    this.prizes = initialPrizes;
                    this.$nextTick(() => {
                        // First, draw the wheel with the prizes in their original order
                        this.drawWheel(this.prizes);

                        // Prefer to point to 'Gold Coin'; if absent, try a bumper; else first slice.
                        const preferredTargets = ['Gold Coin', 'Smart TV', 'Smart Phone'];
                        let targetIndex = -1;
                        for (const name of preferredTargets) {
                            const idx = this.prizes.findIndex(p => p.toLowerCase() === name.toLowerCase());
                            if (idx !== -1) {
                                targetIndex = idx;
                                break;
                            }
                        }
                        if (targetIndex === -1) targetIndex = 0; // safe fallback

                        const wheelContainer = document.getElementById('wheel-container');

                        if (targetIndex !== -1 && wheelContainer) {
                            const sliceAngle = 360 / this.prizes.length;
                            // Calculate the angle to the center of the target slice
                            const targetAngle = (targetIndex * sliceAngle) + (sliceAngle / 2);
                            // The rotation needed to bring that slice to the top (under the pointer)
                            const initialRotation = -targetAngle;

                            // Apply the rotation instantly without any animation
                            wheelContainer.style.transition = 'none';
                            wheelContainer.style.transform = `rotate(${initialRotation}deg)`;
                        }
                    });
                },
                getUserLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((position) => {
                            this.$wire.set('latitude', position.coords.latitude);
                            this.$wire.set('longitude', position.coords.longitude);
                        }, (error) => {
                            console.error("Geolocation error: " + error.message);
                        });
                    } else {
                        console.log("Geolocation is not supported by this browser.");
                    }
                },
                handleSpinClick() {
                    if (this.isSpinning) return;
                    this.isSpinning = true;
                    this.$wire.spinWheel();
                },
                startSpin(detail) {
                    const {
                        wheelPrizes,
                        winningPrizeName,
                        isWinner
                    } = detail;
                    const wheelContainer = document.getElementById('wheel-container');
                    if (!wheelContainer || this.prizes.length === 0) return;
                    this.drawWheel(wheelPrizes);
                    const winnerIndex = wheelPrizes.indexOf(winningPrizeName);
                    if (winnerIndex === -1) {
                        this.isSpinning = false;
                        return;
                    }
                    const tickSound = document.getElementById('tick-sound');
                    const tickInterval = setInterval(() => {
                        try {
                            tickSound.currentTime = 0;
                            tickSound.play();
                        } catch (e) {}
                    }, 300);
                    const degreesPerSlice = 360 / wheelPrizes.length;
                    const randomOffset = (Math.random() - 0.5) * degreesPerSlice * 0.8;
                    const prizeAngle = (winnerIndex * degreesPerSlice) + (degreesPerSlice / 2) + randomOffset;
                    const targetRotation = 360 - prizeAngle;
                    const extraSpins = 12 * 360;
                    const totalRotation = extraSpins + targetRotation;
                    wheelContainer.style.transition = 'none';
                    wheelContainer.style.transform = 'rotate(0deg)';
                    requestAnimationFrame(() => {
                        wheelContainer.style.transition = `transform 8000ms cubic-bezier(0.25, 1, 0.5, 1)`;
                        wheelContainer.style.transform = `rotate(${totalRotation}deg)`;
                    });
                    wheelContainer.addEventListener('transitionend', () => {
                        clearInterval(tickInterval);
                        if (isWinner) {
                            try {
                                document.getElementById('confetti-sound').play();
                            } catch (e) {}
                            this.fireConfetti();
                        }
                        setTimeout(() => {
                            this.$wire.afterSpinAnimation();
                        }, 3000);
                        // this.isSpinning = false;
                    }, {
                        once: true
                    });
                },
                fireConfetti() {
                    document.getElementById('confetti-sound').play();
                    const duration = 3 * 1000;
                    const animationEnd = Date.now() + duration;
                    const defaults = {
                        startVelocity: 30,
                        spread: 360,
                        ticks: 60,
                        zIndex: 100
                    };
                    const randomInRange = (min, max) => Math.random() * (max - min) + min;
                    const interval = setInterval(() => {
                        const timeLeft = animationEnd - Date.now();
                        if (timeLeft <= 0) return clearInterval(interval);
                        const particleCount = 50 * (timeLeft / duration);
                        confetti({
                            ...defaults,
                            particleCount,
                            origin: {
                                x: randomInRange(0.1, 0.3),
                                y: Math.random() - 0.2
                            }
                        });
                        confetti({
                            ...defaults,
                            particleCount,
                            origin: {
                                x: randomInRange(0.7, 0.9),
                                y: Math.random() - 0.2
                            }
                        });
                    }, 250);
                },
                shareClaim(prize, claimId, agent) {
                    const hotline = '8985303030';
                    const timings = '11 AM - 7 PM';

                    if (!prize || !claimId || !agent) {
                        alert('Claim details not available for sharing.');
                        return;
                    }

                    const shareText = `🎉 I won a ${prize} in the Consumer Dhamaka!\n\n` +
                        `My Claim ID: ${claimId}\n\n` +
                        `Collection Point:\n` +
                        `${agent.shop_name} (${agent.contact_person})\n` +
                        `${agent.location}, ${agent.city}, ${agent.state}\n` +
                        `Phone: ${agent.phone_number_1}\n\n` +
                        `For more help, call our hotline ${hotline}\n` +
                        `Timing: ${timings}`;

                    if (navigator.share) {
                        navigator.share({
                            title: 'I Won a Prize!',
                            text: shareText,
                        }).catch(console.error);
                    } else {
                        alert('Sharing is not supported on this browser. Please copy the details manually.');
                    }
                },
                drawWheel(prizes) {
                    const svgNS = "http://www.w3.org/2000/svg";
                    const wheelEl = document.getElementById('wheel-svg');
                    if (!wheelEl) return;
                    wheelEl.innerHTML = '';

                    const numSlices = prizes.length;
                    if (numSlices === 0) return;

                    const sliceAngle = 360 / numSlices;
                    const radius = 200;
                    const center = 200;

                    const defs = document.createElementNS(svgNS, 'defs');

                    const colorSliceGradient = document.createElementNS(svgNS, 'linearGradient');
                    colorSliceGradient.setAttribute('id', 'colorSliceGrad');
                    colorSliceGradient.setAttribute('gradientUnits', 'userSpaceOnUse');
                    colorSliceGradient.setAttribute('x1', '0');
                    colorSliceGradient.setAttribute('y1', '0');
                    colorSliceGradient.setAttribute('x2', '400');
                    colorSliceGradient.setAttribute('y2', '400');
                    colorSliceGradient.setAttribute('gradientTransform', 'rotate(-45)');
                    colorSliceGradient.innerHTML = `
                    <stop offset="25%" stop-color="#f72585" />
                    <stop offset="50%" stop-color="#3a0ca3" />
                    <stop offset="75%" stop-color="#4cc9f0" />
                `;
                    defs.appendChild(colorSliceGradient);
                    wheelEl.appendChild(defs);

                    // 3D Borders
                    const outerBorderGradient = document.createElementNS(svgNS, 'radialGradient');
                    outerBorderGradient.setAttribute('id', 'outerBorderGrad');
                    outerBorderGradient.innerHTML = `
                    <stop offset="50%" stop-color="#19279e" />
                    <stop offset="100%" stop-color="#2a55ef" />
                `;
                    defs.appendChild(outerBorderGradient);

                    // 3D Outer Border Effect with increased thickness
                    const outerBorderShadow = document.createElementNS(svgNS, 'circle');
                    outerBorderShadow.setAttribute('cx', center);
                    outerBorderShadow.setAttribute('cy', center);
                    outerBorderShadow.setAttribute('r', radius + 22);
                    outerBorderShadow.setAttribute('fill', '#16287d');
                    wheelEl.appendChild(outerBorderShadow);

                    const outerBorder = document.createElementNS(svgNS, 'circle');
                    outerBorder.setAttribute('cx', center);
                    outerBorder.setAttribute('cy', center);
                    outerBorder.setAttribute('r', radius + 20);
                    outerBorder.setAttribute('fill', 'url(#outerBorderGrad)'); // Apply the new gradient
                    wheelEl.appendChild(outerBorder);

                    const outerBorderHighlight = document.createElementNS(svgNS, 'circle');
                    outerBorderHighlight.setAttribute('cx', center);
                    outerBorderHighlight.setAttribute('cy', center);
                    outerBorderHighlight.setAttribute('r', radius + 18);
                    outerBorderHighlight.setAttribute('fill', 'none');
                    outerBorderHighlight.setAttribute('stroke', 'rgba(255, 255, 255, 0.3)');
                    outerBorderHighlight.setAttribute('stroke-width', '2');
                    wheelEl.appendChild(outerBorderHighlight);

                    // Inner Golden Border
                    const innerGoldenLine = document.createElementNS(svgNS, 'circle');
                    innerGoldenLine.setAttribute('cx', center);
                    innerGoldenLine.setAttribute('cy', center);
                    innerGoldenLine.setAttribute('r', radius + 2); // Positioned just outside the slices
                    innerGoldenLine.setAttribute('fill', 'none');
                    innerGoldenLine.setAttribute('stroke', '#FDE047'); // Bright gold color
                    innerGoldenLine.setAttribute('stroke-width', '3');
                    wheelEl.appendChild(innerGoldenLine);

                    // Draw Slices
                    for (let i = 0; i < numSlices; i++) {
                        const isWhiteSlice = i % 2 !== 0;
                        const path = document.createElementNS(svgNS, "path");
                        path.setAttribute('d', this.getSlicePath(i, sliceAngle, radius, center));
                        path.setAttribute('fill', isWhiteSlice ? '#FFFFFF' : 'url(#colorSliceGrad)');
                        path.setAttribute('stroke', '#E5E7EB');
                        path.setAttribute('stroke-width', '1');
                        wheelEl.appendChild(path);
                    }

                    // UPDATED: Logic for tangential text, aligned to the outer border
                    for (let i = 0; i < numSlices; i++) {
                        const isWhiteSlice = i % 2 !== 0;
                        const middleAngle = (i * sliceAngle) + (sliceAngle / 2);

                        const textAngle = middleAngle + 90;

                        const angleRad = (middleAngle - 90) * (Math.PI / 180);

                        // --- Define Text Metrics ---
                        const fontSize = 15;
                        const lineHeight = 18;

                        const textRadius = radius * 0.90;

                        const x = center + textRadius * Math.cos(angleRad);
                        const y = center + textRadius * Math.sin(angleRad);

                        const text = document.createElementNS(svgNS, "text");
                        text.setAttribute('x', x);
                        text.setAttribute('y', y);
                        text.setAttribute('transform', `rotate(${textAngle}, ${x}, ${y})`);
                        text.setAttribute('font-size', `${fontSize}px`);
                        text.setAttribute('font-weight', '600');
                        text.setAttribute('text-anchor', 'start');
                        text.setAttribute('fill', isWhiteSlice ? '#000000' : '#FFFFFF');

                        // --- Text Wrapping Logic ---
                        const words = prizes[i].split(' ');
                        const lines = [];
                        const maxCharsPerLine = 12;

                        if (words.length === 1) {
                            lines.push(words[0]);
                        } else if (words.length === 2) {
                            lines.push(words[0]);
                            lines.push(words[1]);
                        } else if (words.length === 3) {
                            lines.push(words[0]);
                            lines.push(words[1] + ' ' + words[2]);
                        } else {
                            lines.push(words[0] + ' ' + words[1]);
                            lines.push(words.slice(2).join(' '));
                        }

                        const startY = -((lines.length - 1) * lineHeight) / 2;

                        lines.forEach((line, lineIndex) => {
                            const tspan = document.createElementNS(svgNS, "tspan");
                            tspan.setAttribute('x', x);
                            tspan.setAttribute('dy', lineIndex === 0 ? `${startY}px` : `${lineHeight}px`);
                            tspan.textContent = line;
                            text.appendChild(tspan);
                        });

                        wheelEl.appendChild(text);
                    }
                },

                getSlicePath(i, sliceAngle, radius, center) {
                    const startAngle = i * sliceAngle - 90;
                    const endAngle = startAngle + sliceAngle;
                    const startRad = startAngle * (Math.PI / 180);
                    const endRad = endAngle * (Math.PI / 180);
                    const x1 = center + radius * Math.cos(startRad);
                    const y1 = center + radius * Math.sin(startRad);
                    const x2 = center + radius * Math.cos(endRad);
                    const y2 = center + radius * Math.sin(endRad);
                    return `M ${center},${center} L ${x1},${y1} A ${radius},${radius} 0 0,1 ${x2},${y2} z`;
                },

                // getTextPath now ensures text is always upright by reversing the path on the left side
                getTextPath(i, sliceAngle, textRadius, wheelRadius, center) {
                    const middleAngle = (i * sliceAngle) + (sliceAngle / 2);
                    const textRad = (middleAngle - 90) * (Math.PI / 180);

                    // Path starts at the given textRadius and extends outwards
                    const startRadius = textRadius;
                    const endRadius = wheelRadius - 10;

                    const textPathStartX = center + startRadius * Math.cos(textRad);
                    const textPathStartY = center + startRadius * Math.sin(textRad);
                    const textPathEndX = center + endRadius * Math.cos(textRad);
                    const textPathEndY = center + endRadius * Math.sin(textRad);

                    // Check if the text will be upside down and reverse the path if so
                    if (middleAngle > 90 && middleAngle < 270) {
                        return `M${textPathEndX},${textPathEndY} L${textPathStartX},${textPathStartY}`;
                    } else {
                        return `M${textPathStartX},${textPathStartY} L${textPathEndX},${textPathEndY}`;
                    }
                }
            }
        }
    </script>
@endpush
