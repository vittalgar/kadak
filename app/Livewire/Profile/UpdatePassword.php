<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Change Password')]
class UpdatePassword extends Component
{
    // --- Form Properties ---
    #[Rule('required|string|current_password')]
    public string $current_password = '';

    #[Rule('required|string|min:8|confirmed')]
    public string $password = '';
    
    public string $password_confirmation = '';

    /**
     * Update the user's password.
     */
    public function updatePassword(): void
    {
        // 1. Validate the input based on the rules above.
        $this->validate();

        // 2. If validation passes, update the password.
        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        // 3. Reset the form fields.
        $this->reset(['current_password', 'password', 'password_confirmation']);
        
        // 4. Send a success message to the browser.
        $this->dispatch('password-updated');
    }

    public function render()
    {
        return view('livewire.profile.update-password');
    }
}