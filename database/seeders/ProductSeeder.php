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
                'name' => 'Kadak Pyali Blue Premium Assam Tea - 250g',
                'sku' => 'KPBPAT-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Blue Premium Assam Tea - 500g',
                'sku' => 'KPBPAT-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Blue Premium Assam Tea - 1kg',
                'sku' => 'KPBPAT-1000',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aduku Premium Assam Tea - 250g',
                'sku' => 'APAT-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aduku Premium Assam Tea - 500g',
                'sku' => 'APAT-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aduku Premium Assam Tea - 1kg',
                'sku' => 'APAT-1000',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Gold - 250g',
                'sku' => 'KG-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Gold - 500g',
                'sku' => 'KG-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Gold - 1kg',
                'sku' => 'KG-1000',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali - 250g',
                'sku' => 'KP-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali - 500g',
                'sku' => 'KP-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali - 1kg',
                'sku' => 'KP-1000',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Adrak - 250g',
                'sku' => 'KPA-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Red Label - 250g',
                'sku' => 'KPRL-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kadak Pyali Elachi - 250g',
                'sku' => 'KPE-250',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
