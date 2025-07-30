<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\QrBatch;
use App\Models\QrToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('QR Batch Manager')]
class QrBatchManager extends Component
{
    use WithPagination;

    public string $batchName = '';
    public int $quantity = 1000;
    public ?int $selectedCampaignId = null;

    public $campaigns;

    public function mount()
    {
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'batchName' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:200000',
            'selectedCampaignId' => 'required|exists:campaigns,id',
        ];
    }

    public function createBatch()
    {
        $this->validate();

        $campaign = Campaign::find($this->selectedCampaignId);

        $batch = QrBatch::create([
            'name' => $this->batchName,
            'quantity' => $this->quantity,
            'campaign_id' => $this->selectedCampaignId,
            'generated_by_user_id' => Auth::id(),
        ]);

        $tokensToInsert = [];
        for ($i = 0; $i < $this->quantity; $i++) {
            $tokensToInsert[] = [
                'token' => Str::uuid()->toString(),
                'status' => 'NEW',
                'batch_id' => $batch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($tokensToInsert, 1000) as $chunk) {
            QrToken::insert($chunk);
        }

        session()->flash('success', 'Batch created successfully for the "' . $campaign->name . '" campaign.');
        $this->reset('batchName', 'quantity', 'selectedCampaignId');
    }

    /**
     * NEW: An empty method that allows us to trigger a re-render from the frontend.
     */
    public function refreshComponent()
    {
        // This method can remain empty. Calling it from the frontend
        // is enough to make Livewire refresh the component data.
    }

    public function render()
    {
        $batches = QrBatch::with(['generator', 'campaign'])->latest()->paginate(10);
        return view('livewire.admin.qr-batch-manager', ['batches' => $batches]);
    }
}
