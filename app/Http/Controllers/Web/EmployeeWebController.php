<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeWebController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'department_id', 'status']);
        $employees = $this->employeeService->listEmployees($filters, 10);
        $departments = Department::all();

        $activeCount = Employee::where('status', 'active')->count();
        $avgSalary = Employee::avg('salary') ?? 0;

        return view('employees.index', compact('employees', 'departments', 'activeCount', 'avgSalary'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->employeeService->createEmployee($request->validated());

        return redirect()->route('employees.index')->with('success', 'Employee saved successfully!');
    }

    public function destroy(Employee $employee)
    {
        $this->employeeService->deleteEmployee($employee);

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
