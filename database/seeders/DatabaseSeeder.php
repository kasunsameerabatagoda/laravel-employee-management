<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $engineering = Department::create([
            'name' => 'Engineering',
            'code' => 'ENG',
            'description' => 'Software engineering and IT operations',
        ]);

        $hr = Department::create([
            'name' => 'Human Resources',
            'code' => 'HR',
            'description' => 'HR management and recruitment',
        ]);

        $projectA = Project::create([
            'name' => 'Mobile App Redesign',
            'description' => 'Rebuilding Flutter application for mobile client',
            'start_date' => now()->subMonths(2),
        ]);

        $projectB = Project::create([
            'name' => 'Cloud Migration',
            'description' => 'Migrating AWS infrastructure to Kubernetes',
            'start_date' => now()->subMonth(),
        ]);

        $emp1 = Employee::create([
            'department_id' => $engineering->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'designation' => 'Senior Backend Developer',
            'salary' => 95000.00,
            'status' => 'active',
            'joined_date' => now()->subYears(2),
        ]);

        $emp1->projects()->attach([$projectA->id => ['role' => 'Lead Developer'], $projectB->id => ['role' => 'Architect']]);

        Employee::factory()->count(10)->create([
            'department_id' => $engineering->id,
        ]);

        Employee::factory()->count(5)->create([
            'department_id' => $hr->id,
        ]);
    }
}
