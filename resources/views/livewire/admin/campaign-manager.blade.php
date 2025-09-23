<div class="space-y-8">
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Campaign Manager</h1>

    <div class="bg-bkg-alt border border-border rounded-lg shadow-sm">
        <div class="p-6 border-b border-border">
            <h2 class="text-xl font-semibold text-fg-alt">{{ $isEditing ? 'Edit Campaign' : 'Create New Campaign' }}</h2>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-fg-soft">Campaign Name</label>
                    <input wire:model.blur="form.name" type="text" id="name"
                        class="mt-1 block w-full form-input">
                    @error('form.name')
                        <span class="text-danger text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-fg-soft">Description
                        (Optional)</label>
                    <textarea wire:model.blur="form.description" id="description" rows="3" class="mt-1 block w-full form-textarea"></textarea>
                    @error('form.description')
                        <span class="text-danger text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-fg-soft">Start Date</label>
                        <input wire:model.live="form.start_date" type="date" id="startDate"
                            class="mt-1 block w-full form-input">
                        @error('form.start_date')
                            <span class="text-danger text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-fg-soft">End Date</label>
                        <input wire:model.live="form.end_date" type="date" id="endDate"
                            class="mt-1 block w-full form-input">
                        @error('form.end_date')
                            <span class="text-danger text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input wire:model="form.is_active" id="isActive" type="checkbox"
                            class="h-4 w-4 rounded form-checkbox">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="isActive" class="font-medium text-fg-soft">Set as Active Campaign</label>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="bg-primary text-primary-fg font-bold py-2 px-4 rounded-lg hover:opacity-90">{{ $isEditing ? 'Save Changes' : 'Create Campaign' }}</button>
                    @if ($isEditing)
                        <button wire:click.prevent="cancelEdit" type="button"
                            class="text-sm text-fg-soft hover:underline ml-4">Cancel</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="bg-bkg-alt border border-border rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-fg-alt">Existing Campaigns</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date Range</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($campaigns as $campaign)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $campaign->name }}</td>
                            <td class="px-6 py-4">
                                @if ($campaign->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/20 text-success">Active</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-500/20 text-fg-soft">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $campaign->start_date->format('d M, Y') }} -
                                {{ $campaign->end_date->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-4">
                                <a href="{{ route('admin.campaigns.products', $campaign) }}"
                                    class="text-blue-500 hover:underline">Attach Products</a>
                                <a href="{{ route('admin.prizes.index', $campaign) }}"
                                    class="text-green-500 hover:underline">Manage Prizes</a>
                                <button wire:click="edit({{ $campaign->id }})"
                                    class="text-primary hover:underline">Edit</button>
                                <button wire:click="delete({{ $campaign->id }})"
                                    wire:confirm="Are you sure you want to delete this campaign and all its prizes?"
                                    class="text-danger hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-fg-soft">No campaigns created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $campaigns->links() }}</div>
    </div>
</div>
