<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Claim;
use App\Models\Product;
use App\Models\QrToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Spin to Win!')]
class SpinPage extends Component
{
    public string $token;
    public ?Campaign $campaign = null;
    public string $currentState = 'loading';
    public $products = [];
    public $wheelPrizes = [];
    public ?string $winningPrize = null;
    public ?string $finalClaimId = null;
    public ?string $errorMessage = null;
    public ?int $selectedProductId = null;
    public string $claimName = '';
    public string $claimMobile = '';
    public string $claimCity = '';
    public ?float $latitude = null;
    public ?float $longitude = null;

    public function mount($token)
    {
        $this->token = $token;
        $qrToken = QrToken::with('batch.campaign')->where('token', $this->token)->first();

        if (!$qrToken || !$qrToken->batch || !$qrToken->batch->campaign) {
            $this->setError("This promotional code is invalid.");
            return;
        }

        $this->campaign = $qrToken->batch->campaign;

        if (!$this->campaign->is_active || now()->isBefore($this->campaign->start_date) || now()->isAfter($this->campaign->end_date)) {
            $this->setError("This campaign is not currently active.");
            return;
        }

        if ($qrToken->status === 'CLAIMED') {
            $this->setError("This promotional code has already been claimed.");
            return;
        }

        // --- THE FIX IS HERE ---
        // For both NEW and SPINNING (but unclaimed) tokens, we must first get the product selection.
        if ($qrToken->status === 'NEW' || ($qrToken->prize !== null && $qrToken->status === 'SPINNING')) {
            $this->winningPrize = $qrToken->prize; // This will be null for a NEW token, which is correct.
            $this->products = $this->campaign->products()->where('is_active', true)->get();
            $this->currentState = 'product_selection';
            return;
        }

        $this->setError("This promotional code has already been used.");
    }

    public function selectProduct()
    {
        $this->validate(['selectedProductId' => 'required|exists:products,id']);

        // --- THE FIX ---
        // After selecting a product, check if a prize was already won.
        if ($this->winningPrize) {
            // If so, go straight to the claim form.
            $this->currentState = 'claim';
        } else {
            // Otherwise, go to the spin wheel.
            $this->currentState = 'spin';
        }
    }

    public function spinWheel()
    {
        $qrToken = QrToken::where('token', $this->token)->first();
        if (!$qrToken || $qrToken->status !== 'NEW') {
            $this->setError("Invalid or already used token.");
            return;
        }

        // Now that we are spinning, we can mark the status.
        $qrToken->status = 'SPINNING';
        $qrToken->save();

        $masterPrizePool = $this->campaign->prizes()->where('is_active', true)->where('remaining_stock', '>', 0)->get()->toArray();
        if (empty($masterPrizePool)) {
            $this->setError("No prizes are currently available for this campaign.");
            return;
        }

        $totalWeight = array_sum(array_column($masterPrizePool, 'weight'));
        $randomNumber = mt_rand(1, $totalWeight);
        $prizeData = null;
        foreach ($masterPrizePool as $prize) {
            if ($randomNumber <= $prize['weight']) {
                $prizeData = $prize;
                break;
            }
            $randomNumber -= $prize['weight'];
        }

        if ($prizeData) {
            $this->winningPrize = $prizeData['name'];
            $qrToken->prize = $this->winningPrize;
            $qrToken->save();

            DB::table('prizes')->where('id', $prizeData['id'])->decrement('remaining_stock');

            $allPrizeNames = array_column($masterPrizePool, 'name');
            shuffle($allPrizeNames);
            $wheelPrizes = array_slice($allPrizeNames, 0, 16);
            if (!in_array($this->winningPrize, $wheelPrizes)) {
                $wheelPrizes[array_rand($wheelPrizes)] = $this->winningPrize;
            }
            $this->dispatch('spin-has-been-determined', wheelPrizes: $wheelPrizes, winningPrizeName: $this->winningPrize);
        }
    }

    public function submitClaim()
    {
        $validated = $this->validate([
            'claimName' => 'required|string|max:255',
            'claimMobile' => 'required|numeric|digits:10',
            'claimCity' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $qrToken = QrToken::where('token', $this->token)->first();
        if (!$qrToken || $qrToken->status !== 'SPINNING') {
            $this->setError("Invalid claim attempt.");
            return;
        }

        $claimId = 'BB-' . strtoupper(Str::random(8));

        Claim::create([
            'claim_id' => $claimId,
            'token_used' => $this->token,
            'campaign_id' => $this->campaign->id,
            'product_selected_id' => $this->selectedProductId,
            'name' => $validated['claimName'],
            'mobile' => $validated['claimMobile'],
            'city' => $validated['claimCity'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'prize_won' => $this->winningPrize,
            'status' => 'Processing',
        ]);

        $qrToken->status = 'CLAIMED';
        $qrToken->save();

        $this->finalClaimId = $claimId;
        $this->currentState = 'thank_you';
    }

    private function setError(string $message)
    {
        $this->currentState = 'error';
        $this->errorMessage = $message;
    }

    public function render()
    {
        return view('livewire.spin-page');
    }
}
