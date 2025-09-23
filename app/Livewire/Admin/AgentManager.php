<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\AgentForm;
use App\Models\Agent;
use App\Models\City;
use App\Models\State;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.app')]
#[Title('Agent Manager')]
class AgentManager extends Component
{
    use WithPagination;

    public AgentForm $form;
    public bool $isEditing = false;
    public ?Agent $editingAgent = null;
    public $states;

    public function mount()
    {
        $this->states = State::orderBy('name')->get();
    }

    #[Computed]
    public function cities()
    {
        if (empty($this->form->state_id)) {
            return collect();
        }
        return City::where('state_id', $this->form->state_id)->orderBy('name')->get();
    }

    public function updatedFormStateId($stateId)
    {
        $this->form->reset('city_id');
        $this->form->resetValidation('city_id');
    }

    public function edit(Agent $agent)
    {
        $this->isEditing = true;
        $this->editingAgent = $agent;
        $this->form->setAgent($agent);
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            session()->flash('success', 'Agent updated successfully.');
        } else {
            $this->form->store();
            session()->flash('success', 'Agent created successfully.');
        }
        $this->cancelEdit();
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingAgent = null;
        $this->form->reset();
    }

    public function delete(Agent $agent)
    {
        $agent->delete();
        session()->flash('success', 'Agent and user account deleted successfully.');
    }

    public function render()
    {
        $agents = Agent::latest()->paginate(10);
        return view('livewire.admin.agent-manager', [
            'agents' => $agents,
        ]);
    }
}
