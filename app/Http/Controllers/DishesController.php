<?php

namespace App\Http\Controllers;

use App\Models\Dishes;
use Illuminate\Http\Request;

class DishesController extends Controller
{
    public function index(Request $request)
    {
        $data = Dishes::query();
        if ($request->deletes == 'true') {
            $data->withTrashed();
        }
        $response = $data->get();
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'make_time' => 'required',
            'make_price' => 'required',
            'sale_price' => 'required',
            'status' => 'required'
        ]);

        return Dishes::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $dish = Dishes::find($id);
        $dish->update($request->all());
        return response()->json(['message' => 'Platillo actualizado']);
    }

    public function status(Request $request, $id)
    {
        $dish = Dishes::withTrashed()->find($id);
        $dish->status = $request->status;
        $dish->save();
        return response()->json(['message' => 'Platillo actualizado -> estatus']);
    }

    public function destroy($id)
    {
        $dish = Dishes::find($id);
        $dish->delete();
        return response()->json(['message' => 'Platillo eliminado']);
    }

    public function restore($id)
    {
        $dish = Dishes::withTrashed()->find($id);
        $dish->restore();
        return response()->json(['message' => 'Platillo recuperado']);
    }
}
