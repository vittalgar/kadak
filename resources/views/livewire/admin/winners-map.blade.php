<div
    x-data="mapManager(@json($claims))"
    x-init="initMap()"
>
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Live Winners Map</h1>

    {{-- THE FIX: The filter is removed and replaced with a dynamic title --}}
    @if ($currentCampaign)
        <p class="mt-2 text-base text-fg-soft">
            Showing winners for the current campaign: <span class="font-bold text-primary">{{ $currentCampaign->name }}</span>
        </p>
    @else
        <p class="mt-2 text-base text-fg-soft">
            There are no active campaigns running at the moment.
        </p>
    @endif
    
    <div class="mt-6 h-[600px] w-full bg-gray-200 dark:bg-gray-700 rounded-lg shadow-md relative z-10" wire:ignore>
        <div id="map" class="h-full w-full rounded-lg"></div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            function mapManager(claimsData) {
                return {
                    map: null,
                    claims: claimsData,
                    initMap() {
                        this.$nextTick(() => {
                            if (this.map) { this.map.remove(); }
                            const container = L.DomUtil.get('map');
                            if (container != null) { container._leaflet_id = null; }

                            const centerCoordinates = this.claims.length > 0 ? [this.claims[0].latitude, this.claims[0].longitude] : [20.5937, 78.9629];
                            const zoomLevel = this.claims.length > 0 ? 10 : 5;

                            const map = L.map('map').setView(centerCoordinates, zoomLevel);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
                            
                            this.claims.forEach(claim => {
                                const popupContent = `<div class="font-sans"><div class="font-bold text-base">${claim.name}</div><div class="text-gray-600">${claim.city}</div><div class="mt-2">Won a <strong>${claim.prize_won}</strong>!</div></div>`;
                                L.marker([claim.latitude, claim.longitude]).addTo(map).bindPopup(popupContent);
                            });
                            this.map = map;
                            setTimeout(() => { this.map.invalidateSize(); }, 150);
                        });
                    }
                }
            }
        </script>
    @endpush
</div>