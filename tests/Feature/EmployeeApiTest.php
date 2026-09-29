<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_employees(): void
    {
        $department = Department::factory()->create();
        Employee::factory()->count(3)->create(['department_id' => $department->id]);

        $response = $this->getJson('/api/v1/employees');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'first_name', 'last_name', 'email', 'department']
                     ]
                 ]);
    }

    public function test_can_create_employee_with_valid_data(): void
    {
        $department = Department::factory()->create();

        $payload = [
            'department_id' => $department->id,
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice.smith@example.com',
            'phone' => '555-0199',
            'designation' => 'Tech Lead',
            'salary' => 110000.00,
            'status' => 'active',
            'joined_date' => '2024-01-15',
        ];

        $response = $this->postJson('/api/v1/employees', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.email', 'alice.smith@example.com');

        $this->assertDatabaseHas('employees', [
            'email' => 'alice.smith@example.com',
        ]);
    }

    public function test_cannot_create_employee_with_duplicate_email(): void
    {
        $department = Department::factory()->create();
        Employee::factory()->create([
            'department_id' => $department->id,
            'email' => 'duplicate@example.com'
        ]);

        $payload = [
            'department_id' => $department->id,
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'email' => 'duplicate@example.com',
            'designation' => 'Developer',
            'salary' => 80000,
            'status' => 'active',
            'joined_date' => '2024-02-01',
        ];

        $response = $this->postJson('/api/v1/employees', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_can_update_employee(): void
    {
        $department = Department::factory()->create();
        $employee = Employee::factory()->create(['department_id' => $department->id]);

        $response = $this->putJson("/api/v1/employees/{$employee->id}", [
            'designation' => 'Principal Architect',
            'salary' => 140000.00,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.designation', 'Principal Architect');
    }

    public function test_can_delete_employee(): void
    {
        $department = Department::factory()->create();
        $employee = Employee::factory()->create(['department_id' => $department->id]);

        $response = $this->deleteJson("/api/v1/employees/{$employee->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
