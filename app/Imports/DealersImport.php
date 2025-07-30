<?php

namespace App\Imports;

use App\Models\Dealer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DealersImport implements ToModel, WithHeadingRow, WithValidation
{
    private $dealerRoleId;

    public function __construct()
    {
        $this->dealerRoleId = Role::where('name', 'Dealer')->value('id');
    }

    public function model(array $row)
    {
        // THE FIX: Use a transaction and explicitly link the two models.
        return DB::transaction(function () use ($row) {
            $dealer = Dealer::create([
                'dealership_name' => $row['dealership_name'],
                'contact_person'  => $row['contact_person'],
                'phone_number'    => $row['phone_number'],
                'city'            => $row['city'],
                'state'           => $row['state'],
            ]);

            User::create([
                'name'      => $row['contact_person'],
                'email'     => $row['email'],
                'password'  => Hash::make($row['phone_number']),
                'role_id'   => $this->dealerRoleId,
                'dealer_id' => $dealer->id, // This is the crucial link
            ]);

            return $dealer;
        });
    }

    public function rules(): array
    {
        return [
            'dealership_name' => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'phone_number'    => 'required|numeric|digits:10|unique:dealers,phone_number',
            'city'            => 'required|string|max:100',
            'state'           => 'required|string|max:100',
        ];
    }
}
