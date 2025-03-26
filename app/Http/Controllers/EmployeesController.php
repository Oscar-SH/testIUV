<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Employee::query();
            if ($request->deletes == 'true') {
                $data->withTrashed();
            }
            $response = $data->get();
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los empleados: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
            ]);

            $employee = Employee::create($request->all());

            DB::commit();
            return response()->json($employee, 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el empleado: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $employee->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Empleado actualizado']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el empleado: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            DB::commit();
            return response()->json(['message' => 'Empleado eliminado']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar el empleado: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::withTrashed()->findOrFail($id);
            $employee->restore();

            DB::commit();
            return response()->json(['message' => 'Empleado recuperado']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al recuperar el empleado: ' . $e->getMessage()], 500);
        }
    }
}