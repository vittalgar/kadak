<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Claim;
use App\Models\City;
use App\Models\State;
use App\Models\Prize;
use App\Models\QrToken;
use App\Models\Agent;
use App\Jobs\SendWinnerSms;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Project Kadak - Spin to Win!')]
class SpinPage extends Component
{
    // --- TESTING CONFIGURATION ---
    private ?string $testDate = '2025-10-15';
    // -----------------------------

    // --- Core Properties ---
    public string $token;
    public ?Campaign $campaign = null;
    public string $currentState = 'loading';
    public ?string $errorMessage = null;

    // --- Wheel & Prize Properties ---
    public array $initialPrizes = [];
    public ?string $winningPrize = null;
    private const SPECIAL_PRIZE = "Many More Attractive Gifts";
    private const BETTER_LUCK_PRIZE = "Better Luck Next Time";
    private const WHEEL_SIZE = 16;

    // Track only the stock columns that exist in your schema (left→right = cumulative order)
    private const CAMPAIGN_STOCK_MONTHS = ['oct', 'nov', 'dec'];

    // --- Claim Form Properties ---
    public array $products = [];
    public array $agents = [];
    public array $states = [];
    public Collection $cities;

    public ?int $selectedProductId = null;
    public string $claimMobile = '';
    public string $claimName = '';
    public ?int $selectedStateId = null;
    public ?int $selectedCityId = null;
    public ?int $selectedAgentId = null;
    public ?string $finalClaimId = null;
    public ?Agent $selectedAgentDetails = null;

    // Properties to capture user location
    public ?float $latitude = null;
    public ?float $longitude = null;

    public function mount($token): void
    {
        $now = $this->testDate ? Carbon::parse($this->testDate) : now();
        $this->cities = collect();
        $this->token = $token;
        $qrToken = QrToken::with('batch.campaign')->where('token', $this->token)->first();

        if (!$qrToken || !$qrToken->batch || !$qrToken->batch->campaign) {
            $this->setError("This promotional code is invalid.");
            return;
        }
        $this->campaign = $qrToken->batch->campaign;
        if ($this->campaign->is_active == false || !$now->between($this->campaign->start_date, $this->campaign->end_date)) {
            $this->setError("This campaign is not currently active.");
            return;
        }

        if ($qrToken->status === 'CLAIMED') {
            $claim = Claim::where('token_used', $this->token)->with('agent')->first();
            if ($claim && $claim->prize_won === self::BETTER_LUCK_PRIZE) {
                $this->currentState = 'betterLuck';
            } elseif ($claim) {
                $this->winningPrize = $claim->prize_won;
                $this->finalClaimId = $claim->claim_id;
                $this->claimName = $claim->name;
                $this->selectedAgentDetails = $claim->agent;
                $this->currentState = 'thankYou';
            } else {
                $this->setError("This code has been used, but claim details could not be found.");
            }
            return;
        }

        $this->products = $this->campaign->products()->where('is_active', true)->get(['products.id', 'products.name', 'products.image_url'])->toArray();
        $this->currentState = 'collectUserDetails';
    }

    /**
     * Step 1: Validate Mobile & Product, then move to the spin wheel.
     */
    public function submitInitialDetails()
    {
        $this->validate([
            'claimMobile' => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'selectedProductId' => [
                'required',
                Rule::exists('campaign_product', 'product_id')
                    ->where('campaign_id', $this->campaign->id),
            ],
        ]);
        $qrToken = QrToken::where('token', $this->token)->first();

        if ($qrToken && $qrToken->status === 'SPINNING' && $qrToken->prize) {
            $this->winningPrize = $qrToken->prize;
            if ($this->winningPrize === self::BETTER_LUCK_PRIZE) {
                $claimData = ['campaign_id' => $this->campaign->id, 'product_selected_id' => $this->selectedProductId, 'token_used' => $this->token, 'mobile' => $this->claimMobile, 'prize_won' => $this->winningPrize, 'name' => 'N/A'];
                $this->createClaimRecord($claimData);
                $this->currentState = 'betterLuck';
            } else {
                $activeAgentStates = Agent::where('is_active', true)->distinct()->pluck('state');
                $this->states = State::whereIn('name', $activeAgentStates)->orderBy('name')->get()->toArray();
                $this->agents = [];
                $this->currentState = 'claim';
            }
        } else {
            $prizesForWheel = $this->campaign->prizes()->where('show', true)->where('is_active', true)->pluck('name');
            if ($this->campaign->prizes()->where('name', 'Gold Coin')->exists()
                && !$prizesForWheel->contains('Gold Coin')) {
                $prizesForWheel->push('Gold Coin');
            }
            if (!$prizesForWheel->contains(self::SPECIAL_PRIZE)) {
                $prizesForWheel->push(self::SPECIAL_PRIZE);
            }
            if (!$prizesForWheel->contains(self::BETTER_LUCK_PRIZE)) {
                $prizesForWheel->push(self::BETTER_LUCK_PRIZE);
            }
            $this->initialPrizes = $this->padPrizes($prizesForWheel->toArray(), self::WHEEL_SIZE, true, ['Gold Coin', self::SPECIAL_PRIZE, self::BETTER_LUCK_PRIZE]);
            $this->currentState = 'spin';
        }
    }
    /**
     * Step 2: User clicks "SPIN!", determine the prize.
     */
    public function spinWheel()
    {
        try {
            DB::transaction(function () {
                $qrToken = QrToken::where('token', $this->token)->lockForUpdate()->first();
                if (!$qrToken || $qrToken->status !== 'NEW') {
                    throw new \Exception("This promotional code has already been used.");
                }
                $qrToken->status = 'SPINNING';
                $qrToken->save();
                $prizeToClaim = $this->determinePrize($this->claimMobile);
                $this->winningPrize = $prizeToClaim->name;
                $qrToken->prize = $this->winningPrize;
                $qrToken->save();
                $visualSpinTarget = $prizeToClaim->show ? $prizeToClaim->name : self::SPECIAL_PRIZE;
                $wheelPrizesForDisplay = $this->buildNearMissWheel($prizeToClaim);
                $isWinner = ($this->winningPrize !== self::BETTER_LUCK_PRIZE);
                $this->dispatch('spin-to', wheelPrizes: $wheelPrizesForDisplay, winningPrizeName: $visualSpinTarget, isWinner: $isWinner);
            });
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    /**
     * Step 3: After animation, decide the next step (Claim, or Better Luck).
     */
    public function afterSpinAnimation(): void
    {
        $claimData = [
            'campaign_id' => $this->campaign->id,
            'product_selected_id' => $this->selectedProductId,
            'token_used' => $this->token,
            'mobile' => $this->claimMobile,
            'prize_won' => $this->winningPrize,
        ];
        if ($this->winningPrize === self::BETTER_LUCK_PRIZE) {
            $claimData['name'] = 'N/A';
            $this->createClaimRecord($claimData); // This call is now valid
            $this->currentState = 'betterLuck';
        } else {
            $activeAgentStates = Agent::where('is_active', true)->distinct()->pluck('state');
            $this->states = State::whereIn('name', $activeAgentStates)->orderBy('name')->get()->toArray();
            $this->agents = [];
            $this->currentState = 'claim';
        }
    }

    /**
     * Step 4: User submits final details (Name, Location, Agent).
     */
    public function submitFinalDetails()
    {
        $this->validate([
            'claimName' => 'required|string|max:255',
            'selectedStateId' => 'required|exists:states,id',
            'selectedCityId' => ['required', Rule::exists('cities', 'id')->where('state_id', $this->selectedStateId)],
            'selectedAgentId' => 'required|exists:agents,id'
        ]);

        $this->selectedAgentDetails = Agent::find($this->selectedAgentId);
        if (!$this->selectedAgentDetails) {
            $this->setError('The selected agent could not be found.');
            return;
        }

        $city = City::find($this->selectedCityId);
        $state = State::find($this->selectedStateId);

        $claimData = [
            'campaign_id' => $this->campaign->id,
            'product_selected_id' => $this->selectedProductId,
            'token_used' => $this->token,
            'mobile' => $this->claimMobile,
            'prize_won' => $this->winningPrize,
            'name' => $this->claimName,
            'city' => $city ? $city->name : null,
            'state' => $state ? $state->name : null,
            'agent_id' => $this->selectedAgentId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        $this->createClaimRecord($claimData, true); // This call is now valid
        $this->currentState = 'thankYou';
    }

    private function createClaimRecord(array $data, bool $sendSms = false)
    {
        try {
            DB::transaction(function () use ($data, $sendSms) {
                $qrToken = QrToken::where('token', $this->token)->lockForUpdate()->first();

                if (!$qrToken || !in_array($qrToken->status, ['SPINNING', 'NEW'])) {
                    throw new \Exception("This promotional code has already been claimed or is in an invalid state.");
                }

                $claimId = 'BB-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
                $data['claim_id'] = $claimId;

                $claim = Claim::create($data);

                if ($sendSms) {
                    SendWinnerSms::dispatch($claim);
                }

                $qrToken->status = 'CLAIMED';
                $qrToken->save();

                $this->finalClaimId = $claimId;
            });
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    /**
     * Core prize determination logic, now with duplicate prevention.
     */
    private function determinePrize(string $mobileNumber): Prize
    {
        // --- 0) Recent history / categories won ---
        $winHistory = Claim::where('mobile', $mobileNumber)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $prizesAlreadyWon   = $winHistory->pluck('prize_won')->toArray();
        $recentPrizesWon    = array_slice($prizesAlreadyWon, 0, 5);
        $wonPrizeCategories = Prize::whereIn('name', $prizesAlreadyWon)->pluck('category')->countBy();
    
        $now = $this->testDate ? Carbon::parse($this->testDate) : now();
    
        // --- 1) Stock columns (cumulative months + current month) ---
        $cumulativeMonths = $this->cumulativeStockColumnsFor($now); // e.g. ['stock_oct','stock_nov','stock_dec']
    
        // --- 2) Base pool (exclude MMAG placeholder entirely) ---
        $prizeQuery = $this->campaign->prizes()
            ->where('is_active', true)
            ->where('name', '!=', self::SPECIAL_PRIZE);
    
        // Guard: only one Bumper lifetime
        $hasBumperBefore = ($wonPrizeCategories->get('Bumper', 0) > 0);
        if ($hasBumperBefore) {
            $prizeQuery->where('category', '!=', 'Bumper');
        }
    
        // --- 3) Build weighted list by cumulative stock ---
        $allAvailablePrizes = $prizeQuery->get()->map(function ($prize) use ($cumulativeMonths, $now) {
            $stock = 0;
            foreach ($cumulativeMonths as $m) {
                $stock += (int) ($prize->{$m} ?? 0);
            }
    
            // Gold Coin: visible in wheel, but not awardable before day 15
            if (strcasecmp($prize->name, 'Gold Coin') === 0 && $now->day < 15) {
                $stock = 0;
            }
    
            $prize->weight = max(0, (int) $stock);
            return $prize;
        });
    
        // --- 4) Duplicate prevention (recent 5) + positive weight only ---
        $eligiblePrizes = $allAvailablePrizes
            ->where('weight', '>', 0)
            ->whereNotIn('name', $recentPrizesWon);
    
        // Fallback A: allow previously-won prizes if otherwise empty
        if ($eligiblePrizes->isEmpty()) {
            $eligiblePrizes = $allAvailablePrizes->where('weight', '>', 0);
            if ($eligiblePrizes->isEmpty()) {
                // Fallback B: try BLNT if it has positive cumulative stock
                $blnt = Prize::where('campaign_id', $this->campaign->id)
                    ->where('name', self::BETTER_LUCK_PRIZE)
                    ->first();
    
                if ($blnt) {
                    $cum = 0;
                    foreach ($cumulativeMonths as $m) { $cum += (int) ($blnt->{$m} ?? 0); }
                    if ($cum > 0) {
                        // Decrement from cumulative and return BLNT
                        $this->decrementFromCumulative($blnt, $now);
                        return $blnt;
                    }
                }
    
                throw new \RuntimeException('No awardable prizes available (all months exhausted).');
            }
        }
    
        // --- 5) Tier weights (calendar cadence) ---
        $d = $now->day;
        $tiers = $d <= 14
            ? ['Low-Level' => 55, 'Mid-Level' => 35, 'High-Level' => 8,  'Bumper' => 2]
            : ($d <= 21
                ? ['Low-Level' => 45, 'Mid-Level' => 35, 'High-Level' => 15, 'Bumper' => 5]
                : ['Low-Level' => 35, 'Mid-Level' => 35, 'High-Level' => 20, 'Bumper' => 10]);
    
        if ($hasBumperBefore) {
            unset($tiers['Bumper']); // lifetime bumper guard
        }
    
        // --- 6) Calendar-aware pacing (month-to-date plan vs actual) ---

        $monthAwardedCountsByCategory = function (Carbon $ref): array {
            [$start, $end] = $this->monthWindow($ref);
        
            $rows = Claim::query()
                ->join('prizes', function ($join) {
                    $join->on('claims.prize_won', '=', 'prizes.name')
                         ->on('prizes.campaign_id', '=', 'claims.campaign_id');
                })
                ->where('claims.campaign_id', $this->campaign->id)
                ->whereBetween('claims.created_at', [$start, $end])
                ->select('prizes.category', DB::raw('COUNT(*) as cnt'))
                ->groupBy('prizes.category')
                ->get();
        
            $out = [];
            foreach ($rows as $r) { $out[$r->category] = (int) $r->cnt; }
            return $out;
        };

    
        $monthTotalStockByCategory = function (array $cumCols): array {
            $rows = $this->campaign->prizes()
                ->where('is_active', true)
                ->get(['category', 'stock_oct', 'stock_nov', 'stock_dec']);
            $totals = [];
            foreach ($rows as $p) {
                $sum = 0;
                foreach ($cumCols as $m) { $sum += (int) ($p->{$m} ?? 0); }
                $cat = $p->category ?: 'Others';
                $totals[$cat] = ($totals[$cat] ?? 0) + $sum;
            }
            return $totals;
        };
    
        $pacingMultiplierFor = function (string $cat, int $awardedSoFar, int $totalStock, Carbon $ref): float {
            if ($totalStock <= 0) return 0.0;
            $day = (int)$ref->day; $days = (int)$ref->daysInMonth;
            $plannedSoFar = $totalStock * ($day / $days);
            if ($plannedSoFar <= 0) return 1.0;
    
            // If awarded > planned -> slow down (<1); if behind -> speed up (>1)
            $ratio = $plannedSoFar / max(1, $awardedSoFar);
            // Clamp to keep adjustments gentle and predictable
            $min = 0.25; $max = 1.5;
            $mult = max($min, min($max, $ratio));
    
            // Make High & Bumper more conservative by default
            if ($cat === 'High-Level')  $mult = min($mult, 1.0);
            if ($cat === 'Bumper')      $mult = min($mult, 0.8);
            return $mult;
        };
    
        $awardedByCat = $monthAwardedCountsByCategory($now);
        $totalByCat   = $monthTotalStockByCategory($cumulativeMonths);
    
        // Apply pacing multipliers to the base tier weights
        foreach ($tiers as $cat => $w) {
            $mult = $pacingMultiplierFor($cat, (int)($awardedByCat[$cat] ?? 0), (int)($totalByCat[$cat] ?? 0), $now);
            $tiers[$cat] = (int) round($w * $mult);
        }
    
        // --- 7) Hard caps after pacing (safety rails) ---
        $daysInMonth = $now->daysInMonth;
        $monthPhase  = ($now->day <= (int)floor($daysInMonth / 2)) ? 'early' : 'late';
    
        // Tune these caps as policy levers:
        $capHigh   = ($monthPhase === 'early') ? 8 : 12; // % of tier mass
        $capBumper = ($monthPhase === 'early') ? 2 : 4;  // % of tier mass
    
        $sum = array_sum($tiers) ?: 1;
        if (isset($tiers['High-Level'])) {
            $maxW = (int) floor($sum * $capHigh / 100);
            $tiers['High-Level'] = min($tiers['High-Level'], max(0, $maxW));
        }
        $sum = array_sum($tiers) ?: 1;
        if (isset($tiers['Bumper'])) {
            $maxW = (int) floor($sum * $capBumper / 100);
            $tiers['Bumper'] = min($tiers['Bumper'], max(0, $maxW));
        }
        if (array_sum($tiers) <= 0) {
            $tiers = ['Low-Level' => 70, 'Mid-Level' => 30];
        }
    
        // --- 8) Tiered draw with BLNT sprinkled in every tier ---
        $prizeToAward = null;
        $workingTiers = $tiers;
    
        while (!$prizeToAward && !empty($workingTiers)) {
            $targetCategory = $this->selectTierByWeight($workingTiers);
    
            $pool = $eligiblePrizes->filter(function ($p) use ($targetCategory) {
                if (strcasecmp($p->category, $targetCategory) === 0) return true;
    
                // BLNT (Others) participates in ALL tiers
                if (
                    strcasecmp($p->category, 'Others') === 0 &&
                    strcasecmp($p->name, self::BETTER_LUCK_PRIZE) === 0
                ) {
                    return true;
                }
                return false;
            });
    
            if ($pool->isNotEmpty()) {
                $prizeToAward = $this->performWeightedDraw($pool);
            }
    
            unset($workingTiers[$targetCategory]);
        }
    
        // --- 9) Fallback draw from all eligible if tier pools empty ---
        if (!$prizeToAward) {
            $prizeToAward = $this->performWeightedDraw($eligiblePrizes);
            if (!$prizeToAward) {
                // Try BLNT explicitly if it has positive cumulative stock
                $blnt = $eligiblePrizes->firstWhere('name', self::BETTER_LUCK_PRIZE)
                    ?? Prize::where('campaign_id', $this->campaign->id)->where('name', self::BETTER_LUCK_PRIZE)->first();
                if ($blnt) {
                    $cum = 0; foreach ($cumulativeMonths as $m) { $cum += (int) ($blnt->{$m} ?? 0); }
                    if ($cum > 0) {
                        $this->decrementFromCumulative($blnt, $now);
                        return $blnt;
                    }
                }
                throw new \RuntimeException('No awardable prizes available.');
            }
        }
    
        // --- 10) Decrement from cumulative (current→backward) and return ---
        $toDecrement = Prize::where('id', $prizeToAward->id)->firstOrFail();
        
        $this->decrementFromCumulative($toDecrement, $now);
        
        return $toDecrement;
    }
    
    // Local closures so this method is self-contained.
    private function monthWindow(Carbon $ref): array
    {
        return [
            $ref->copy()->startOfMonth()->startOfDay(),
            $ref->copy()->endOfMonth()->endOfDay(),
        ];
    }

    /**
     * Returns cumulative stock column names up to and including "now".
     * Example (Nov): ['stock_oct','stock_nov']; (Dec): ['stock_oct','stock_nov','stock_dec'].
     * If "now" is before the first tracked month, returns just the first tracked month—safe default.
     */
    private function cumulativeStockColumnsFor(Carbon $now): array
    {
        $abbr = strtolower($now->format('M'));  // 'oct'/'nov'/'dec'
        $idx = array_search($abbr, self::CAMPAIGN_STOCK_MONTHS, true);

        if ($idx === false) {
            // If outside the campaign window, use all past columns up to the nearest bound.
            // This keeps weights sensible during tests.
            $idx = max(0, min(count(self::CAMPAIGN_STOCK_MONTHS) - 1, $now->month - 10)); // crude clamp if needed
        }

        $cols = [];
        for ($i = 0; $i <= $idx; $i++) {
            $cols[] = 'stock_' . self::CAMPAIGN_STOCK_MONTHS[$i];
        }
        return $cols;
    }

    /**
     * Decrement 1 unit from the first non-zero month bucket,
     * scanning from current month backward (e.g., Dec -> Nov -> Oct),
     * with a row lock to avoid race conditions.
     */
    private function decrementFromCumulative(Prize $prize, Carbon $now): void
    {
        // Lock the prize row inside the outer transaction
        $locked = Prize::where('id', $prize->id)->lockForUpdate()->firstOrFail();
    
        // Use the class constant as the source of truth for campaign months
        $months = self::CAMPAIGN_STOCK_MONTHS;
    
        // Find current month’s index; default to last if outside range
        $abbr = strtolower($now->format('M')); // 'oct'|'nov'|'dec'
        $idx  = array_search($abbr, $months, true);
        if ($idx === false) {
            $idx = count($months) - 1;
        }
    
        // Scan backwards: current -> previous -> ...
        for ($i = $idx; $i >= 0; $i--) {
            $col = 'stock_' . $months[$i];
            if ((int) ($locked->{$col} ?? 0) > 0) {
                $locked->decrement($col);
                return;
            }
        }
    
        throw new \RuntimeException('Out of stock across all eligible months for this prize.');
    }


    public function updatedSelectedStateId($stateId): void
    {
        // 1. Immediately reset all dependent properties.
        $this->reset(['selectedCityId', 'selectedAgentId']);
        $this->agents = [];
        $this->cities = collect();

        // 2. If a new state was actually selected, fetch new data.
        if ($stateId) {
            $this->cities = City::where('state_id', $stateId)->orderBy('name')->get();

            $state = State::find($stateId);
            if ($state) {
                $this->agents = Agent::where('is_active', true)
                    ->where('state', $state->name)
                    ->orderBy('city')
                    ->orderBy('shop_name')
                    ->get()
                    ->toArray();
            }
        }

        // 3. UPDATED: Dispatch an event to the browser
        $this->dispatch('agent-list-updated');
    }

    private function performWeightedDraw(Collection $prizes): ?object
    {
        $totalWeight = $prizes->sum('weight');
        if ($totalWeight <= 0) {
            // Fallback if all prizes are out of stock, return a random one
            return $prizes->isNotEmpty() ? $prizes->random() : null;
        }

        $randomNumber = mt_rand(1, (int)$totalWeight);

        foreach ($prizes as $prize) {
            if ($randomNumber <= $prize->weight) {
                return $prize;
            }
            $randomNumber -= $prize->weight;
        }

        return $prizes->last(); // Fallback in case of rounding errors
    }

    private function buildNearMissWheel(Prize $winningPrize): array
    {
        $visualTarget = $winningPrize->show ? $winningPrize->name : self::SPECIAL_PRIZE;
        $goldCoinPrize = $this->campaign->prizes()->where('name', 'Gold Coin')->where('name', '!=', $visualTarget)->first();
        $otherBumperPrize = $this->campaign->prizes()->where('category', 'Bumper')->where('show', true)->where('name', '!=', $visualTarget)->where('name', '!=', 'Gold Coin')->first();
        $otherPrizesPool = $this->campaign->prizes()->where('is_active', true)->where('show', true)->where('category', '!=', 'Bumper')->where('name', '!=', $visualTarget)->pluck('name')->shuffle();
        $wheelPrizes = collect();

        if ($goldCoinPrize && $otherBumperPrize) {
            $wheelPrizes->push($goldCoinPrize->name);
            $wheelPrizes->push($visualTarget);
            $wheelPrizes->push($otherBumperPrize->name);
        } elseif ($goldCoinPrize) {
            $wheelPrizes->push($visualTarget);
            $wheelPrizes->push($goldCoinPrize->name);
        } else {
            $wheelPrizes->push($visualTarget);
            if ($otherBumperPrize) {
                $wheelPrizes->push($otherBumperPrize->name);
            }
        }

        $wheelPrizes = $wheelPrizes->merge($otherPrizesPool);
        if (!$wheelPrizes->contains(self::BETTER_LUCK_PRIZE)) {
            $wheelPrizes->push(self::BETTER_LUCK_PRIZE);
        }
        if (!$wheelPrizes->contains(self::SPECIAL_PRIZE)) {
            $wheelPrizes->push(self::SPECIAL_PRIZE);
        }

        return $this->padPrizes($wheelPrizes->toArray(), self::WHEEL_SIZE, false);
    }


    private function padPrizes(array $prizes, int $targetSize, bool $shuffle = true, array $mustInclude = []): array
    {
        if (empty($prizes)) {
            return array_fill(0, $targetSize, 'Try Again!');
        }
    
        // Expand
        $padded = $prizes;
        while (count($padded) < $targetSize) {
            $padded = array_merge($padded, $prizes);
        }
    
        if ($shuffle) {
            shuffle($padded);
        }
    
        // Take first N
        $sliced = array_slice($padded, 0, $targetSize);
    
        // Ensure required labels are present in the final view
        foreach ($mustInclude as $label) {
            if (!in_array($label, $sliced, true)) {
                // If label exists in source pool, swap it in by replacing a non-critical slot
                if (in_array($label, $padded, true)) {
                    for ($i = $targetSize - 1; $i >= 0; $i--) {
                        if ($sliced[$i] !== self::SPECIAL_PRIZE && $sliced[$i] !== self::BETTER_LUCK_PRIZE) {
                            $sliced[$i] = $label;
                            break;
                        }
                    }
                } else {
                    // As a last resort, insert it and drop the last item
                    $sliced[$targetSize - 1] = $label;
                }
            }
        }
    
        return $sliced;
    }


    private function selectTierByWeight(array $weightedValues): string
    {
        $totalWeight = array_sum($weightedValues);
        if ($totalWeight <= 0) {
            // Fallback if no tiers are available
            return array_key_first($weightedValues) ?? 'Low-Level';
        }

        $randomNumber = mt_rand(1, $totalWeight);

        foreach ($weightedValues as $value => $weight) {
            if ($randomNumber <= $weight) {
                return $value;
            }
            $randomNumber -= $weight;
        }

        return array_key_last($weightedValues); // Fallback
    }

    private function setError(string $message): void
    {
        $this->currentState = 'error';
        $this->errorMessage = $message;
    }

    public function render()
    {
        return view('livewire.spin-page');
    }
}
