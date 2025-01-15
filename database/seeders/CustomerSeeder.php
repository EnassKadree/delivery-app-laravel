<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'phone' => '1111111111',
            'email' => 'manar@customer.com',
            'password' => Hash::make('manar'),
            'role' => 'customer',
        ]);

        // Create a customer linked to this user
        Customer::create([
            'user_id' => $user->id,
            'first_name' => 'Manar',
            'last_name' => 'Aljarkas',
            'image' => null, // You can add a path to an image here if needed
            'address' => 'Damascus, Syria',
        ]);
    }
}
