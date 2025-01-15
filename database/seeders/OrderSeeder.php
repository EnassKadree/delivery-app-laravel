<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the first customer
        $customer = Customer::first();

        if ($customer) {
            // Create some orders for the customer
            Order::create([
                'status' => 'pending',
                'total_price' => 100.00,
                'address' => 'Baramkeh-Damascus',
                'customer_id' => $customer->id,
            ]);

            Order::create([
                'status' => 'inProgress',
                'total_price' => 250.50,
                'address' => 'Mazzeh-Damascus',
                'customer_id' => $customer->id,
            ]);

            Order::create([
                'status' => 'completed',
                'total_price' => 300.75,
                'address' => 'Malki-Damascus',
                'customer_id' => $customer->id,
            ]);
        }
    }
}
