<div>
    <h1 class="text-3xl font-bold text-fg-alt mb-6">My Profile</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="bg-bkg-alt border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h2 class="text-xl font-semibold text-fg-alt">Profile Information</h2>
                    <p class="mt-1 text-sm text-fg-soft">Update your account's profile information and email address.</p>
                </div>
                <div class="p-6">
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-fg-soft">Name</label>
                            <input wire:model="name" type="text" id="name" class="mt-1 block w-full form-input">
                            @error('name')
                                <span class="text-danger text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-fg-soft">Email Address</label>
                            <input wire:model="email" type="email" id="email"
                                class="mt-1 block w-full form-input">
                            @error('email')
                                <span class="text-danger text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-primary text-primary-fg font-bold py-2 px-4 rounded-lg hover:opacity-90">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-bkg-alt border border-border rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-fg-alt">Account Actions</h3>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('profile.password') }}" wire:navigate
                            class="text-primary hover:underline text-sm font-medium">
                            Change Password
                        </a>
                        {{-- You could add a "Delete Account" link here in the future --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
