<div class="space-y-8">
    <h1 class="text-3xl font-bold text-fg-alt mb-6">Product Manager</h1>

    <!-- Add / Edit Product Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $isEditing ? 'Edit Product' : 'Add New Product' }}
            </h2>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product
                            Name</label>
                        <input wire:model="form.name" type="text" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('form.name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU
                            (Optional)</label>
                        <input wire:model="form.sku" type="text" id="sku"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('form.sku')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input wire:model="form.is_active" id="is_active" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">Product is
                            Active</label>
                        <p class="text-gray-500 dark:text-gray-400">Uncheck to hide this product from the customer
                            selection list.</p>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $isEditing ? 'Save Changes' : 'Add Product' }}
                    </button>
                    @if ($isEditing)
                        <button wire:click.prevent="cancelEdit" type="button"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:underline ml-4">Cancel</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- List of Existing Products -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Existing Products</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Product Name</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            SKU</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->sku ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($product->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                <button wire:click="edit({{ $product->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</button>
                                <button wire:click="delete({{ $product->id }})"
                                    wire:confirm="Are you sure you want to delete this product?"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No
                                products have been added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
