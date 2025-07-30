<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.admin')]
#[Title('Change Password')]
class UpdatePassword extends Component
{
    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
