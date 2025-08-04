<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisBuku>
 */
class JenisBukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_jenis' => $this->faker->unique()->words(2, true),
            'kode_jenis' => $this->faker->unique()->regexify('[A-Z]{2,4}'),
            'deskripsi' => $this->faker->optional()->sentence(),
            'status' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }

    /**
     * Indicate that the jenis buku is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the jenis buku is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
} 