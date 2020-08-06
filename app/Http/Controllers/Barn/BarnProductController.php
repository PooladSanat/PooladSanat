<?php

namespace App\Http\Controllers\Barn;

use App\BarnsProduct;
use App\Color;
use App\Http\Controllers\Controller;
use App\Product;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Mockery\Exception;
use Morilog\Jalali\Jalalian;
use Yajra\DataTables\DataTables;

class BarnProductController extends Controller
{
    public function list(Request $request)
    {
        $colors = Color::all();
        $products = Product::all();
        if ($request->ajax()) {
            $data = BarnsProduct::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_id', function ($row) {
                    return $row->product->label;
                })
                ->addColumn('color_id', function ($row) {
                    $color = Color::where('id', $row->color_id)->first();
                    if (!empty($color)) {
                        return $color->name;

                    } else {
                        return '';

                    }
                })
                ->addColumn('true', function ($row) {
                    return abs($row->Inventory - $row->NumberSold);
                })
                ->addColumn('action', function ($row) {
                    return $this->actions($row);
                })
                ->rawColumns([])
                ->make(true);

        }
        return view('barnproduct.list', compact('colors', 'products'));


    }

    public function store(Request $request)
    {

        $barn = BarnsProduct::where('product_id', $request->product)
            ->where('color_id', $request->color)
            ->first();
        if (!empty($request->product_id)) {
            BarnsProduct::updateOrCreate(['id' => $request->product_id],
                [
                    'product_id' => $request->product,
                    'color_id' => $request->color,
                    'Inventory' => $request->PhysicalInventory,
                ]);
            return response()->json(['success' => 'Product saved successfully.']);

        } else {
            if (!empty($barn)) {
                BarnsProduct::find($barn->id)->update(
                    [
                        'Inventory' => $barn->Inventory + $request->PhysicalInventory,
                    ]);
                return response()->json(['success' => 'Product saved successfully.']);

            } else {
                BarnsProduct::updateOrCreate(['id' => $request->product_id],
                    [
                        'product_id' => $request->product,
                        'color_id' => $request->color,
                        'Inventory' => $request->PhysicalInventory,
                    ]);
                return response()->json(['success' => 'Product saved successfully.']);

            }
        }


    }

    public function update($id)
    {
        $color = BarnsProduct::where('id', $id)
            ->first();
        return response()->json($color);
    }

    public function receiptproduct(Request $request)
    {
        $colors = Color::all();
        $products = Product::all();
        if ($request->ajax()) {
            $data = \DB::table('receiptproduct')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_id', function ($row) {
                    $name = Product::where('id', $row->product_id)->first();
                    return $name->label;
                })
                ->addColumn('color_id', function ($row) {
                    $name = Color::where('id', $row->color_id)->first();
                    return $name->name;
                })
                ->addColumn('created', function ($row) {
                    $created = Jalalian::forge($row->created_at)->format('Y/m/d');
                    return $created;
                })
                ->addColumn('action', function ($row) {
                    return $this->action($row);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('receiptproduct.list', compact('products', 'colors'));


    }

    public function receiptwizard($id)
    {

        $barn = \DB::table('receiptproduct')
            ->where('id', $id)->first();
        $check = \DB::table('barns_products')
            ->where('product_id', $barn->product_id)
            ->where('color_id', $barn->color_id)
            ->first();
        if ($check) {
            \DB::beginTransaction();
            try {
                \DB::table('barns_products')
                    ->where('id', $check->id)
                    ->update([
                        'Inventory' => $check->Inventory + $barn->number,
                        'NumberSold' => $check->NumberSold,
                    ]);
                \DB::table('receiptproduct')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        } else {
            try {
                \DB::table('barns_products')
                    ->insert([
                        'order_id' => $barn->order_id,
                        'product_id' => $barn->product_id,
                        'color_id' => $barn->color_id,
                        'Inventory' => $barn->number,
                    ]);
                \DB::table('receiptproduct')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        }
    }

    public function restore(Request $request)
    {
        $carbon = Carbon::now();
        \DB::table('receiptproduct')
            ->insert([
                'product_id' => $request->product,
                'color_id' => $request->color,
                'number' => $request->PhysicalInventory,
                'date' => Jalalian::forge($carbon)->format('Y/m/d'),
                'created_at' => $carbon,
            ]);
        return response()->json(['success' => 'success']);
    }

    public function actions($row)
    {

        $btn = null;
        if (\Gate::check('ویرایش موجودی انبار')) {
            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="editProduct">
                       <i class="fa fa-edit fa-lg" title="ویرایش"></i>
                       </a>&nbsp;&nbsp;';
        }

        return $btn;

    }

    public function action($row)
    {

        $btn = null;

        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="checkProduct">
                       <i class="fa fa-check fa-lg" title="تایید رسید"></i>
                       </a>&nbsp;&nbsp;';


        return $btn;

    }

}
