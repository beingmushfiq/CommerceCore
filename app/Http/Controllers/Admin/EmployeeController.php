<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Store;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->latest()->paginate(20);
        return view('admin.employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'designation' => 'required|string|max:255',
            'basic_salary' => 'required|numeric',
            'joining_date' => 'required|date',
            'employee_id' => 'required|string|unique:employees,employee_id'
        ]);

        $storeId = session('admin_store_id') ?? Store::first()->id;

        Employee::create(array_merge($validated, [
            'store_id' => $storeId,
            'status' => 'active'
        ]));

        return back()->with('success', 'Employee registered successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('user', 'payrolls', 'attendances');
        return view('admin.employees.show', compact('employee'));
    }
}
