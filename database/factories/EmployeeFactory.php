<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'designation' => fake()->randomElement(['Software Engineer', 'Senior Engineer', 'Product Manager', 'QA Specialist', 'HR Exec']),
            'salary' => fake()->randomFloat(2, 40000, 150000),
            'status' => fake()->randomElement(['active', 'inactive', 'on_leave']),
            'joined_date' => fake()->date('Y-m-d', '-1 year'),
        ];
    }
}
