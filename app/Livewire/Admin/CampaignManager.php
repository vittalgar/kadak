<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CampaignForm;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Campaign Manager')]
class CampaignManager extends Component
{
    use WithPagination;

    public CampaignForm $form; // Use the Form Object

    public bool $isEditing = false;

    public function edit(Campaign $campaign)
    {
        $this->form->resetValidation();
        $this->isEditing = true;
        $this->form->setCampaign($campaign);
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->form->update();
            $this->dispatch('notify', message: 'Campaign updated successfully.', type: 'success');
        } else {
            $this->form->store();
            $this->dispatch('notify', message: 'Campaign created successfully.', type: 'success');
        }
        $this->isEditing = false;
    }

    public function delete(Campaign $campaign)
    {
        $campaign->loadCount(['prizes', 'qrBatches']);

        // Check if there are any prizes linked to this campaign.
        if ($campaign->prizes_count > 0) {
            $this->dispatch('notify', message: "Cannot delete '{$campaign->name}'. It has {$campaign->prizes_count} prizes linked to it.", type: 'error');
            return;
        }

        // Check if there are any QR batches linked to this campaign.
        if ($campaign->qr_batches_count > 0) {
            $this->dispatch('notify', message: "Cannot delete '{$campaign->name}'. It has {$campaign->qr_batches_count} QR batches linked to it.", type: 'error');
            return;
        }

        // If no dependencies exist, proceed with the deletion.
        $campaign->delete();
        $this->dispatch('notify', message: 'Campaign deleted successfully.', type: 'success');
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
