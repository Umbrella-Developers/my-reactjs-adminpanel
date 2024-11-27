<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => 'What is Laravel?',
            'answer' => 'Laravel is a web application framework with expressive, elegant syntax.',
            'section' => 'General',
            'sort' => 1,
            'status' => true,
        ];
    }
}
