<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Location Performance Report</h1>

    <div class="mb-6 max-w-sm">
        <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Filter by Campaign</label>
        <select id="campaignFilter" wire:model.live="selectedCampaignId"
            class="mt-1 block w-full form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            <option value="">All Campaigns</option>
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- <p class="text-fg-soft mb-6">
        This report shows a breakdown of prize claims by geographical location, helping you identify areas with the
        highest customer engagement.
    </p> --}}

    <div class="space-y-6">
        @forelse ($locationData as $state => $cities)
            <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
                <div class="p-4 border-b border-border">
                    <h2 class="text-xl font-semibold text-fg-alt">{{ $state }}</h2>
                </div>
                <ul class="divide-y divide-border">
                    @foreach ($cities as $city)
                        <li class="flex justify-between items-center p-4 hover:bg-bkg-soft">
                            <span class="font-medium text-fg">{{ $city['city'] }}</span>
                            <span class="font-bold text-primary text-lg">
                                {{ number_format($city['total_claims']) }}
                                <span class="text-sm text-fg-soft font-medium">claims</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm text-center text-fg-soft">
                <p>No location data is available yet.</p>
            </div>
        @endforelse
    </div>
</div>
