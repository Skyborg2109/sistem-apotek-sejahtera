<?php

namespace Database\Factories;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchasePrice = $this->faker->numberBetween(5000, 50000);
        return [
            'name' => $this->faker->words(3, true),
            'sku' => 'MED-' . $this->faker->unique()->numberBetween(1000, 9999),
            'category' => $this->faker->randomElement(['Antibiotik', 'Obat Bebas', 'Vitamin & Suplemen', 'Obat Resep']),
            'purchase_price' => $purchasePrice,
            'selling_price' => $purchasePrice * 1.25,
            'stock' => $this->faker->numberBetween(0, 200),
            'unit' => $this->faker->randomElement(['Strip', 'Botol', 'Box', 'Tablet']),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+3 years'),
            'supplier_id' => \App\Models\Supplier::factory(),
        ];
    }
}
