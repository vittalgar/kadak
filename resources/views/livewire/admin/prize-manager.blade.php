<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-2">Prize Manager</h1>
    <p class="text-lg text-fg-soft mb-6">For Campaign: <span
            class="font-semibold text-primary">{{ $campaign->name }}</span></p>

    <div class="bg-bkg-alt rounded-lg shadow-sm border border-border">
        <div class="p-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-fg-alt">All Prizes</h2>
            <button wire:click="create"
                class="bg-primary text-primary-fg font-bold py-2 px-4 rounded-lg hover:opacity-90">
                Add New Prize
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Prize Name</th>
                        <th class="px-6 py-3">Display</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3 text-center">Total Stock</th>
                        <th class="px-6 py-3 text-center">Stock (Oct/Nov/Dec)</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($prizes as $prize)
                        <tr class="hover:bg-bkg-soft">
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $prize->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $prize->show ? 'bg-blue-500/20 text-blue-500' : 'bg-gray-500/20 text-fg-soft' }}">
                                    {{ $prize->show ? 'On Wheel' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $prize->category }}</td>
                            <td class="px-6 py-4 text-center font-bold text-primary">
                                {{ $prize->stock_oct + $prize->stock_nov + $prize->stock_dec }}</td>
                            <td class="px-6 py-4 text-center">{{ $prize->stock_oct }} / {{ $prize->stock_nov }} /
                                {{ $prize->stock_dec }}</td>
                            <td class="px-6 py-4 text-right space-x-4">
                                <button wire:click="edit({{ $prize->id }})"
                                    class="font-medium text-primary hover:underline">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-fg-soft">No prizes created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $prizes->links() }}</div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-bkg-alt rounded-lg shadow-xl p-8 w-full max-w-lg"
                @click.away="$wire.set('showModal', false)">

                <h2 class="text-2xl font-bold mb-6 text-fg-alt">
                    @if ($isEditing)
                        Edit Prize: <span class="text-primary">{{ $name }}</span>
                    @else
                        Create New Prize
                    @endif
                </h2>

                <form wire:submit="save" class="space-y-4">
                    {{-- Form fields for name, category, display type (show), and monthly stock --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-fg-soft">Prize Name</label>
                        <input wire:model="name" type="text" id="name" class="mt-1 block w-full form-input">
                        @error('name')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-fg-soft">Category</label>
                            <input wire:model="category" type="text" id="category"
                                placeholder="e.g., Bumper/Grand, High Level" class="mt-1 block w-full form-input">
                            @error('category')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="show" class="block text-sm font-medium text-fg-soft">Display on
                                Wheel</label>
                            <select wire:model="show" id="show" class="mt-1 block w-full form-select">
                                <option value="1">Yes (Show on Spin Wheel)</option>
                                <option value="0">No (Part of "More Gifts")</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-fg-soft">Monthly Stock</label>
                        <div class="grid grid-cols-3 gap-4 mt-1">
                            <input wire:model="stock_oct" type="number" placeholder="Oct" class="form-input">
                            <input wire:model="stock_nov" type="number" placeholder="Nov" class="form-input">
                            <input wire:model="stock_dec" type="number" placeholder="Dec" class="form-input">
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-primary text-primary-fg font-bold py-2 px-4 rounded-lg">Save
                            Prize</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
