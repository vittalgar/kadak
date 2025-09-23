<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\QrBatch;
use App\Models\QrToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('QR Code Batch Manager')]
class QrBatchManager extends Component
{
    use WithPagination;

    public string $batchName = '';
    public int $quantity = 1000;
    public ?int $selectedCampaignId = null;
    public array $campaigns = []; // Use a simple array for reliability

    public function mount()
    {
        // Fetch campaigns into a simple array to prevent Livewire issues
        $this->campaigns = Campaign::where('is_active', true)->orderBy('name')->get(['id', 'name'])->toArray();
    }

    protected function rules(): array
    {
        return [
            'batchName' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:500000',
            'selectedCampaignId' => 'required|exists:campaigns,id',
        ];
    }

    // This is the corrected method
    public function createBatch()
    {
        $this->validate();

        try {
            // --- THE FIX IS HERE ---
            // We wrap the entire operation in a transaction.
            DB::transaction(function () {
                $batch = QrBatch::create([
                    'name' => $this->batchName,
                    'quantity' => $this->quantity,
                    'campaign_id' => $this->selectedCampaignId,
                    'generated_by_user_id' => Auth::id(),
                ]);

                $tokensToInsert = [];
                for ($i = 0; $i < $this->quantity; $i++) {
                    $tokensToInsert[] = [
                        'token' => Str::random(16), // Using Str::random for consistency
                        'status' => 'NEW',
                        'batch_id' => $batch->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert tokens in chunks to be memory-efficient for large batches.
                foreach (array_chunk($tokensToInsert, 1000) as $chunk) {
                    QrToken::insert($chunk);
                }
            });

            $this->dispatch('notify', message: 'Batch created successfully.', type: 'success');
            $this->reset('batchName', 'quantity', 'selectedCampaignId');
        } catch (\Exception $e) {
            // If anything goes wrong inside the transaction, catch the error.
            $this->dispatch('notify', message: 'Failed to create batch. Please try again. Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $batches = QrBatch::with(['generator', 'campaign'])->latest()->paginate(10);
        return view('livewire.admin.qr-batch-manager', ['batches' => $batches]);
    }
}
