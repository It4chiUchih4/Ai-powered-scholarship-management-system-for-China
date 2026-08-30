<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password_hash' => bcrypt('password'),
            'role' => 'student',
            'academic_background' => 'HSC from Dhaka College, 2023',
            'preferred_field' => 'Computer Science',
            'cgpa' => 3.50,
            'language_proficiency' => 'IELTS 6.0',
            'degree_seeking' => 'bachelor',
            'plan' => 'free',
        ];
    }
}
