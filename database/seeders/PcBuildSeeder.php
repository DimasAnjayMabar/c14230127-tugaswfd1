<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PcBuild;
use App\Models\PcBuilds;
use App\Models\PcPart;
use App\Models\PcParts;

class PcBuildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a PC build
        $pcBuild = PcBuilds::create([
            'name' => 'Gaming Beast',
            'description' => 'High-end gaming PC with RGB lighting',
            'total_price' => 0, // We will update this later
        ]);

        // Define PC parts
        $parts = [
            ['type' => 'CPU', 'name' => 'Intel Core i9-13900K', 'price' => 550, 'picture' => 'images/cpu.png'],
            ['type' => 'GPU', 'name' => 'NVIDIA RTX 4090', 'price' => 1600, 'picture' => 'images/gpu.png'],
            ['type' => 'RAM', 'name' => 'Corsair Vengeance 32GB (2x16GB)', 'price' => 180, 'picture' => 'images/ram.png'],
            ['type' => 'Storage', 'name' => 'Samsung 980 Pro 2TB NVMe', 'price' => 250, 'picture' => 'images/ssd.png'],
            ['type' => 'Motherboard', 'name' => 'ASUS ROG Strix Z790-E', 'price' => 400, 'picture' => 'images/motherboard.png'],
            ['type' => 'Power Supply', 'name' => 'Corsair RM1000x 1000W', 'price' => 200, 'picture' => 'images/psu.png'],
            ['type' => 'Cooling', 'name' => 'NZXT Kraken Z73 AIO', 'price' => 280, 'picture' => 'images/cooling.png'],
            ['type' => 'Case', 'name' => 'Lian Li PC-O11 Dynamic', 'price' => 160, 'picture' => 'images/case.png'],
        ];

        // Create and attach parts to the build
        $totalPrice = 0;

        foreach ($parts as $partData) {
            $part = PcParts::create($partData);
            $quantity = ($partData['type'] === 'RAM') ? 2 : 1; // Example: 2 RAM sticks

            // Attach to pivot table with quantity
            $pcBuild->parts()->attach($part->id, ['quantity' => $quantity]);

            // Update total price based on quantity
            $totalPrice += $part->price * $quantity;
        }

        // Update total price of the build
        $pcBuild->update(['total_price' => $totalPrice]);
    }
}
