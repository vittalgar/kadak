<?php

namespace App\Livewire\Forms;

use App\Models\Prize;
use Livewire\Attributes\Rule;
use Livewire\Form;

class PrizeForm extends Form
{
    public ?Prize $prize;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|in:Common,Mid-Value,High-Value,Grand')]
    public string $category = 'Common';

    #[Rule('required|integer|min:0')]
    public int $total_stock = 1000;

    #[Rule('required|integer|min:0')]
    public int $remaining_stock = 1000;

    #[Rule('required|integer|min:1')]
    public int $weight = 100;

    #[Rule('required|boolean')]
    public bool $is_active = true;

    public function setPrize(Prize $prize)
    {
        $this->prize = $prize;
        $this->name = $prize->name;
        $this->category = $prize->category;
        $this->total_stock = $prize->total_stock;
        $this->remaining_stock = $prize->remaining_stock;
        $this->weight = $prize->weight;
        $this->is_active = $prize->is_active;
    }

    public function store($campaignId)
    {
        $this->validate();
        $data = $this->all();
        $data['campaign_id'] = $campaignId;
        Prize::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->prize->update($this->all());
        $this->reset();
    }
}
