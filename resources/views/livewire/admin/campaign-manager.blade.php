<div class="space-y-8">
    @if (session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/30 text-green-300 rounded-lg">{{ session('success') }}
        </div>
    @endif
    <div class="bg-[#2a3042] shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">{{ $isEditing ? 'Edit Campaign' : 'Create New Campaign' }}</h2>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Campaign Name</label>
                    <input wire:model="form.name" type="text" id="name"
                        class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('form.name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300">Description
                        (Optional)</label>
                    <textarea wire:model="form.description" id="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('form.description')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-300">Start Date</label>
                        <input wire:model="form.start_date" type="date" id="startDate"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.start_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-300">End Date</label>
                        <input wire:model="form.end_date" type="date" id="endDate"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.end_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex h-5 items-center"><input wire:model="form.is_active" id="isActive" type="checkbox"
                            class="h-4 w-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500"></div>
                    <div class="ml-3 text-sm"><label for="isActive" class="font-medium text-gray-300">Set as Active
                            Campaign</label></div>
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ $isEditing ? 'Save Changes' : 'Create Campaign' }}</button>
                    @if ($isEditing)
                        <button wire:click.prevent="cancelEdit" type="button"
                            class="text-sm text-gray-400 hover:underline ml-4">Cancel</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="mt-8 bg-[#2a3042] shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold text-white">Existing Campaigns</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date
                            Range</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-[#2a3042] divide-y divide-gray-700">
                    @forelse ($campaigns as $campaign)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $campaign->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($campaign->is_active)
                                    <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500/10 text-green-300">Active</span>@else<span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-500/10 text-gray-300">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $campaign->start_date->format('d M, Y') }} -
                                {{ $campaign->end_date->format('d M, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                <a href="{{ route('campaigns.products', $campaign) }}"
                                    class="text-blue-400 hover:text-blue-300">Attach Products</a>
                                <a href="{{ route('prizes.index', $campaign) }}"
                                    class="text-green-400 hover:text-green-300">Manage Prizes</a>
                                <button wire:click="edit({{ $campaign->id }})"
                                    class="text-indigo-400 hover:text-indigo-300">Edit</button>
                                <button wire:click="delete({{ $campaign->id }})"
                                    wire:confirm="Are you sure you want to delete this campaign and all its prizes?"
                                    class="text-red-400 hover:text-red-300">Delete</button>
                            </td>
                    </tr>@empty<tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-400">No
                                campaigns created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $campaigns->links() }}</div>
    </div>
</div>
