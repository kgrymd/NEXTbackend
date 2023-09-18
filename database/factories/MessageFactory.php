<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_group_id' => \App\Models\ChatGroup::factory(),
            'user_id' => \App\Models\User::factory(),
            'message_text' => fake()->paragraph(),
        ];
    }
}
