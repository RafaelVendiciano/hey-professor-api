<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'question' => fake()->realText(15) . '?',
            'status' => 'draft',
            'user_id' => User::factory()
        ];
    }

    public function published(): self {

        return $this->state(function (array $attributes) {
            return [
                'status' => 'published'
            ];
        });
    }

    public function draft(): self {

        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft'
            ];
        });
    }

}
