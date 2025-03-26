<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use App\Models\Sales;
use App\Models\SalesDishes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Sales::with('employee', 'dishes');
            if ($request->deletes == 'true') {
                $data->withTrashed();
            }
            $response = $data->get();
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las ventas: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'table_number' => 'required',
                'tip' => 'required',
                'id_employee' => 'required',
                'dishes' => 'required'
            ]);

            $sale = new Sales;
            $sale->table_number = $request->table_number;
            $sale->tip = $request->tip;
            $sale->id_employee = $request->id_employee;
            $sale->save();

            foreach ($request->dishes as $dish) {
                $new_dish = new SalesDishes;
                $new_dish->id_sale = $sale->id;
                $new_dish->id_dish = $dish['id'];
                $new_dish->save();
            }
            DB::commit();
            return response()->json($sale);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la venta: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sale = Sales::findOrFail($id);
            $sale->update($request->all());
            return response()->json(['message' => 'Venta actualizada']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar la venta: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sale = Sales::findOrFail($id);
            $sale->delete();
            DB::commit();
            return response()->json(['message' => 'Venta eliminada']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar la venta: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $sale = Sales::withTrashed()->findOrFail($id);
            $sale->restore();
            DB::commit();
            return response()->json(['message' => 'Venta recuperada']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al recuperar la venta: ' . $e->getMessage()], 500);
        }
    }
}
