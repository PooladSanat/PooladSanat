<?php

namespace App\Http\Controllers\Sell;

use App\BarnReturns;
use App\BarnsProduct;
use App\BarnTemporary;
use App\Color;
use App\Complaints;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelProduct;
use App\Product;
use App\Returns;
use App\Setting;
use App\User;
use Carbon\Carbon;
use DB;
use Gate;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Mockery\Exception;
use Morilog\Jalali\Jalalian;
use Response;
use Validator;
use Yajra\DataTables\DataTables;

class ReturnsController extends Controller
{
    public function list(Request $request)
    {

        $products = Product::all();
        $customers = Customer::all();
        $colors = Color::all();
        if ($request->ajax()) {
            $data = DB::table('returns')
                ->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('code', function ($row) {
                    $btn = '<a href="' . route('admin.Returns.list.detail.print', $row->id) . '">
                     ' . $row->id . '
                      </a>';
                    return $btn;
                })
                ->addColumn('date', function ($row) {
                    $date = Jalalian::forge($row->created_at)->format('Y/m/d');
                    return $date;
                })
                ->addColumn('costumer_id', function ($row) {
                    $costumer_id = Customer::where('id', $row->customer_id)->first();
                    return $costumer_id->name;
                })
                ->addColumn('detail_returns', function ($row) {
                    $detail_returns = DB::table('detail_returns')
                        ->where('return_id', $row->id)
                        ->sum('number');
                    return $detail_returns;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return 'در انتظار بررسی مدیر فروش';
                    } elseif ($row->status == 2) {
                        return 'در انتظار بررسی QC';
                    } elseif ($row->status == 3) {
                        return 'در انتظار بررسی انبار';
                    } else {
                        return 'اتمام یافته';
                    }
                })
                ->addColumn('action', function ($row) {
                    return $this->actions($row);
                })
                ->rawColumns(['action', 'code'])
                ->make(true);
        }
        return view('returns.list', compact('customers', 'colors', 'products'));

    }

    public function store(Request $request)
    {
        $barnproduct = BarnsProduct::where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)->first();
        $barnreturn = BarnReturns::where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)->first();
        try {
            Returns::create([
                'product_id' => $request->product_id,
                'color_id' => $request->color_id,
                'invoice_number' => $request->invoice_number,
                'healthynumber' => $request->healthynumber,
                'wastagenumber' => $request->wastagenumber,
                'date' => $request->date,
                'description' => $request->description,
            ]);
            if (!empty($barnproduct)) {
                BarnsProduct::where('product_id', $request->product_id)
                    ->where('color_id', $request->color_id)
                    ->update([
                        'Inventory' => $barnproduct->Inventory + $request->healthynumber,
                    ]);
            } else {
                BarnsProduct::create([
                    'product_id' => $request->product_id,
                    'color_id' => $request->color_id,
                    'Inventory' => $request->healthynumber,
                ]);
            }
            if (!empty($barnreturn)) {
                BarnReturns::where('product_id', $request->product_id)
                    ->where('color_id', $request->color_id)
                    ->update([
                        'Inventory' => $barnreturn->Inventory + $request->wastagenumber,
                    ]);
            } else {
                BarnReturns::create([
                    'product_id' => $request->product_id,
                    'color_id' => $request->color_id,
                    'Inventory' => $request->wastagenumber,
                ]);
            }
            \DB::commit();
        } catch (Exception $exception) {
            \DB::rollBack();
        }

        return response()->json(['success' => 'success']);

    }

    public function invoice(Returns $returns)
    {
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        $colors = Color::all();
        $setting = Setting::first();
        $modelProducts = ModelProduct::all();
        return view('returns.print', compact('users', 'customers',
            'returns',
            'products', 'colors', 'modelProducts', 'setting'));

    }

    public function number(Request $request)
    {

        $state = DB::table('invoices')
            ->where('customer_id', $request->commodity_id)->get();
        return response()->json($state);


    }

    public function barn(Request $request)
    {
        $id = array();
        $details = DB::table('detail_returns')
            ->where('return_id', $request->id_id)
            ->get();
        foreach ($details as $detail) {
            $id[] = $detail->id;
        }

        DB::table('return_qc')
            ->insert([
                'user_id' => auth()->user()->id,
                'return_id' => $request->id_id,
                'status' => $request->statusq,
                'description' => $request->descriptionq,
            ]);
        Returns::where('id', $request->id_id)
            ->update([
                'status' => 3,
            ]);
        try {
            $m = count(collect($request)->get('m'));
            for ($i = 0; $i < $m; $i++) {
                DB::table('detail_returns')->where('id', $id[$i])
                    ->update([
                        'Healthy' => $request->get('s')[$i],
                        'wastage' => $request->get('m')[$i],
                    ]);
            }
        } catch (Exception $exception) {
            return response()->json(['success' => 'success']);
        }
        return response()->json(['success' => 'success']);
    }

    public function product(Request $request)
    {
        $data = DB::table('invoice_product')
            ->where('invoice_id', $request->product)
            ->distinct()
            ->get(['product_id']);
        return response()->json($data);

    }

    public function color(Request $request)
    {
        $data = DB::table('invoice_product')
            ->where('invoice_id', $request->color)
            ->where('product_id', $request->p)
            ->distinct()
            ->get(['color_id']);
        return response()->json($data);

    }

    public function manager(Request $request)
    {
        $date = Carbon::now();
        DB::beginTransaction();
        try {
            Returns::where('id', $request->id_)
                ->update([
                    'status' => 2,
                ]);
            DB::table('return_manager')
                ->insert([
                    'user_id' => auth()->user()->id,
                    'return_id' => $request->id_,
                    'status' => $request->statusu,
                    'description' => $request->descriptionu,
                    'created_at' => $date,
                ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
        return response()->json(['success' => 'success']);

    }

    public function print($id)
    {
        $manager = DB::table('return_manager')
            ->where('return_id', $id)
            ->first();
        $qc = DB::table('return_qc')
            ->where('return_id', $id)
            ->first();

        $barn = DB::table('return_barn')
            ->where('return_id', $id)
            ->first();

        $customers = Customer::all();
        $return = Returns::where('id', $id)->first();
        $invoices_id = DB::table('detail_returns')
            ->where('return_id', $id)
            ->first();
        $Healthy = DB::table('detail_returns')
            ->where('return_id', $id)
            ->sum('Healthy');

        $wastage = DB::table('detail_returns')
            ->where('return_id', $id)
            ->sum('wastage');


        $sum = DB::table('detail_returns')
            ->where('return_id', $id)
            ->sum('number');


        $invoices_ids = DB::table('detail_returns')
            ->where('return_id', $id)
            ->get();
        $invoices = Invoice::all();
        $users = User::all();
        $complaints = Complaints::where('id', $invoices_id->complaints_id)->first();
        return view('returns.detail', compact('users', 'id', 'invoices_id',
            'return', 'customers', 'invoices', 'complaints', 'invoices_ids',
            'sum', 'manager', 'Healthy', 'wastage', 'qc', 'barn'));
    }

    public function success(Request $request)
    {

        $date = Carbon::now();
        Returns::where('id', $request->id_d)
            ->update([
                'status' => 4,
            ]);
        DB::table('return_barn')
            ->insert([
                'user_id' => auth()->user()->id,
                'return_id' => $request->id_d,
                'status' => $request->statusd,
                'description' => $request->descriptiond,
                'created_at' => $date,
            ]);

        try {
            $m = count(collect($request)->get('m'));
            for ($i = 0; $i < $m; $i++) {
                $barnproduct = DB::table('barns_products')
                    ->where('product_id', $request->get('product')[$i])
                    ->where('color_id', $request->get('coloor')[$i])
                    ->first();

                $barnreturn = DB::table('barn_returns')
                    ->where('product_id', $request->get('product')[$i])
                    ->where('color_id', $request->get('coloor')[$i])
                    ->first();


                if (!empty($barnproduct)) {
                    DB::table('barns_products')
                        ->where('product_id', $request->get('product')[$i])
                        ->where('color_id', $request->get('coloor')[$i])
                        ->update([
                            'Inventory' => abs($barnproduct->Inventory + $request->get('s')[$i]),
                        ]);
                } else {
                    DB::table('barns_products')
                        ->where('product_id', $request->get('product')[$i])
                        ->where('color_id', $request->get('coloor')[$i])
                        ->insert([
                            'product_id' => $request->get('product')[$i],
                            'color_id' => $request->get('coloor')[$i],
                            'Inventory' => abs($request->get('s')[$i]),
                        ]);
                }
                if (!empty($barnreturn)) {
                    DB::table('barn_returns')
                        ->where('product_id', $request->get('product')[$i])
                        ->where('color_id', $request->get('coloor')[$i])
                        ->update([
                            'Inventory' => abs($barnreturn->Inventory + $request->get('m')[$i]),
                        ]);
                } else {
                    DB::table('barn_returns')
                        ->where('product_id', $request->get('product')[$i])
                        ->where('color_id', $request->get('coloor')[$i])
                        ->insert([
                            'product_id' => $request->get('product')[$i],
                            'color_id' => $request->get('coloor')[$i],
                            'Inventory' => abs($request->get('m')[$i]),
                        ]);
                }


            }
        } catch (Exception $exception) {
            return response()->json(['success' => 'success']);
        }


        return response()->json(['success' => 'success']);

    }

    public function storee(Request $request)
    {
        DB::beginTransaction();
        try {
            $return = Returns::create([
                'customer_id' => $request->customer_id,
                'Cost' => $request->Carry,
                'date' => $request->date,
                'Description_m' => $request->description_m,
                'Description_v' => $request->description_f,
                'description' => $request->description,
                'status' => 1,
            ]);
            try {
                $invoice = count(collect($request)->get('product'));
                for ($i = 0; $i < $invoice; $i++) {
                    \DB::table('detail_returns')->insert([
                        'return_id' => $return->id,
                        'invoice_id' => $request->get('invoice')[$i],
                        'product_id' => $request->get('product')[$i],
                        'color_id' => $request->get('color')[$i],
                        'number' => $request->get('number')[$i],
                        'reason' => $request->get('reasons')[$i],
                    ]);
                }
            } catch (Exception $exception) {
                return response()->json(['success' => 'success']);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
        return response()->json(['success' => 'success']);
    }

    public function sttoree(Request $request)
    {

        DB::beginTransaction();
        try {
            $return = Returns::create([
                'customer_id' => $request->customer_id,
                'Cost' => $request->Carry,
                'date' => $request->dattee,
                'Description_m' => $request->description_m,
                'Description_v' => $request->description_f,
                'description' => $request->description,
                'status' => 1,
            ]);
            DB::table('detail_returns')
                ->where('complaints_id', $request->id_id)
                ->update([
                    'return_id' => $return->id,
                ]);
            Complaints::where('id', $request->id_id)
                ->update([
                    'status' => 3,
                ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
        return response()->json(['success' => 'success']);
    }

    public function totalnumber(Request $request)
    {
        $data = DB::table('invoice_product')
            ->where('invoice_id', $request->color)
            ->where('product_id', $request->p)
            ->where('color_id', $request->c)
            ->first();
        return response()->json($data);

    }

    public function storeinvoice(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'Weight.*' => 'required|integer',
            'number.*' => 'required',
            'sell.*' => 'required',
            'color.*' => 'required',
            'product.*' => 'required',
        ], [
            'number.*.required' => 'لطفا تعداد فروش محصول را مشخص کنید',
            'product.*.required' => 'لطفا محصول خود را انتخاب کنید',
            'color.*.required' => 'لطفا رنگ محصول را انتخاب کنید',
            'sell.*.required' => 'لطفا قیمت فروش محصول را مشخص کنید',
            'Weight.*.required' => 'لطفا وزن محصول را وارد کنید برای اینکار به تعاریف پایه مراجعه کنید',
            'Weight.*.integer' => 'لطفا وزن محصول را وارد کنید برای اینکار به تعاریف پایه مراجعه کنید',
        ]);
        $tx = (int)$request->price_full - (int)$request->price_selll;
        if ($tx < 0) {
            $tax = 0;
        } else {
            $tax = $tx;
        }
        $date = Jalalian::forge(date('Y/m/d'))->format('Y/m/d');
        $to = Carbon::parse($date);
        $now_date = new Verta();
        $year = substr($now_date->year, strpos($now_date->year, '://') + 2, 6);
        $rand = Invoice::withTrashed()->latest('id')->first();
        if ($rand == null) {
            $numberCount = 100000;
        } else {
            $numberCount = $rand->number + 1;
        }
        $invoiceNumber = $year . $numberCount;
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $invoice = Invoice::create([
                    'invoiceNumber' => $invoiceNumber,
                    'number' => $numberCount,
                    'user_id' => $request->user_id,
                    'customer_id' => $request->customer_id,
                    'invoiceType' => $request->InvoiceType,
                    'paymentMethod' => $request->paymentMethod,
                    'sum_sell' => $request->sum_selll,
                    'number_sell' => $request->number_selll,
                    'price_sell' => $request->price_selll,
                    'created' => date("Y/m/d"),
                    'date' => $date,
                    'tax' => $tax,
                    'Month' => $to->month,
                    'Year' => $to->year,
                    'takhfif' => $request->takhfif,
                    'expenses' => $request->expenses,
                    'Carry' => $request->Carry,
                    'ta' => $request->taa,
                    'totalfinal' => $request->price_f,
                    'ma' => $request->ma,
                    'create' => $this->convert2english($request->created),
                    'returns' => $request->return,
                    'description' => $request->description,
                ]);
                try {
                    $number = count(collect($request)->get('product'));
                    for ($i = 0; $i <= $number; $i++) {
                        \DB::table('invoice_product')->insert([
                            'invoice_id' => $invoice->id,
                            'user_id' => $request->user_id,
                            'product_id' => $request->get('product')[$i],
                            'color_id' => $request->get('color')[$i],
                            'salesNumber' => $request->get('number')[$i],
                            'leftover' => $request->get('number')[$i],
                            'salesPrice' => $request->get('sell')[$i],
                            'sumTotal' => $request->get('Price_Sell')[$i],
                            'weight' => $request->get('Weight')[$i],
                            'taxAmount' => $request->get('Tax')[$i],
                        ]);
                    }
                } catch (\Exception $e) {
                }
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
            }
            return response()->json(['success' => 'مشخصات با موفقیت در سیستم ثبت شد']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function actions($row)
    {
        $btn = null;
        if ($row->status == 4) {
            $btn = $btn . '<a href="' . route('admin.invoice.store.return', $row->id) . '">
                       <i class="fa fa-mail-reply-all fa-lg" title="صدور پیش فاکتور"></i>
                       </a>&nbsp;&nbsp;';
        }
        if ($row->status == 1) {
            if (Gate::check('نظر مدیر فروش')) {
                $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="مدیر فروش"
                       class="usert">
                       <i class="fa fa-user fa-lg" title="مدیر فروش"></i>
                       </a>&nbsp;&nbsp;';
            }
        }
        if ($row->status == 2) {
            if (Gate::check('نظر QC')) {
                $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="درخواست مرجوعی"
                       class="qc">
                       <i class="fa fa-file-text fa-lg" title="نظر QC"></i>
                       </a>&nbsp;&nbsp;';
            }
        }
        if ($row->status == 3) {
            if (Gate::check('نظر انبار')) {
                $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="نظر انبار"
                       class="database">
                       <i class="fa fa-database fa-lg" title="نظر انبار"></i>
                       </a>&nbsp;&nbsp;';
            }
        }

        return $btn;

    }

    public function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }
}
