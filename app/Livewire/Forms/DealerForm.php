<?php

namespace App\Livewire\Forms;

use App\Models\Dealer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Rule as LivewireRule;
use Livewire\Form;

class DealerForm extends Form
{
    public ?Dealer $dealer;

    #[LivewireRule('required|string|max:255')]
    public string $dealership_name = '';

    #[LivewireRule('required|string|max:255')]
    public string $contact_person = '';

    #[LivewireRule('required|numeric|digits:10')]
    public string $phone_number = '';

    #[LivewireRule('required|string|max:100')]
    public string $city = '';

    #[LivewireRule('required|string|max:100')]
    public string $state = '';

    #[LivewireRule('required|email')]
    public string $email = '';

    // THE FIX: The rules method is now aware of the editing state.
    public function rules()
    {
        // Check if the underlying model property exists and is set. This is the key.
        $isEditing = isset($this->dealer);

        $userIdToIgnore = null;
        if ($isEditing && $this->dealer->user) {
            $userIdToIgnore = $this->dealer->user->id;
        }

        return [
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
                // Only ignore a record if we are editing.
                $isEditing ? Rule::unique('dealers')->ignore($this->dealer->id) : 'unique:dealers,phone_number'
            ],
            'email' => [
                'required',
                'email',
                // Only ignore a record if we are editing and have a user ID.
                $userIdToIgnore ? Rule::unique('users')->ignore($userIdToIgnore) : 'unique:users,email'
            ],
        ];
    }

    public function setDealer(Dealer $dealer)
    {
        $this->dealer = $dealer;
        $this->dealership_name = $dealer->dealership_name;
        $this->contact_person = $dealer->contact_person;
        $this->phone_number = $dealer->phone_number;
        $this->city = $dealer->city;
        $this->state = $dealer->state;

        if ($dealer->user) {
            $this->email = $dealer->user->email;
        }
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            $dealer = Dealer::create($this->only(['dealership_name', 'contact_person', 'phone_number', 'city', 'state']));

            $dealerRoleId = Role::where('name', 'Dealer')->value('id');

            User::create([
                'name' => $this->contact_person,
                'email' => $this->email,
                'password' => Hash::make($this->phone_number),
                'role_id' => $dealerRoleId,
                'dealer_id' => $dealer->id,
            ]);
        });

        $this->reset();
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $this->dealer->update($this->only(['dealership_name', 'contact_person', 'phone_number', 'city', 'state']));

            if ($this->dealer->user) {
                $this->dealer->user->update([
                    'email' => $this->email,
                    'name' => $this->contact_person
                ]);
            }
        });

        $this->reset();
    }
}
