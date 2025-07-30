<?php

namespace App\Livewire\Forms;

use App\Models\RetailShop;
use Livewire\Attributes\Rule;
use Livewire\Form;

class RetailShopForm extends Form
{
    public ?RetailShop $shop;

    #[Rule('required|string|max:255')]
    public string $shop_name = '';

    #[Rule('required|string')]
    public string $address = '';

    #[Rule('required|string|max:100')]
    public string $city = '';

    #[Rule('required|string|max:100')]
    public string $state = '';

    #[Rule('required|numeric|digits:6')]
    public string $pincode = '';

    #[Rule('required|boolean')]
    public bool $is_active = true;

    public function setShop(RetailShop $shop)
    {
        $this->shop = $shop;
        $this->shop_name = $shop->shop_name;
        $this->address = $shop->address;
        $this->city = $shop->city;
        $this->state = $shop->state;
        $this->pincode = $shop->pincode;
        $this->is_active = $shop->is_active;
    }

    public function store($dealerId)
    {
        $this->validate();
        $data = $this->all();
        $data['dealer_id'] = $dealerId;
        RetailShop::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->shop->update($this->all());
        $this->reset();
    }
}
