<?php

namespace App\Livewire\Forms;

use App\Models\Campaign;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CampaignForm extends Form
{
    public ?Campaign $campaign;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('required|date')]
    public string $start_date = '';

    #[Rule('required|date|after_or_equal:start_date')]
    public string $end_date = '';

    #[Rule('required|boolean')]
    public bool $is_active = false;

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
