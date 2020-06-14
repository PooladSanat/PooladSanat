<?php

namespace App\Http\Controllers\Sell;

use App\BarnReturns;
use App\BarnsProduct;
use App\Color;
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
        $colors = Color::all();
        if ($request->ajax()) {
            $data = Returns::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_id', function ($row) {
                    $product = Product::where('id', $row->product_id)->first();
                    return $product->label;
                })
                ->addColumn('color_id', function ($row) {
                    $color = Color::where('id', $row->color_id)->first();
                    return $color->name;
                })
                ->addColumn('action', function ($row) {
                    return $this->actions($row);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('returns.list', compact('products', 'colors'));

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
        $id = Invoice::where('invoiceNumber', $returns->invoice_number)->first();
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        $colors = Color::all();
        $setting = Setting::first();
        $modelProducts = ModelProduct::all();

        return view('returns.print', compact('users', 'customers',
            'products', 'colors', 'modelProducts', 'setting', 'id', 'returns'));

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
                    'returns' => $request->returns,
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
        $btn = '<a href="' . route('admin.invoice.store.return', $row->id) . '">
                       <i class="fa fa-mail-reply-all fa-lg" title="پیش فاکتور"></i>
                       </a>&nbsp;&nbsp;';

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
