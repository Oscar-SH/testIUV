<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Exception;
use App\Models\Sales;
use App\Models\SalesDishes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use stdClass;

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
                $new_dish = new SalesDishes();
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

    public function getDataGraphic(Request $request)
    {
        $categories = collect();
        $data_series = collect();
        $date = $request->date ? Carbon::parse($request->date) : Carbon::now();
        $range = ["start" => 0, "end" => 24];
        while ($range['start'] < $range['end']) {
            $range['start']++;
            $sales = Sales::where(DB::raw("CAST(strftime('%H', created_at) AS INTEGER)"), '=', $range['start'])
                ->whereDate('created_at', '=', $date)->get();
            $data_series->add($sales->count());
            $categories->add(str_pad($range['start'], 2, "0", STR_PAD_LEFT));
        }

        $series = new stdClass;
        $series->data = $data_series;
        $series->name = "Ventas";

        $data = new stdClass;
        $data->series = [$series];
        $data->categories = $categories;

        return response()->json($data);
    }

    public function getCatTables()
    {
        return response()->json(Sales::select('table_number')->groupBy('table_number')->get());
    }

    public function getDataExcel(Request $request)
    {
        $response = collect();
        $date = $request->date ? Carbon::parse($request->date)->toDateString() : Carbon::now()->toDateString();

        $rows = Sales::select('*', DB::raw("strftime('%H', created_at) as hour"))
            ->whereDate('created_at', $date)
            ->where('table_number', '=', $request->table_number)
            ->where('id_employee', '=', $request->id_employee)
            ->with(['dishes', 'employee']) // Incluye las relaciones
            ->get();

        $hours = $rows->groupBy('hour');

        foreach ($hours as $hourKey => $sales) {
            $data = new stdClass;
            $data->table_number = $request->table_number;
            $data->employee = optional($sales->first()->employee)->name ?? 'Sin empleado';
            $data->hour = $hourKey;
            $data->total_tip = $sales->sum('tip');
            $data->total_sale = $sales->reduce(function ($carry, $sale) {
                return $carry + $sale->dishes->sum('sale_price');
            }, 0);
            $data->total = $data->total_tip + $data->total_sale;
            $response->add($data);
        }

        return response()->json($response);
    }
}
