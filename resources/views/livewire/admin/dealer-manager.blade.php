<div class="space-y-8">
    @if (session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/30 text-green-300 rounded-lg">{{ session('success') }}
        </div>
    @endif
    @if (session('dealer_password'))
        <div class="p-4 bg-blue-500/10 border border-blue-500/30 text-blue-300 rounded-lg"><strong>Important:</strong>
            {{ session('dealer_password') }}</div>
    @endif
    <div class="bg-[#2a3042] shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">{{ $isEditing ? 'Edit Dealer' : 'Onboard New Dealer' }}</h2>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div><label for="dealership_name" class="block text-sm font-medium text-gray-300">Dealership
                            Name</label><input wire:model="form.dealership_name" type="text" id="dealership_name"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.dealership_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div><label for="contact_person" class="block text-sm font-medium text-gray-300">Contact
                            Person</label><input wire:model="form.contact_person" type="text" id="contact_person"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.contact_person')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div><label for="email" class="block text-sm font-medium text-gray-300">Login Email</label><input
                        wire:model="form.email" type="email" id="email"
                        class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('form.email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div><label for="phone_number" class="block text-sm font-medium text-gray-300">Phone (Becomes
                            Password)</label><input wire:model="form.phone_number" type="tel" id="phone_number"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.phone_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div><label for="city" class="block text-sm font-medium text-gray-300">City</label><input
                            wire:model="form.city" type="text" id="city"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.city')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div><label for="state" class="block text-sm font-medium text-gray-300">State</label><input
                            wire:model="form.state" type="text" id="state"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.state')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div><button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ $isEditing ? 'Save Changes' : 'Add Dealer' }}</button>
                    @if ($isEditing)
                        <button wire:click.prevent="cancelEdit" type="button"
                            class="text-sm text-gray-400 hover:underline ml-4">Cancel</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="mt-8 bg-[#2a3042] shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-white">Bulk Import Dealers</h2>
            <p class="mt-1 text-sm text-gray-400">Upload a CSV/Excel file. Columns: `dealership_name`, `contact_person`,
                `email`, `phone_number`, `city`, `state`.</p>
            <form wire:submit="importDealers" class="mt-4">
                <div class="flex items-center space-x-4"><input wire:model="upload" type="file" id="upload"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/10 file:text-indigo-300 hover:file:bg-indigo-500/20" /><button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
                        wire:loading.attr="disabled" wire:target="upload"><span wire:loading.remove
                            wire:target="importDealers,upload">Upload & Import</span><span wire:loading
                            wire:target="importDealers,upload">Importing...</span></button></div>
                @error('upload')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </div>
    <div class="mt-8 bg-[#2a3042] shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold text-white">Existing Dealers</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Dealership</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Login
                            Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Location</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-[#2a3042] divide-y divide-gray-700">
                    @forelse ($dealers as $dealer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                {{ $dealer->dealership_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                <div>{{ $dealer->contact_person }}</div>
                                <div>{{ $dealer->phone_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $dealer->user?->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $dealer->city }},
                                {{ $dealer->state }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4"><a
                                    href="{{ route('shops.index', $dealer) }}"
                                    class="text-green-400 hover:text-green-300">Manage Shops</a><button
                                    wire:click="edit({{ $dealer->id }})"
                                    class="text-indigo-400 hover:text-indigo-300">Edit</button><button
                                    wire:click="delete({{ $dealer->id }})" wire:confirm="Are you sure?"
                                    class="text-red-400 hover:text-red-300">Delete</button></td>
                    </tr>@empty<tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400">No dealers have
                                been added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $dealers->links() }}</div>
    </div>
</div>
