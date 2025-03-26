<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Dishes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DishesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Dishes::query();
            if ($request->deletes == 'true') {
                $data->withTrashed();
            }
            $response = $data->get();
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los platillos: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'make_time' => 'required',
                'make_price' => 'required',
                'sale_price' => 'required',
                'status' => 'required'
            ]);

            $dish = Dishes::create($request->all());

            DB::commit();
            return response()->json($dish, 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el platillo: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dish = Dishes::findOrFail($id);
            $dish->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Platillo actualizado']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el platillo: ' . $e->getMessage()], 500);
        }
    }

    public function status(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dish = Dishes::withTrashed()->findOrFail($id);
            $dish->status = $request->status;
            $dish->save();

            DB::commit();
            return response()->json(['message' => 'Platillo actualizado -> estatus']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el estatus del platillo: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $dish = Dishes::findOrFail($id);
            $dish->delete();

            DB::commit();
            return response()->json(['message' => 'Platillo eliminado']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar el platillo: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $dish = Dishes::withTrashed()->findOrFail($id);
            $dish->restore();

            DB::commit();
            return response()->json(['message' => 'Platillo recuperado']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al recuperar el platillo: ' . $e->getMessage()], 500);
        }
    }
}