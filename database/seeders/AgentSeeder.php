<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Agent;
use App\Models\User;
use App\Models\Role;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use a transaction to ensure data integrity
        DB::transaction(function () {
            // Find the ID for the 'Agent' role, which we are using for Agents
            $agentRoleId = Role::where('name', 'Agent')->value('id');

            if (!$agentRoleId) {
                // If the role doesn't exist, we can't create agents.
                // You can use `$this->command->error()` to show a message in the console.
                return;
            }

            $agents = [
                [
                    'shop_name' => 'Swaminarayan Traders',
                    'contact_person' => 'Bhavik Patel',
                    'location' => 'Padampura ',
                    'city' => 'Aurangabad',
                    'state' => 'Maharashtra',
                    'phone_number_1' => '9367303030',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Vaishali Enterprises',
                    'contact_person' => 'Kailash Kumar',
                    'location' => 'Opp Plaza Cinema',
                    'city' => 'Ajmer',
                    'state' => 'Rajasthan',
                    'phone_number_1' => '9587051741',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Lakhani Agencies',
                    'contact_person' => 'Yash Lakjani',
                    'location' => 'Hotel Shanti Niketan',
                    'city' => 'Banswara',
                    'state' => 'Rajasthan',
                    'phone_number_1' => '7725998230',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Ranka Genral Store',
                    'contact_person' => 'Suresh Ranka',
                    'location' => 'Near Police Control Room',
                    'city' => 'Bhilwara',
                    'state' => 'Rajasthan',
                    'phone_number_1' => '9828178930',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Sohanlal & Sons',
                    'contact_person' => 'Sunil Kumar',
                    'location' => 'Hanumanji Rd-312601',
                    'city' => 'Chittorgarh',
                    'state' => 'Rajasthan',
                    'phone_number_1' => '9782475221',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Shree Ratan Traders',
                    'contact_person' => 'Ayush Lodha',
                    'location' => 'Khachrod Ujjain-456224',
                    'city' => 'Khachrod',
                    'state' => 'Madhya Pradesh',
                    'phone_number_1' => '7415144585',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Sharda Traders',
                    'contact_person' => 'Kalpesh Koradiya',
                    'location' => 'Tilak Road-444001',
                    'city' => 'Akola',
                    'state' => 'Maharashtra',
                    'phone_number_1' => '9370655404',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Ram Company ',
                    'contact_person' => 'Sunil Vyas',
                    'location' => 'Gautam Nagar, Kalakhet',
                    'city' => 'Mandsaur',
                    'state' => 'Madhya Pradesh',
                    'phone_number_1' => '7389643028',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Narottamdas Enterprises',
                    'contact_person' => 'Kushank Patel',
                    'location' => 'New Siyaganj-452007',
                    'city' => 'Indore',
                    'state' => 'Madhya Pradesh',
                    'phone_number_1' => '6261922240',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Parsawat Marketings',
                    'contact_person' => 'Basant Parsawat',
                    'location' => 'Inside Nagauri Gate, Main Mkt',
                    'city' => 'Didwana',
                    'state' => 'Rajasthan',
                    'phone_number_1' => '9829367111',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Maanya Traders',
                    'contact_person' => 'Mallikarjuna Devadula',
                    'location' => 'Near Flower Market, Bandimetta',
                    'city' => 'Kurnool',
                    'state' => 'Andhra Pradesh',
                    'phone_number_1' => '9393033030',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'R C Sons',
                    'contact_person' => 'Suresh Agarwal',
                    'location' => 'Dumartarai, Deopur',
                    'city' => 'Raipur',
                    'state' => 'Chattisgarh',
                    'phone_number_1' => '9584231000',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Ganesh Traders',
                    'contact_person' => 'Chetan Gurnani',
                    'location' => '54/1 Fawara Chowk Ujjain',
                    'city' => 'Ujjain',
                    'state' => 'Madhya Pradesh',
                    'phone_number_1' => '9406625198',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'New Abhivandan Tea Department',
                    'contact_person' => 'Abhijit jaypal Mahajan',
                    'location' => 'Laxmipuri',
                    'city' => 'Kolhapur',
                    'state' => 'Maharashtra',
                    'phone_number_1' => '9890812555',
                    'phone_number_2' => '8985303030',
                ],
                [
                    'shop_name' => 'Bharath Beverages',
                    'contact_person' => 'Vimarshini',
                    'location' => 'Kadri Road',
                    'city' => 'Mangaluru',
                    'state' => 'Karnataka',
                    'phone_number_1' => '8951713030',
                    'phone_number_2' => '8985303030',
                ],
            ];

            foreach ($agents as $agentData) {
                // Create the Agent record
                $agent = Agent::create($agentData);

                // Create the corresponding User record for the Agent's login
                User::create([
                    'name' => $agent->contact_person,
                    'email' => $agent->phone_number_1 . "@bharathgroup.com", // Login ID is their Phone Number 1
                    'password' => Hash::make('Agent@123'), // Default password
                    'role_id' => $agentRoleId,
                    'agent_id' => $agent->id, // Link the user to the agent
                ]);
            }
        });
    }
}
