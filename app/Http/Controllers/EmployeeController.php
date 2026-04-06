<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Show all employees
    public function index()
    {
        $employees = Employee::with('department', 'user')->get();
        return view('employees.index', compact('employees'));
    }

    // Show create form
    public function create()
    {
        $users = User::doesntHave('employee')->get();
        $departments = Department::all();

        return view('employees.create', compact('users', 'departments'));
    }

    // Store employee
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'nullable|date',
        ]);

        Employee::create([
            'user_id' => $request->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'department_id' => $request->department_id,
            'hire_date' => $request->hire_date,
        ]);

        return redirect()->route('employees.index');
    }

    // Display single employee
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    // Show form to edit employee
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    // Update employee in database
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'nullable|date',
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee updated!');
    }

    // Delete employee
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted!');
    }
}
