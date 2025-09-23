<?php

namespace App\Livewire\Forms;

use App\Models\Campaign;
use Livewire\Attributes\Rule;
use Livewire\Form;
use Livewire\Attributes\Live;

class CampaignForm extends Form
{
    public ?Campaign $campaign;

    // --- THE FIX IS HERE: Add #[Live] to the form properties ---
    #[Rule('required|string|max:255')]
    #[Live]
    public string $name = '';

    #[Rule('nullable|string')]
    #[Live]
    public string $description = '';

    #[Rule('required|date')]
    #[Live]
    public string $start_date = '';

    #[Rule('required|date|after_or_equal:start_date')]
    #[Live]
    public string $end_date = '';

    #[Rule('boolean')]
    public bool $is_active = true;

    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->name = $campaign->name;
        $this->description = $campaign->description;
        $this->start_date = $campaign->start_date->format('Y-m-d');
        $this->end_date = $campaign->end_date->format('Y-m-d');
        $this->is_active = $campaign->is_active;
    }

    public function store()
    {
        $this->validate();
        Campaign::create($this->all());
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->campaign->update($this->all());
        $this->reset();
    }
}
