<?php

namespace App\Http\Controllers\Barn;

use App\BarnsProduct;
use App\Color;
use App\Http\Controllers\Controller;
use App\Polymeric;
use App\Product;
use App\StoreColor;
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
            $data = BarnsProduct::orderBy('product_id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('Number', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="detail-factor">
                      ' . $row->NumberSold . '
                       </a>';
                    return $btn;
                })
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
                ->rawColumns(['Number', 'action'])
                ->make(true);

        }
        return view('barnproduct.list', compact('colors', 'products'));


    }


    public function ListList(Request $request)
    {
        if ($request->ajax()) {
            $id = BarnsProduct::where('id', $request->detail_factor)->first();
            $product = Product::where('id', $id->product_id)->first();
            $color = Color::where('id', $id->color_id)->first();

            $data = \DB::table('View_Scheduling')
                ->where('product', $product->label)
                ->where('color', $color->name)
                ->whereNull('total')
                ->whereNull('statusfull')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->rawColumns([])
                ->make(true);
        }
        return view('barnproduct.list');

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
                ->orderBy('date', 'desc')
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
                ->addColumn('time', function ($row) {
                    $created = Carbon::make($row->created_at)->format('H:i:s');
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
        $v = verta();
        $carbon = Carbon::now()->timezone('Asia/Tehran');
        \DB::table('receiptproduct')
            ->insert([
                'product_id' => $request->product,
                'color_id' => $request->color,
                'number' => $request->PhysicalInventory,
                'date' => Jalalian::forge($carbon)->format('Y/m/d'),
                'created_at' => $carbon,
                'Year' => $v->year,
                'Month' => $v->month,
            ]);
        return response()->json(['success' => 'success']);
    }


    public function receiptreturn(Request $request)
    {
        $colors = Color::all();
        $products = Product::all();
        if ($request->ajax()) {
            $data = \DB::table('receiptreturn')
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
                ->addColumn('time', function ($row) {
                    $created = Carbon::make($row->created_at)->format('H:i:s');
                    return $created;
                })
                ->addColumn('action', function ($row) {
                    return $this->actionn($row);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('receiptreturn.list', compact('products', 'colors'));


    }

    public function restorereturn(Request $request)
    {
        $carbon = Carbon::now()->timezone('Asia/Tehran');
        \DB::table('receiptreturn')
            ->insert([
                'product_id' => $request->product,
                'color_id' => $request->color,
                'number' => $request->PhysicalInventory,
                'date' => Jalalian::forge($carbon)->format('Y/m/d'),
                'created_at' => $carbon,
            ]);
        return response()->json(['success' => 'success']);
    }

    public function receiptwizardreturn($id)
    {

        $barn = \DB::table('receiptreturn')
            ->where('id', $id)->first();
        $check = \DB::table('barn_returns')
            ->where('product_id', $barn->product_id)
            ->where('color_id', $barn->color_id)
            ->first();
        if ($check) {
            \DB::beginTransaction();
            try {
                \DB::table('barn_returns')
                    ->where('id', $check->id)
                    ->update([
                        'Inventory' => $check->Inventory + $barn->number,
                    ]);
                \DB::table('receiptreturn')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        } else {
            try {
                \DB::table('barn_returns')
                    ->insert([
                        'product_id' => $barn->product_id,
                        'color_id' => $barn->color_id,
                        'Inventory' => $barn->number,
                    ]);
                \DB::table('receiptreturn')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        }
    }

    public function receiptcarncolor(Request $request)
    {
        $colors = Color::all();
        $products = Color::all();
        if ($request->ajax()) {
            $data = \DB::table('receiptcarncolor')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('croncolor_id', function ($row) {
                    $name = \DB::table('colors')->where('id', $row->croncolor_id)->first();
                    return $name->manufacturer;
                })
                ->addColumn('color_id', function ($row) {
                    $name = Color::where('id', $row->croncolor_id)->first();
                    return $name->name;
                })
                ->addColumn('created', function ($row) {
                    $created = Jalalian::forge($row->created_at)->format('Y/m/d');
                    return $created;
                })
                ->addColumn('time', function ($row) {
                    $created = Carbon::make($row->created_at)->format('H:i:s');
                    return $created;
                })
                ->addColumn('action', function ($row) {
                    return $this->actionnn($row);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('receiptcroncolor.list', compact('products', 'colors'));


    }

    public function restorecarncolor(Request $request)
    {
        $carbon = Carbon::now()->timezone('Asia/Tehran');
        \DB::table('receiptcarncolor')
            ->insert([
                'croncolor_id' => $request->product,
                'color_id' => null,
                'number' => $request->PhysicalInventory,
                'date' => Jalalian::forge($carbon)->format('Y/m/d'),
                'created_at' => $carbon,
            ]);
        return response()->json(['success' => 'success']);
    }

    public function receiptwizardcroncolor($id)
    {

        $barn = \DB::table('receiptcarncolor')
            ->where('id', $id)->first();
        $check = \DB::table('barn_colors')
            ->where('color_id', $barn->croncolor_id)
            ->first();
        if ($check) {
            \DB::beginTransaction();
            try {
                \DB::table('barn_colors')
                    ->where('id', $check->id)
                    ->update([
                        'PhysicalInventory' => $check->PhysicalInventory + $barn->number,
                    ]);
                \DB::table('receiptcarncolor')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        } else {
            try {
                \DB::table('barn_colors')
                    ->insert([
                        'color_id' => $barn->croncolor_id,
                        'PhysicalInventory' => $barn->number,
                    ]);
                \DB::table('receiptcarncolor')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        }
    }


    public function receiptpolim(Request $request)
    {
        $colors = Polymeric::all();
        $products = Polymeric::all();
        if ($request->ajax()) {
            $data = \DB::table('receiptpolim')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('croncolor_id', function ($row) {
                    $name = Polymeric::where('id', $row->polim_id)->first();
                    return $name->grid;
                })
                ->addColumn('color_id', function ($row) {
                    $name = Polymeric::where('id', $row->polim_id)->first();
                    return $name->type;
                })
                ->addColumn('created', function ($row) {
                    $created = Jalalian::forge($row->created_at)->format('Y/m/d');
                    return $created;
                })
                ->addColumn('time', function ($row) {
                    $created = Carbon::make($row->created_at)->format('H:i:s');
                    return $created;
                })
                ->addColumn('action', function ($row) {
                    return $this->actionnn($row);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('receiptpolim.list', compact('products', 'colors'));


    }

    public function restorepolim(Request $request)
    {
        $carbon = Carbon::now()->timezone('Asia/Tehran');
        \DB::table('receiptpolim')
            ->insert([
                'polim_id' => $request->product,
                'color_id' => null,
                'number' => $request->PhysicalInventory,
                'date' => Jalalian::forge($carbon)->format('Y/m/d'),
                'created_at' => $carbon,
            ]);
        return response()->json(['success' => 'success']);
    }

    public function receiptwizardpolim($id)
    {

        $barn = \DB::table('receiptpolim')
            ->where('id', $id)->first();
        $check = \DB::table('barn_materials')
            ->where('polymeric_id', $barn->polim_id)
            ->first();
        if ($check) {
            \DB::beginTransaction();
            try {
                \DB::table('barn_materials')
                    ->where('id', $check->id)
                    ->update([
                        'PhysicalInventory' => $check->PhysicalInventory + $barn->number,
                    ]);
                \DB::table('receiptpolim')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        } else {
            try {
                \DB::table('barn_materials')
                    ->insert([
                        'polymeric_id' => $barn->polim_id,
                        'PhysicalInventory' => $barn->number,
                    ]);
                \DB::table('receiptpolim')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        }
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
        if ($row->status == null) {
            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="checkProduct">
                       <i class="fa fa-check fa-lg" title="تایید رسید"></i>
                       </a>&nbsp;&nbsp;';
        }
        return $btn;
    }

    public function actionn($row)
    {
        $btn = null;
        if ($row->status == null) {
            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="checkProduct">
                       <i class="fa fa-check fa-lg" title="تایید رسید"></i>
                       </a>&nbsp;&nbsp;';
        }
        return $btn;
    }

    public function actionnn($row)
    {
        $btn = null;
        if ($row->status == null) {
            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="checkProduct">
                       <i class="fa fa-check fa-lg" title="تایید رسید"></i>
                       </a>&nbsp;&nbsp;';
        }
        return $btn;
    }


}
