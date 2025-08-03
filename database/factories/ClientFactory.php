<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use App\Models\Counter;
use App\Models\Regime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        return [
            'user_id' => User::factory(),
            'counter_id' => Counter::inRandomOrder()->first()?->id,
            'regime_id' => Regime::inRandomOrder()->first()?->id,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'phone' => $this->faker->unique()->numerify('##########'),
            'name' => $firstName = $this->faker->firstName,
            'last_name' => $lastName = $this->faker->lastName,
            'full_name' => "$firstName $lastName",
            'email' => $this->faker->unique()->safeEmail,
            'siec' => $this->faker->word,
            'useridse' => $this->faker->userName,
            'idse' => $this->faker->password,
            'usersipare' => $this->faker->userName,
            'sipare' => $this->faker->password,
            'address' => $this->faker->streetAddress,
            'country' => $this->faker->country,
            'localities' => $this->faker->city,
            'street' => $this->faker->streetName,
            'col' => $this->faker->streetSuffix,
            'num_ext' => $this->faker->buildingNumber,
            'rfc' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
            'curp' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{6}[H,M][A-Z]{5}[0-9]{2}'),
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'cp' => $this->faker->postcode,
            'nss' => $this->faker->unique()->numerify('###########'),
            'note' => $this->faker->sentence,
            'token' => Str::random(8),
            'birthdate' => $this->faker->optional()->date(),
        ];

    }
}