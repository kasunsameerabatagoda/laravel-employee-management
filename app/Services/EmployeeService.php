<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepo
    ) {}

    public function listEmployees(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->employeeRepo->getPaginated($filters, $perPage);
    }

    public function getEmployee(int $id): Employee
    {
        $employee = $this->employeeRepo->findById($id);

        if (!$employee) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Employee with ID {$id} not found.");
        }

        return $employee;
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $projectIds = $data['project_ids'] ?? [];
            unset($data['project_ids']);

            $employee = $this->employeeRepo->create($data);

            if (!empty($projectIds)) {
                $this->employeeRepo->syncProjects($employee, $projectIds);
            }

            return $employee->load(['department', 'projects']);
        });
    }

    public function updateEmployee(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
            if (isset($data['project_ids'])) {
                $this->employeeRepo->syncProjects($employee, $data['project_ids']);
                unset($data['project_ids']);
            }

            return $this->employeeRepo->update($employee, $data);
        });
    }

    public function deleteEmployee(Employee $employee): bool
    {
        return DB::transaction(function () use ($employee) {
            return $this->employeeRepo->delete($employee);
        });
    }
}
