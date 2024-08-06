<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Pais;    
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Ciudad;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EmpresaFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $paisId = Pais::inRandomOrder()->first()->id;
        $estadoId = Estado::inRandomOrder()->first()->id;
        $municipioId = Municipio::inRandomOrder()->first()->id;
        $ciudadId = Ciudad::inRandomOrder()->first()->id;

        return [
            'razon_social' => $this->faker->name(),
            'nombre_comercial' => $this->faker->unique()->name(),
            'pais_id' => $paisId,
            'estado_id' => $estadoId,
            'municipio_id' => $municipioId,
            'ciudad_id' => $ciudadId,
            'colonia_id' => $this->faker->optional()->randomDigit(),
           'codigo_postal' => $this->faker->postcode(),
            'calle' => $this->faker->streetName(),
            'numero_exterior' => $this->faker->buildingNumber(),
            'numero_interior' => $this->faker->buildingNumber(),
            'registro_patronal' => $this->faker->unique()->word(),
            'regimen_id' => $this->faker->optional()->randomDigit(),
 
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
