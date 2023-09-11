<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recruitment_id' => 1,
            'user_id' => rand(1, 3), // ランダムにユーザーを設定 (1 ~ 3)
            'comment_text' => fake()->realTextBetween(10, 50),
        ];
    }
}
