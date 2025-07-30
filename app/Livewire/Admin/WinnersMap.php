<?php

namespace App\Livewire\Admin;

use App\Models\Claim;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Live Winners Map')]
class WinnersMap extends Component
{
    public $claims;

    public function mount()
    {
        // Fetch only the claims where latitude and longitude are not null.
        // We select only the columns we need for the map to be efficient.
        $this->claims = Claim::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('name', 'city', 'prize_won', 'latitude', 'longitude')
            ->get()
            ->toJson(); // Pass the data as a JSON string
    }

    public function render()
    {
        return view('livewire.admin.winners-map');
    }
}
