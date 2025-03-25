<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index()
    {
        return response()->json(Employee::withTrashed()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        return Employee::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        $employee->update($request->all());
        return response()->json(['message' => 'Empleado actualizado']);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
        return response()->json(['message' => 'Empleado eliminado']);
    }

    public function restore($id)
    {
        $employee = Employee::withTrashed()->find($id);
        $employee->restore();
        return response()->json(['message' => 'Empleado recuperado']);
    }
}
