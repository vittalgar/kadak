<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Prize;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrizeSeeder extends Seeder
{
    public function run(): void
    {
        // Find an existing active campaign
        $campaign = Campaign::where('is_active', true)->first();

        // If no active campaign is found, create one directly instead of using a factory.
        if (!$campaign) {
            $campaign = Campaign::create([
                'name' => 'Consumer Dhamaka',
                'description' => 'Bharath Beverages Campaign - COnsume Dhamaka - From Oct 2025 to Dec 2025',
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-31',
                'is_active' => true,
            ]);
        }

        // Clear any existing prizes for this campaign to ensure a clean slate.
        $campaign->prizes()->delete();

        $prizes = [
            // Bumper/Grand Prizes (show = 0, hidden in "Many More Gifts")
            ['name' => 'Gold Coin', 'category' => 'Bumper', 'stock_oct' => 0, 'stock_nov' => 0, 'stock_dec' => 0, 'show' => 1],
            ['name' => 'Smart Phone', 'category' => 'Bumper', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],
            ['name' => 'Smart TV', 'category' => 'Bumper', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],

            // High Level Prizes
            ['name' => 'Mixer Grinder', 'category' => 'High-Level', 'stock_oct' => 2, 'stock_nov' => 2, 'stock_dec' => 1, 'show' => 1],
            ['name' => 'Watch/Custom Fitness Tracker', 'category' => 'High-Level', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],
            ['name' => 'Pressure Cooker', 'category' => 'High-Level', 'stock_oct' => 2, 'stock_nov' => 2, 'stock_dec' => 2, 'show' => 1],
            ['name' => 'Toaster', 'category' => 'High-Level', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],
            ['name' => 'Wireless Earbud', 'category' => 'High-Level', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],
            ['name' => 'Electric Kettle', 'category' => 'High-Level', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],

            // Mid Level Prizes
            ['name' => 'Cello Water Jar', 'category' => 'Mid-Level', 'stock_oct' => 26, 'stock_nov' => 20, 'stock_dec' => 20, 'show' => 1],
            ['name' => 'T-Shirt', 'category' => 'Mid-Level', 'stock_oct' => 133, 'stock_nov' => 100, 'stock_dec' => 100, 'show' => 1],
            ['name' => 'Travel Bag', 'category' => 'Mid-Level', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 1],
            ['name' => 'Cello Wow Happy Meal 2 Pcs Set - Lunch Box', 'category' => 'Mid-Level', 'stock_oct' => 53, 'stock_nov' => 40, 'stock_dec' => 40, 'show' => 0],
            ['name' => 'Tea Strainer - Steel', 'category' => 'Mid-Level', 'stock_oct' => 533, 'stock_nov' => 400, 'stock_dec' => 400, 'show' => 0],
            ['name' => 'Cello Zest Gift Set', 'category' => 'Mid-Level', 'stock_oct' => 80, 'stock_nov' => 60, 'stock_dec' => 60, 'show' => 0],
            ['name' => 'Peeler', 'category' => 'Mid-Level', 'stock_oct' => 266, 'stock_nov' => 200, 'stock_dec' => 200, 'show' => 0],
            ['name' => 'Customized Bluetooth Speaker', 'category' => 'Mid-Level', 'stock_oct' => 1, 'stock_nov' => 1, 'stock_dec' => 1, 'show' => 0],
            ['name' => 'Non-stick Coated Frypan', 'category' => 'Mid-Level', 'stock_oct' => 5, 'stock_nov' => 4, 'stock_dec' => 4, 'show' => 01],
            ['name' => 'Plastic Tray', 'category' => 'Mid-Level', 'stock_oct' => 187, 'stock_nov' => 140, 'stock_dec' => 140, 'show' => 0],
            ['name' => 'Plastic Bucket', 'category' => 'Mid-Level', 'stock_oct' => 133, 'stock_nov' => 100, 'stock_dec' => 100, 'show' => 0],
            ['name' => 'Plastic Stool', 'category' => 'Mid-Level', 'stock_oct' => 26, 'stock_nov' => 20, 'stock_dec' => 20, 'show' => 0],
            ['name' => 'Brush Holder', 'category' => 'Mid-Level', 'stock_oct' => 533, 'stock_nov' => 400, 'stock_dec' => 400, 'show' => 0],
            ['name' => 'Kewin 3Pcs Tea Sugar', 'category' => 'Mid-Level', 'stock_oct' => 1600, 'stock_nov' => 1200, 'stock_dec' => 1200, 'show' => 0],
            ['name' => 'Chapati Box', 'category' => 'Mid-Level', 'stock_oct' => 133, 'stock_nov' => 100, 'stock_dec' => 100, 'show' => 0],
            ['name' => 'Roti Basket - Regular', 'category' => 'Mid-Level', 'stock_oct' => 53, 'stock_nov' => 40, 'stock_dec' => 40, 'show' => 0],

            // Low Level Prizes
            ['name' => 'Mobile Stand', 'category' => 'Low-Level', 'stock_oct' => 4000, 'stock_nov' => 3000, 'stock_dec' => 3000, 'show' => 1],
            ['name' => 'Spoon', 'category' => 'Low-Level', 'stock_oct' => 13334, 'stock_nov' => 10000, 'stock_dec' => 10000, 'show' => 1],
            ['name' => 'Tea Cup - Steel', 'category' => 'Low-Level', 'stock_oct' => 2666, 'stock_nov' => 2000, 'stock_dec' => 2000, 'show' => 0],
            ['name' => 'Tea Packet - ₹10', 'category' => 'Low-Level', 'stock_oct' => 5553, 'stock_nov' => 4165, 'stock_dec' => 4165, 'show' => 0],
            ['name' => 'Notepad', 'category' => 'Low-Level', 'stock_oct' => 2666, 'stock_nov' => 2000, 'stock_dec' => 2000, 'show' => 0],
            ['name' => 'Pen', 'category' => 'Low-Level', 'stock_oct' => 2666, 'stock_nov' => 2000, 'stock_dec' => 2000, 'show' => 0],
            ['name' => 'Diwali Poster', 'category' => 'Low-Level', 'stock_oct' => 1333, 'stock_nov' => 1000, 'stock_dec' => 1000, 'show' => 0],

            // Others
            ['name' => 'Better Luck Next Time', 'category' => 'Others', 'stock_oct' => 4000, 'stock_nov' => 3000, 'stock_dec' => 3000, 'show' => 1],
        ];

        // Add the special "Many More Exciting Gifts" category to be shown on the wheel
        $prizes[] = ['name' => 'Many More Attractive Gifts', 'category' => 'Low-Level', 'stock_oct' => 18487, 'stock_nov' => 13870, 'stock_dec' => 13870, 'show' => 1];

        foreach ($prizes as $prizeData) {
            // Assign weight based on total stock for items shown on the wheel
            $totalStock = ($prizeData['stock_oct'] ?? 0) + ($prizeData['stock_nov'] ?? 0) + ($prizeData['stock_dec'] ?? 0);
            $campaign->prizes()->create($prizeData);
        }
    }
}
