<div
    class="flex flex-col items-center justify-center min-h-screen px-4 py-8 bg-gradient-to-br from-gray-700 via-gray-900 to-black text-white">

    <!-- Screen 1: Product Selection -->
    @if ($currentState === 'product_selection')
        <div class="w-full max-w-md text-center animate-fade-in">
            <img src="/images/logo.png" alt="Company Logo" class="w-24 h-auto mx-auto mb-6">
            <h1 class="text-3xl font-bold mb-4">Welcome to {{ $campaign->name ?? 'Project Kadak' }}!</h1>
            <p class="text-lg text-gray-300 mb-8">Please select the product you purchased to begin.</p>
            <form wire:submit="selectProduct" class="space-y-4">
                <select wire:model="selectedProductId"
                    class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select a Product...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('selectedProductId')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                <button type="submit"
                    class="w-full px-4 py-3 font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-300">Continue</button>
            </form>
        </div>
    @endif

    <!-- Screen 2: Spin the Wheel -->
    @if ($currentState === 'spin')
        <div class="w-full max-w-md text-center animate-fade-in">
            <img src="/images/logo.png" alt="Company Logo" class="w-24 h-auto mx-auto mb-6">
            <h1 class="text-3xl font-bold mb-4">{{ $campaign->name ?? 'Spin to Win!' }}</h1>
            <div class="relative w-full max-w-xs sm:max-w-sm mx-auto my-8 aspect-square">
                <div class="absolute top-[-15px] left-1/2 -translate-x-1/2 z-10"
                    style="width: 0; height: 0; border-left: 25px solid transparent; border-right: 25px solid transparent; border-top: 40px solid #f1c40f;">
                </div>
                <div id="wheel-container" class="w-full h-full">
                    <svg id="wheel-svg" viewBox="0 0 400 400"
                        class="w-full h-full transition-transform duration-[7000ms] ease-[cubic-bezier(.08,.69,.19,.99)]"></svg>
                </div>
            </div>
            <button id="spinButton" wire:click="spinWheel" wire:loading.attr="disabled"
                class="px-8 py-4 text-xl font-bold text-white bg-red-600 rounded-full hover:bg-red-700 disabled:bg-gray-500 transition-colors duration-300">
                <span wire:loading.remove wire:target="spinWheel">SPIN!</span>
                <span wire:loading wire:target="spinWheel">Spinning...</span>
            </button>
        </div>
    @endif

    <!-- Screen 3: Claim Form -->
    @if ($currentState === 'claim')
        <div class="w-full max-w-md text-center animate-fade-in">
            <h1 class="text-2xl font-bold mb-2">Congratulations!</h1>
            <p class="text-xl text-yellow-400 mb-6">You've won a <span class="font-bold">{{ $winningPrize }}</span>!</p>
            <form wire:submit="submitClaim" class="space-y-4 text-left p-6 bg-gray-800 rounded-lg">
                <p class="text-center text-gray-300 mb-4">Enter your details below to claim your prize:</p>
                <div><input wire:model="claimName" type="text" placeholder="Enter Your Full Name"
                        class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white" required></div>
                <div><input wire:model="claimMobile" type="tel" placeholder="Enter Your Mobile Number"
                        class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white" required></div>
                <div><input wire:model="claimCity" type="text" placeholder="Enter Your City"
                        class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white" required></div>
                <button type="submit"
                    class="w-full px-4 py-3 font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-300">
                    <span wire:loading.remove wire:target="submitClaim">CLAIM MY PRIZE!</span>
                    <span wire:loading wire:target="submitClaim">Submitting...</span>
                </button>
            </form>
        </div>
    @endif

    <!-- Screen 4: Thank You -->
    @if ($currentState === 'thank_you')
        <div class="w-full max-w-md text-center p-8 bg-gray-800 rounded-lg animate-fade-in">
            <svg class="w-16 h-16 mx-auto mb-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h1 class="text-2xl font-bold mb-2">Thank You, {{ strtok($claimName, ' ') }}!</h1>
            <p class="text-gray-300 mb-4">Your claim for the **{{ $winningPrize }}** has been recorded.</p>
            <div class="p-4 bg-gray-900 rounded-lg">
                <p class="text-sm text-gray-400">Your Claim ID is:</p>
                <p class="text-2xl font-mono tracking-widest">{{ $finalClaimId }}</p>
            </div>
            <p class="text-sm text-gray-400 mt-4">You will receive an SMS with these details shortly.</p>
        </div>
    @endif

    <!-- Screen 5: Error -->
    @if ($currentState === 'error')
        <div class="w-full max-w-md text-center p-8 bg-gray-800 rounded-lg animate-fade-in">
            <svg class="w-16 h-16 mx-auto mb-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h1 class="text-2xl font-bold mb-2">Oops! There was an issue.</h1>
            <p class="text-gray-300">{{ $errorMessage }}</p>
            <a href="/"
                class="inline-block mt-6 px-6 py-2 font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Back
                to Homepage</a>
        </div>
    @endif

</div>

@push('scripts')
    <script>
        // These functions are now global and can be called by Livewire
        function drawWheel(prizes) {
            const wheelEl = document.getElementById('wheel-svg');
            if (!wheelEl) return; // Safety check

            wheelEl.innerHTML = '';
            const numPrizes = prizes.length;
            if (numPrizes === 0) return;

            const sliceAngle = 360 / numPrizes;
            const radius = 200;
            const center = 200;
            const baseColors = ["#ff4757", "#ff7f50", "#ff6b81", "#ffa502", "#ffd700", "#fff200", "#a5d6a7", "#4caf50",
                "#009688", "#00bcd4", "#1e90ff", "#4169e1", "#9b59b6", "#8e44ad", "#f368e0", "#ff7979"
            ];

            const defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");
            wheelEl.appendChild(defs);

            for (let i = 0; i < numPrizes; i++) {
                const pathSlice = document.createElementNS("http://www.w3.org/2000/svg", "path");
                pathSlice.setAttribute('d', getSlicePath(i, sliceAngle, radius, center));
                pathSlice.setAttribute('fill', baseColors[i % baseColors.length]);
                wheelEl.appendChild(pathSlice);

                const textPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
                const pathId = `text-path-${i}`;
                textPath.setAttribute('id', pathId);
                textPath.setAttribute('d', getTextPath(i, sliceAngle, radius, center));
                defs.appendChild(textPath);

                const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
                const textPathElement = document.createElementNS("http://www.w3.org/2000/svg", "textPath");
                textPathElement.setAttribute("href", `#${pathId}`);
                textPathElement.setAttribute("startOffset", "50%");
                textPathElement.setAttribute("text-anchor", "middle");
                textPathElement.textContent = prizes[i];
                textPathElement.style.fontSize = '14px';
                textPathElement.style.fontWeight = 'bold';
                textPathElement.style.fill = '#ffffff';
                text.appendChild(textPathElement);
                wheelEl.appendChild(text);
            }
        }

        function getSlicePath(i, sliceAngle, radius, center) {
            const startAngle = i * sliceAngle;
            const endAngle = startAngle + sliceAngle;
            const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
            const startRad = (startAngle - 90) * Math.PI / 180;
            const endRad = (endAngle - 90) * Math.PI / 180;
            const x1 = center + radius * Math.cos(startRad);
            const y1 = center + radius * Math.sin(startRad);
            const x2 = center + radius * Math.cos(endRad);
            const y2 = center + radius * Math.sin(endRad);
            return `M ${center},${center} L ${x1},${y1} A ${radius},${radius} 0 ${largeArcFlag} 1 ${x2},${y2} z`;
        }

        function getTextPath(i, sliceAngle, radius, center) {
            const middleAngle = (i * sliceAngle) + (sliceAngle / 2);
            const textRad = (middleAngle - 90) * Math.PI / 180;
            const startRadius = 40;
            const endRadius = radius - 20;
            const textPathStartX = center + startRadius * Math.cos(textRad);
            const textPathStartY = center + startRadius * Math.sin(textRad);
            const textPathEndX = center + endRadius * Math.cos(textRad);
            const textPathEndY = center + endRadius * Math.sin(textRad);
            return `M${textPathStartX},${textPathStartY} L${textPathEndX},${textPathEndY}`;
        }

        function startSpin(detail) {
            const {
                wheelPrizes,
                winningPrizeName
            } = detail;
            const wheelEl = document.getElementById('wheel-svg');
            const spinButton = document.getElementById('spinButton');
            if (!wheelEl || !spinButton) return;

            spinButton.disabled = true;
            drawWheel(wheelPrizes);

            setTimeout(() => {
                const winningPrizeIndex = wheelPrizes.indexOf(winningPrizeName);
                const sliceAngle = 360 / wheelPrizes.length;
                const prizeAngle = (winningPrizeIndex * sliceAngle) + (sliceAngle / 2);
                const targetRotation = 360 - prizeAngle;
                const fullSpins = 6;
                const totalRotation = (360 * fullSpins) + targetRotation;

                wheelEl.style.transform = `rotate(${totalRotation}deg)`;

                wheelEl.addEventListener('transitionend', () => {
                    @this.set('currentState', 'claim');
                }, {
                    once: true
                });
            }, 100);
        }

        // Listen for the event dispatched from the Livewire component
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('spin-has-been-determined', (event) => {
                startSpin(event[0]); // Access the event data correctly
            });
        });

        // --- THE FIX: Only draw the wheel if we are in the spin state ---
        document.addEventListener('DOMContentLoaded', () => {
            if (@json($currentState) === 'spin') {
                drawWheel(['Prize 1', 'Prize 2', 'Prize 3', 'Prize 4', 'Prize 5', 'Prize 6', 'Prize 7', 'Prize 8',
                    'Prize 9', 'Prize 10', 'Prize 11', 'Prize 12', 'Prize 13', 'Prize 14', 'Prize 15',
                    'Prize 16'
                ]);
            }
        });
    </script>


    <script>
        document.addEventListener('livewire:navigated', () => {
            // This pushes a new state into the browser's history stack.
            history.pushState(null, '', location.href);
            window.onpopstate = function() {
                // When the user clicks the back button, it will just go back to this
                // new state, effectively staying on the same page.
                history.go(1);
            };
        });
    </script>
@endpush
