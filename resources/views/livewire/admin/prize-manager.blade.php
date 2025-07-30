<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Prize Manager</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Managing the complete prize pool for campaign:
                <strong class="text-gray-700 dark:text-gray-200">{{ $campaign->name }}</strong>
            </p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
            &larr; Back to All Campaigns
        </a>
    </div>

    <!-- Dismissible Success & Error Messages -->
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
        @if (session('success'))
            <div class="relative rounded-lg bg-green-100 dark:bg-green-900/20 p-4 border border-green-400/30">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button @click="show = false" type="button"
                                class="inline-flex rounded-md bg-green-100 dark:bg-green-900/20 p-1.5 text-green-500 hover:bg-green-200 dark:hover:bg-green-900/30 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (session('import_errors'))
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <p class="font-bold">Import failed due to the following errors:</p>
                <ul class="list-disc list-inside mt-2">
                    @foreach (session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <!-- Add / Edit Prize Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $isEditing ? 'Edit Prize Details' : 'Add New Prize' }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Use this form to manually add or modify a
                single prize. Ensure stock and weight values are set correctly to manage the campaign's prize
                distribution.</p>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prize
                        Name</label>
                    <input wire:model="form.name" type="text" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('form.name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="category"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select wire:model="form.category" id="category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option>Common</option>
                        <option>Mid-Value</option>
                        <option>High-Value</option>
                        <option>Grand</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="total_stock"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Stock</label>
                        <input wire:model="form.total_stock" type="number" id="total_stock"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('form.total_stock')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="remaining_stock"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remaining
                            Stock</label>
                        <input wire:model="form.remaining_stock" type="number" id="remaining_stock"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('form.remaining_stock')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight
                        (Probability)</label>
                    <input wire:model="form.weight" type="number" id="weight"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">A higher number means a higher chance
                        to
                        win. E.g., Spoon = 150, Gold Coin = 1.</p>
                    @error('form.weight')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input wire:model="form.is_active" id="is_active" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">Prize is
                            Active</label>
                        <p class="text-gray-500 dark:text-gray-400">Uncheck to temporarily disable this prize from
                            being
                            won.</p>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $isEditing ? 'Save Changes' : 'Add Prize' }}
                    </button>
                    @if ($isEditing)
                        <button wire:click.prevent="cancelEdit" type="button"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:underline ml-4">Cancel</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Import Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Bulk Import Prizes</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Efficiently add multiple prizes by uploading a
                CSV
                or Excel file. This is the recommended method for setting up a new campaign's prize pool.</p>
            <div class="mt-4 p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                <form wire:submit="importPrizes"
                    class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <input wire:model="upload" type="file" id="prize_upload"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
                        wire:loading.attr="disabled" wire:target="upload">
                        <span wire:loading.remove wire:target="importPrizes,upload">Upload & Import</span>
                        <span wire:loading wire:target="importPrizes,upload">Importing...</span>
                    </button>
                </form>
                @error('upload')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Required columns: `name`, `category`,
                    `total_stock`, `weight`. <a href="#"
                        class="font-medium text-indigo-600 hover:underline">Download sample template</a>.</p>
            </div>
        </div>
    </div>

    <!-- List of Existing Prizes -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Prize List for "{{ $campaign->name }}"
            </h2>
            <!-- NEW: Search and Filter Controls -->
            <div class="flex items-center space-x-4">
                <select wire:model.live="filterCategory"
                    class="block w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Categories</option>
                    <option>Common</option>
                    <option>Mid-Value</option>
                    <option>High-Value</option>
                    <option>Grand</option>
                </select>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search prizes..."
                    class="block w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Category</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Stock (Rem. / Total)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Weight</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($prizes as $prize)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $prize->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $prize->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($prize->remaining_stock) }} /
                                {{ number_format($prize->total_stock) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $prize->weight }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($prize->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                <button wire:click="edit({{ $prize->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</button>
                                <button wire:click="delete({{ $prize->id }})"
                                    wire:confirm="Are you sure you want to delete this prize?"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No prizes found for your search or filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $prizes->links() }}
        </div>
    </div>
</div>
