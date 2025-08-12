<?php

namespace Database\Factories;

use App\Models\Credential;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class CredentialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Credential::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'idse' => $this->faker->password,
            'sipare' => $this->faker->password,
            'siec' => $this->faker->password,
            'useridse' => $this->faker->userName,
            'usersipare' => $this->faker->userName,
            'auxone' => $this->faker->word,
            'auxtwo' => $this->faker->word,
            'auxthree' => $this->faker->word,
            'iniciofiel' => $this->faker->date(),
            'finfiel' => $this->faker->date(),
            'iniciosello' => $this->faker->date(),
            'finsello' => $this->faker->date(),
        ];
    }
}
