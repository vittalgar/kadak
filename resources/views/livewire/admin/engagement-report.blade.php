<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">QR Code Engagement Report</h1>
    <div class="mb-6">
        <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Select a Campaign to Analyze</label>
        <select id="campaignFilter" wire:model.live="selectedCampaignId"
            class="mt-1 block w-full md:w-1/3 form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
            @endforeach
        </select>
    </div>

    @if ($selectedCampaignId)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div class="bg-bkg-alt border border-border rounded-lg p-6">
                <h3 class="text-lg font-semibold text-fg-alt">Generated</h3>
                <p class="text-5xl font-bold text-primary mt-2">{{ number_format($totalGenerated) }}</p>
                <p class="text-sm text-fg-soft">Total QR Codes</p>
            </div>

            <div class="bg-bkg-alt border border-border rounded-lg p-6">
                <div class="flex items-center justify-center text-fg-soft">
                    <div class="w-full h-px bg-border"></div>
                    <div class="mx-4 font-semibold">{{ number_format($scanRate, 1) }}%</div>
                    <div class="w-full h-px bg-border"></div>
                </div>
                <h3 class="text-lg font-semibold text-fg-alt">Scanned</h3>
                <p class="text-5xl font-bold text-primary mt-2">{{ number_format($totalScanned) }}</p>
                <p class="text-sm text-fg-soft">Conversion from Generated</p>
            </div>

            <div class="bg-bkg-alt border border-border rounded-lg p-6">
                <div class="flex items-center justify-center text-fg-soft">
                    <div class="w-full h-px bg-border"></div>
                    <div class="mx-4 font-semibold">{{ number_format($claimRate, 1) }}%</div>
                    <div class="w-full h-px bg-border"></div>
                </div>
                <h3 class="text-lg font-semibold text-fg-alt">Claimed</h3>
                <p class="text-5xl font-bold text-success mt-2">{{ number_format($totalClaimed) }}</p>
                <p class="text-sm text-fg-soft">Conversion from Scanned</p>
            </div>
        </div>
    @else
        <div class="p-6 bg-bkg-alt border border-border rounded-lg shadow-sm text-center text-fg-soft">
            <p>Please select a campaign to view its engagement report.</p>
        </div>
    @endif
</div>
