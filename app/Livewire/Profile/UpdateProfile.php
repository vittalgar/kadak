<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
#[Title('My Profile')]
class UpdateProfile extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    public function mount()
    {
        // Pre-fill the form with the authenticated user's data
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function save()
    {
        $user = Auth::user();

        // Dynamically create the validation rule to ignore the user's own email
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->dispatch('notify', message: 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile.update-profile');
    }
}
