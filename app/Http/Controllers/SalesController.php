<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $data = Sales::with('employee', 'dish');
        if ($request->deletes == 'true') {
            $data->withTrashed();
        }
        $response = $data->get();
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required',
            'tip' => 'required',
            'id_employee' => 'required',
            'id_dish' => 'required'
        ]);

        return Sales::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $sale = Sales::find($id);
        $sale->update($request->all());
        return response()->json(['message' => 'Venta actualizada']);
    }

    public function destroy($id)
    {
        $sale = Sales::find($id);
        $sale->delete();
        return response()->json(['message' => 'Venta eliminada']);
    }

    public function restore($id)
    {
        $sale = Sales::withTrashed()->find($id);
        $sale->restore();
        return response()->json(['message' => 'Venta recuperada']);
    }
}
