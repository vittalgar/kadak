<div class="space-y-8">
    <h1 class="text-3xl font-bold text-fg-alt">Agent Manager</h1>

    @if (session()->has('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-bkg-alt border border-border rounded-lg shadow-sm">
        <div class="p-6 border-b border-border">
            <h2 class="text-xl font-semibold text-fg-alt">{{ $isEditing ? 'Edit Agent' : 'Onboard New Agent' }}</h2>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="shop_name" class="block text-sm font-medium text-fg-soft">Shop Name</label>
                        <input wire:model.blur="form.shop_name" type="text" id="shop_name"
                            class="mt-1 block w-full form-input">
                        @error('form.shop_name')
                            <span class="text-danger text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-fg-soft">Contact
                            Person</label>
                        <input wire:model.blur="form.contact_person" type="text" id="contact_person"
                            class="mt-1 block w-full form-input">
                        @error('form.contact_person')
                            <span class="text-danger text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-fg-soft">Location / Address</label>
                    <input wire:model.blur="form.location" type="text" id="location"
                        class="mt-1 block w-full form-input">
                    @error('form.location')
                        <span class="text-danger text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="state" class="block text-sm font-medium text-fg-soft">State</label>
                        <select id="state" wire:model.live="form.state_id" class="mt-1 block w-full form-select">
                            <option value="">Select a State...</option>
                            @foreach ($this->states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                        @error('form.state_id')
                            <p class="text-danger text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-fg-soft">City</label>
                        <select id="city" wire:model.blur="form.city_id" class="mt-1 block w-full form-select"
                            @if (empty($this->cities)) disabled @endif>
                            <option value="">Select a City...</option>
                            @foreach ($this->cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('form.city_id')
                            <p class="text-danger text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="phone_number_1" class="block text-sm font-medium text-fg-soft">Phone 1 (Login
                            ID)</label>
                        <input wire:model.blur="form.phone_number_1" type="tel" id="phone_number_1"
                            class="mt-1 block w-full form-input">
                        @error('form.phone_number_1')
                            <span class="text-danger text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="phone_number_2" class="block text-sm font-medium text-fg-soft">Phone 2
                            (Optional)</label>
                        <input wire:model.blur="form.phone_number_2" type="tel" id="phone_number_2"
                            class="mt-1 block w-full form-input">
                        @error('form.phone_number_2')
                            <span class="text-danger text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="text-xs text-fg-soft">Note: The default password for a new agent is `Agent@123`.</div>
                <div>
                    <button type="submit"
                        class="bg-primary text-primary-fg font-bold py-2 px-4 rounded-lg hover:opacity-90">{{ $isEditing ? 'Save Changes' : 'Add Agent' }}</button>
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
            <h2 class="text-xl font-semibold text-fg-alt">Existing Agents</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-fg-soft">
                <thead class="text-xs text-fg-soft uppercase bg-bkg-soft">
                    <tr>
                        <th class="px-6 py-3">Shop Name</th>
                        <th class="px-6 py-3">Contact</th>
                        <th class="px-6 py-3">Location</th>
                        <th class="px-6 py-3">Phone Number</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($agents as $agent)
                        <tr>
                            <td class="px-6 py-4 font-medium text-fg-alt">{{ $agent->shop_name }}</td>
                            <td class="px-6 py-4">{{ $agent->contact_person }}</td>
                            <td class="px-6 py-4">{{ $agent->location }}, {{ $agent->city }}, {{ $agent->state }}
                            </td>
                            <td class="px-6 py-4">{{ $agent->phone_number_1 }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-4">
                                <button wire:click="edit({{ $agent->id }})"
                                    class="text-primary hover:underline">Edit</button>
                                <button wire:click="delete({{ $agent->id }})" wire:confirm="Are you sure?"
                                    class="text-danger hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-fg-soft">No agents have been added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $agents->links() }}</div>
    </div>
</div>
