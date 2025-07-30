<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-white">Retail Shops for: {{ $dealer->dealership_name }}</h1>
            <p class="mt-1 text-sm text-gray-400">Manage collection points for this dealer.</p>
        </div><a href="{{ route('dealers.index') }}"
            class="text-sm font-medium text-indigo-400 hover:text-indigo-300">&larr; Back to All Dealers</a>
    </div>
    @if (session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/30 text-green-300 rounded-lg">{{ session('success') }}
        </div>
    @endif
    @if (session('import_errors'))
        <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-300 rounded-lg">
            <p class="font-bold">Import failed:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="bg-[#2a3042] shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">{{ $isEditing ? 'Edit Shop' : 'Add New Shop' }}</h2>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div><label for="shop_name" class="block text-sm font-medium text-gray-300">Shop Name</label><input
                        wire:model="form.shop_name" type="text" id="shop_name"
                        class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('form.shop_name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div><label for="address" class="block text-sm font-medium text-gray-300">Address</label>
                    <textarea wire:model="form.address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('form.address')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
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
                    <div><label for="pincode" class="block text-sm font-medium text-gray-300">Pincode</label><input
                            wire:model="form.pincode" type="text" id="pincode"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900/50 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('form.pincode')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div><button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ $isEditing ? 'Save Changes' : 'Add Shop' }}</button>
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
            <h2 class="text-xl font-semibold text-white">Bulk Import Retail Shops</h2>
            <p class="mt-1 text-sm text-gray-400">Upload a CSV/Excel file. Columns: `shop_name`, `address`, `city`,
                `state`, `pincode`.</p>
            <form wire:submit="importShops" class="mt-4">
                <div class="flex items-center space-x-4"><input wire:model="upload" type="file" id="shop_upload"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/10 file:text-indigo-300 hover:file:bg-indigo-500/20" /><button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
                        wire:loading.attr="disabled" wire:target="upload"><span wire:loading.remove
                            wire:target="importShops,upload">Upload & Import</span><span wire:loading
                            wire:target="importShops,upload">Importing...</span></button></div>
                @error('upload')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </div>
    <div class="mt-8 bg-[#2a3042] shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold text-white">Existing Shops for {{ $dealer->dealership_name }}</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Shop
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">City
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">State
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Pincode</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-[#2a3042] divide-y divide-gray-700">
                    @forelse ($shops as $shop)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                {{ $shop->shop_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $shop->address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $shop->city }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $shop->state }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $shop->pincode }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4"><button
                                    wire:click="edit({{ $shop->id }})"
                                    class="text-indigo-400 hover:text-indigo-300">Edit</button><button
                                    wire:click="delete({{ $shop->id }})" wire:confirm="Are you sure?"
                                    class="text-red-400 hover:text-red-300">Delete</button></td>
                    </tr>@empty<tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400">No retail shops have
                                been added for this dealer yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $shops->links() }}</div>
    </div>
</div>
