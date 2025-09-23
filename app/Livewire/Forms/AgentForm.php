<?php

namespace App\Livewire\Forms;

use App\Models\Agent;
use App\Models\City;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Rule as LivewireRule;
use Livewire\Form;

class AgentForm extends Form
{
    public ?Agent $agent;

    #[LivewireRule('required|string|max:255')]
    public string $shop_name = '';
    #[LivewireRule('required|string|max:255')]
    public string $contact_person = '';
    #[LivewireRule('required|string')]
    public string $location = '';
    #[LivewireRule('required|exists:states,id')]
    public ?int $state_id = null;
    #[LivewireRule('required|exists:cities,id')]
    public ?int $city_id = null;
    #[LivewireRule('required|numeric|digits:10')]
    public string $phone_number_1 = '';
    #[LivewireRule('nullable|numeric|digits:10')]
    public string $phone_number_2 = '';

    public function setAgent(Agent $agent)
    {
        $this->agent = $agent;
        $this->shop_name = $agent->shop_name;
        $this->contact_person = $agent->contact_person;
        $this->location = $agent->location;
        $this->phone_number_1 = $agent->phone_number_1;
        $this->phone_number_2 = $agent->phone_number_2;

        $city = City::where('name', $agent->city)->first();
        if ($city) {
            $this->state_id = $city->state_id;
            $this->city_id = $city->id;
        }
    }

    public function store()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            // THE FIX: We ensure that city and state are not null before proceeding.
            $city = City::find($validated['city_id']);
            $state = State::find($validated['state_id']);

            if (!$city || !$state) {
                // This will prevent the error if for some reason the ID is invalid.
                return;
            }

            $agent = Agent::create([
                'shop_name' => $validated['shop_name'],
                'contact_person' => $validated['contact_person'],
                'location' => $validated['location'],
                'city' => $city->name, // This line is now safe
                'state' => $state->name,
                'phone_number_1' => $validated['phone_number_1'],
                'phone_number_2' => $validated['phone_number_2'] ?? null,
            ]);

            User::create([
                'name' => $validated['contact_person'],
                'email' => $validated['phone_number_1'],
                'password' => Hash::make('Agent@123'),
                'role_id' => Role::where('name', 'Agent')->value('id'),
                'agent_id' => $agent->id,
            ]);
        });
        $this->reset();
    }

    public function update()
    {
        $validated = $this->validate();

        $city = City::find($validated['city_id']);
        $state = State::find($validated['state_id']);

        if (!$city || !$state) {
            return;
        }

        $this->agent->update([
            'shop_name' => $validated['shop_name'],
            'contact_person' => $validated['contact_person'],
            'location' => $validated['location'],
            'city' => $city->name, // This line is now safe
            'state' => $state->name,
            'phone_number_1' => $validated['phone_number_1'],
            'phone_number_2' => $validated['phone_number_2'] ?? null,
        ]);

        $this->reset();
    }
}
