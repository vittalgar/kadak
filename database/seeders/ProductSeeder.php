<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Aduku Premium Assam Tea - 250g',
                'image_url' => '/images/products/aduku-premium-assam-tea.webp',
                'sku' => 'APAT-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Gold - 250g',
                'image_url' => '/images/products/kadak-gold.webp',
                'sku' => 'KG-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Adrak - 250g',
                'image_url' => '/images/products/kadak-pyali-adrak.webp',
                'sku' => 'KPA-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Red Label - 250g',
                'image_url' => '/images/products/kadak-pyali-red-label.webp',
                'sku' => 'KPRL-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Elaichi - 250g',
                'image_url' => '/images/products/kadak-pyali-elaichi.webp',
                'sku' => 'KPE-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Blue - 250g',
                'image_url' => '/images/products/kadak-pyali-blue.webp',
                'sku' => 'KPB-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Yellow - 250g',
                'image_url' => '/images/products/kadak-pyali-yellow.webp',
                'sku' => 'KPY-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aduku Premium Assam Tea - 500g',
                'image_url' => '/images/products/aduku-premium-assam-tea.webp',
                'sku' => 'APAT-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Gold - 500g',
                'image_url' => '/images/products/kadak-gold.webp',
                'sku' => 'KG-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Adrak - 500g',
                'image_url' => '/images/products/kadak-pyali-adrak.webp',
                'sku' => 'KPA-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Red Label - 500g',
                'image_url' => '/images/products/kadak-pyali-red-label.webp',
                'sku' => 'KPRL-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Elaichi - 500g',
                'image_url' => '/images/products/kadak-pyali-elaichi.webp',
                'sku' => 'KPE-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Blue - 500g',
                'image_url' => '/images/products/kadak-pyali-blue.webp',
                'sku' => 'KPB-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Yellow - 500g',
                'image_url' => '/images/products/kadak-pyali-yellow.webp',
                'sku' => 'KPY-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
