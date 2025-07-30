<div>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6">
            <p class="text-gray-600 dark:text-gray-400">
                This map displays a real-time visualization of all prize claims where location data was successfully
                captured. Use it to identify campaign hotspots and analyze your market penetration.
            </p>
        </div>
    </div>

    <!-- Map Container -->
    <div class="mt-6 h-[600px] w-full bg-gray-200 dark:bg-gray-700 rounded-lg shadow-md" wire:ignore>
        <div id="map" class="h-full w-full rounded-lg"></div>
    </div>

    <!-- Add Leaflet JS to the page footer -->
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            // Use a standard DOMContentLoaded listener for maximum reliability
            document.addEventListener('DOMContentLoaded', () => {
                // Get the data passed from the Livewire component
                const claimsData = JSON.parse(@json($claims));

                // Initialize the map
                const map = L.map('map').setView([20.5937, 78.9629], 5);

                // Add the tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add markers for each claim
                claimsData.forEach(claim => {
                    const nameParts = claim.name.split(' ');
                    const firstName = nameParts[0];
                    const lastNameInitial = nameParts.length > 1 ? ` ${nameParts[1].charAt(0)}.` : '';
                    const maskedName = firstName + lastNameInitial;

                    const popupContent = `
                        <div class="font-sans">
                            <div class="font-bold text-base">${maskedName}</div>
                            <div class="text-gray-600">${claim.city}</div>
                            <div class="mt-2">Won a <strong>${claim.prize_won}</strong>!</div>
                        </div>
                    `;

                    L.marker([claim.latitude, claim.longitude])
                        .addTo(map)
                        .bindPopup(popupContent);
                });

                // THE FIX: Force the map to recalculate its size after the page has loaded
                setTimeout(() => {
                    map.invalidateSize();
                }, 150);
            });
        </script>
    @endpush
</div>
