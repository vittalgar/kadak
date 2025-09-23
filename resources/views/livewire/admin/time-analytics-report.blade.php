<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Time-Based Analytics Report</h1>

    <div class="mb-8 max-w-sm">
        <label for="campaignFilter" class="block text-sm font-medium text-fg-soft">Filter by Campaign</label>
        <select id="campaignFilter" wire:model.live="selectedCampaignId"
            class="mt-1 block w-full form-select rounded-md shadow-sm bg-bkg-soft border-border text-fg">
            <option value="">All Campaigns</option>
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
            @endforeach
        </select>
    </div>

    <p class="text-fg-soft mb-8">
        Analyze when your customers are most active to identify peak engagement times and weekly trends.
    </p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-bkg-alt border border-border rounded-lg shadow-sm p-6 h-96">
            @if ($this->chart1)
                <x-chartjs-component :chart="$this->chart1" />
            @else
                <div class="flex items-center justify-center h-full">
                    <p class="text-fg-soft">No data available for this selection.</p>
                </div>
            @endif
        </div>

        <div class="bg-bkg-alt border border-border rounded-lg shadow-sm p-6 h-96">
            @if ($this->chart2)
                <x-chartjs-component :chart="$this->chart2" />
            @else
                <div class="flex items-center justify-center h-full">
                    <p class="text-fg-soft">No data available for this selection.</p>
                </div>
            @endif
        </div>
    </div>
</div>
