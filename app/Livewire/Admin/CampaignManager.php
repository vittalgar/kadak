<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CampaignForm; // Import the new Form Object
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.admin')]
#[Title('Campaign Manager')]
class CampaignManager extends Component
{
    use WithPagination;

    public CampaignForm $form; // Use the Form Object

    public bool $isEditing = false;

    public function edit(Campaign $campaign)
    {
        $this->form->setCampaign($campaign);
        $this->isEditing = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            session()->flash('success', 'Campaign updated successfully.');
        } else {
            $this->form->store();
            session()->flash('success', 'Campaign created successfully.');
        }
        $this->isEditing = false;
    }

    public function delete(Campaign $campaign)
    {
        // Add a check here to prevent deleting a campaign with active batches/claims if needed
        $campaign->delete();
        session()->flash('success', 'Campaign deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->form->reset();
        $this->isEditing = false;
    }

    public function render()
    {
        $campaigns = Campaign::latest()->paginate(10);

        return view('livewire.admin.campaign-manager', [
            'campaigns' => $campaigns,
        ]);
    }
}
