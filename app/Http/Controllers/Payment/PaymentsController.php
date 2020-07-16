<?php

namespace App\Http\Controllers\Payment;

use App\Color;
use App\Customer;
use App\CustomerAccount;
use App\Factors;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Payments;
use App\Product;
use App\User;
use Carbon\Carbon;
use DB;
use Hekmatinasser\Verta\Verta;
use http\Env\Response;
use Illuminate\Http\Request;
use Mockery\Exception;
use Morilog\Jalali\Jalalian;
use Yajra\DataTables\DataTables;
use function GuzzleHttp\Promise\all;

class PaymentsController extends Controller
{

    public function list(Request $request)
    {
        $array_customer = array();
        $v = verta();
        $customers = Customer::all();
        $users = User::all();
        $array_Year = [1399, 1400, 1401, 1402, 1403, 1404, 1405, 1406, 1407, 1408, 1409, 1410, 1411, 1412, 1413, 1414, 1415, 1416, 1417, 1418, 1419, 1420];
        $array_month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($customers as $c) {
            $array_customer[] = $c->id;
        }
        if ($request->ajax()) {
//            $data = DB::select("CALL get_invoices()");
            if ($request->customer_id == null) {
                $customer = $array_customer;
            } else {
                $customer = [$request->customer_id];
            }
            if ($request->year == null) {
                $year = $array_Year;
            } else {
                $year = [$request->year];
            }
            if ($request->month == null) {
                $month = $array_month;
            } else {
                $month = [$request->month];
            }

            $data = DB::table('factors')
                ->where('Month', $v->month)
                ->where('status', 0)
                ->whereIn('customer_id', $customer)
                ->whereIn('Year', $year)
                ->whereIn('Month', $month)
                ->orderBy('customer_id', 'asc')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = ' <input type="checkbox" id="checkbox"
                        name="student_checkbox[]"
                        value=' . $row->pack_id . '
                       class="student_checkbox">';
                    return $btn;
                })
                ->addColumn('total', function ($row) {
                    return number_format($row->sum);
                })
                ->addColumn('number', function ($row) {
                    $number = Factors::where('customer_id', $row->customer_id)
                        ->count();
                    return $number;
                })
                ->addColumn('sumtotal', function ($row) {
                    $number = Factors::where('customer_id', $row->customer_id)
                        ->sum('sum');
                    return number_format($number);
                })
                ->addColumn('user', function ($row) {
                    $name = User::where('id', $row->user_id)->first();
                    return $name->name;
                })
                ->addColumn('customer', function ($row) {
                    $customer = Customer::where('id', $row->customer_id)->first();
                    return $customer->name;
                })
                ->addColumn('action', function ($row) {
                    return $this->actions($row);
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
        return view('payment.list', compact('customers', 'users'));
    }

    public function paymentsuccess(Request $request)
    {
        $array_customer = array();
        $v = verta();
        $customers = Customer::all();
        $array_Year = [1399, 1400, 1401, 1402, 1403, 1404, 1405, 1406, 1407, 1408, 1409, 1410, 1411, 1412, 1413, 1414, 1415, 1416, 1417, 1418, 1419, 1420];
        $array_month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($customers as $c) {
            $array_customer[] = $c->id;
        }
        if ($request->ajax()) {
//            $data = DB::select("CALL get_invoices()");

            if ($request->customer_id == null) {
                $customer = $array_customer;
            } else {
                $customer = [$request->customer_id];
            }
            if ($request->year == null) {
                $year = $array_Year;
            } else {
                $year = [$request->year];
            }
            if ($request->month == null) {
                $month = $array_month;
            } else {
                $month = [$request->month];
            }

            $data = DB::table('factors')
                ->where('Month', $v->month)
                ->where('status', 1)
                ->whereIn('customer_id', $customer)
                ->whereIn('Year', $year)
                ->whereIn('Month', $month)
                ->orderBy('customer_id', 'asc')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('total', function ($row) {
                    return number_format($row->sum);
                })
                ->addColumn('number', function ($row) {
                    $number = Factors::where('customer_id', $row->customer_id)
                        ->count();
                    return $number;
                })
                ->addColumn('sumtotal', function ($row) {
                    $number = Factors::where('customer_id', $row->customer_id)
                        ->sum('sum');
                    return number_format($number);
                })
                ->addColumn('creditor', function ($row) {
                    $number = CustomerAccount::where('customer_id', $row->customer_id)
                        ->first();
                    if (!empty($number)) {
                        return number_format($number->creditor);

                    } else {
                        return number_format(0);

                    }
                })
                ->addColumn('customer', function ($row) {
                    $customer = Customer::where('id', $row->customer_id)->first();
                    return $customer->name;
                })
                ->addColumn('action', function ($row) {
                    return $this->action($row);
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
        return view('paymentsuccess.list', compact('customers'));
    }

    public function listdetail(Request $request)
    {


        if ($request->ajax()) {
            $pack_id = array();
            $clearing_factor = DB::table('clearing_factor')
                ->where('clearing_id', $request->id_id)
                ->get();
            foreach ($clearing_factor as $item) {
                $pack_id[] = $item->pack_id;
            }
            $data = DB::table('schedulings')
                ->whereIn('pack', $pack_id)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    $invoice_product = DB::table('invoice_product')
                        ->where('id', $row->detail_id)
                        ->first();
                    $name = Product::where('id', $invoice_product->product_id)
                        ->first();
                    return $name->label;
                })
                ->addColumn('color', function ($row) {
                    $invoice_product = DB::table('invoice_product')
                        ->where('id', $row->detail_id)
                        ->first();
                    $name = Color::where('id', $invoice_product->color_id)
                        ->first();
                    return $name->name;
                })
                ->addColumn('price', function ($row) {
                    $invoice_product = DB::table('invoice_product')
                        ->where('id', $row->detail_id)
                        ->first();
                    return number_format($invoice_product->sumTotal);
                })
                ->addColumn('price', function ($row) {
                    $invoice_product = DB::table('invoice_product')
                        ->where('id', $row->detail_id)
                        ->first();
                    return number_format($invoice_product->sumTotal);
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('payment.list');

    }

    public function payment(Request $request)
    {
        $check_customer_id = array();
        $check_price = array();
        $count = count($request->id);
        for ($i = 0; $i < $count; $i++) {
            try {
                $customer = DB::table('factors')
                    ->where('pack_id', $request->id[$i])
                    ->first();
                $check_customer_id[] = $customer->customer_id;
                $check_price[] = $customer->sum;
            } catch (Exception $exception) {
            }
        }
        $price = array_sum($check_price);
        $min = min($check_customer_id);
        $max = max($check_customer_id);
        if ($min != $max) {
            return response()->json(['error_customer' => 'error_customer']);
        }
        $customer_account = CustomerAccount::
        where('customer_id', $check_customer_id[0])
            ->first();
        if (empty($customer_account)) {
            $custome = null;
        } else {
            $custome = $customer_account;
        }

        return response()->json(['success' => 'success', 'price' => $price
            , 'creditor' => $custome,
            'customer_id' => $check_customer_id[0], 'pack_id' => $request->id]);

    }

    public function storepament(Request $request)
    {
        $string = preg_split("/,+/", "$request->pack_id");
        $count = count($string);
        $date = Carbon::now();
        DB::beginTransaction();
        try {
            DB::table('clearing')->insert([
                'date' => Jalalian::forge($date)->format('Y/m/d'),
                'takhfif' => $request->takhfif,
                'price' => $request->sum,
            ]);

            $id = DB::table('clearing')
                ->latest('id')->first();
            for ($i = 0; $i < $count; $i++) {
                try {
                    DB::table('clearing_factor')->insert([
                        'clearing_id' => $id->id,
                        'pack_id' => $string[$i],
                    ]);
                    DB::table('factors')
                        ->where('pack_id', $string[$i])
                        ->update([
                            'status' => 1,
                        ]);
                } catch (Exception $exception) {
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
        $user = DB::table('factors')
            ->where('pack_id', $string[0])
            ->first();
        $user_id = User::where('id', $user->user_id)->first();
        $CustomerAccount = DB::table('customer_accounts')->
        where('customer_id', $request->customer_idd)
            ->first();
        if (!empty($CustomerAccount)) {
            $customeraccount = $CustomerAccount->creditor;
        } else {
            $customeraccount = 0;
        }
        $price_customer = DB::table('detail_customer_payment')
            ->where('customer_id', $request->customer_idd)
            ->sum('price');
        $customer_name = Customer::where('id', $request->customer_idd)
            ->first();
        $factors = DB::table('factors')
            ->whereIn('pack_id', $string)
            ->get();
        $sum_price = $request->sum_price;
        $takhfif = $request->takhfif;
        $final = $request->sum_price - $request->takhfif;
        $view = \View::make('payment.print', compact('user_id', 'customer_name', 'customeraccount', 'price_customer', 'factors', 'sum_price', 'takhfif', 'final'));
        return $view->render();
//        if ($request->sum > $request->creditor) {
//            return response()->json(['error_creditor' => 'error_creditor']);
//        }
//        $string = preg_split("/,+/", "$request->pack_id");
//        $created_at = Carbon::now();
//        DB::beginTransaction();
//        try {
//            DB::table('payments_success')
//                ->insert([
//                    'pack_id' => json_encode($request->pack_id),
//                    'user_id' => auth()->user()->id,
//                    'customer_id' => $request->customer_idd,
//                    'ta' => $request->takhfif,
//                    'created_at' => $created_at,
//                ]);
//
//            $count = count($string);
//            for ($i = 0; $i < $count; $i++) {
//                try {
//                    DB::table('factors')
//                        ->where('pack_id', $string[$i])
//                        ->update([
//                            'status' => 1,
//                        ]);
//                } catch (Exception $exception) {
//                }
//            }
//
//            CustomerAccount::where('customer_id', $request->customer_idd)
//                ->update([
//                    'creditor' => $request->creditor - $request->sum,
//                ]);
//            DB::commit();
//        } catch (Exception $exception) {
//            DB::rollBack();
//        }
//        return response()->json(['success' => 'success']);

    }

    public function store(Request $request)
    {
        $sql = DB::insert('CALL InsertPayment (?)', array('nima'));
        dd($sql);
    }

    public function factor(Request $request)
    {
        $id = DB::table('factors')
            ->where('id', $request->id_factor)
            ->first();
        $pack_id = "$id->pack_id";
        $CustomerAccount = CustomerAccount::where('customer_id', $id->customer_id)->first();
        DB::beginTransaction();
        try {
            CustomerAccount::where('customer_id', $id->customer_id)
                ->update([
                    'creditor' => $CustomerAccount->creditor + $id->sum,
                ]);
            DB::table('factors')
                ->where('id', $request->id_factor)
                ->update([
                    'status' => 0,
                    'canceled' => 1,
                    'description' => $request->description,
                ]);

            DB::table('payments_success')
                ->where('pack_id', $pack_id)
                ->update([
                    'pack_id' => 0,
                ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }


        return response()->json(['success' => 'success']);

    }

    public function update($id)
    {


        $detais = DB::table('clearing_factor')
            ->where('clearing_id', $id)
            ->first();

        $detail_id = DB::table('factors')
            ->where('pack_id', $detais->pack_id)
            ->first();

        $clearing = DB::table('clearing')
            ->where('id', $id)
            ->first();

        $p = number_format($clearing->price);


        $name = Customer::where('id', $detail_id->customer_id)->first();
        $date = DB::table('detail_customer_payment')
            ->where('customer_id', $detail_id->customer_id)
            ->latest()->first();
        if (!empty($date)) {
            $dat = Jalalian::forge($date->created_at)->format('در تاریخ ' . 'Y/m/d' . '  ساعت ' . 'H:i:s');
        } else {
            $dat = null;
        }
        $price = CustomerAccount::where('customer_id', $detail_id->customer_id)->first();
        if (!empty($price)) {
            $pri = number_format($price->creditor) . ' ریال ';
        } else {
            $pri = number_format(0);
        }
        return response()->json(['name' => $name, 'price' => $pri, 'date' => $dat, 'sum' => $p]);

    }

    public function actions($row)
    {

        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->customer_id . '" data-original-title="مشاهده"
                       class="detail-eye">
                       <i class="fa fa-plus fa-lg" title="افزایش موجودی مشتری"></i>
                       </a>&nbsp;&nbsp;';


        return $btn;

    }

    public function action($row)
    {

        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="حذف فاکتور"
                       class="detail-trash">
                       <i class="fa fa-trash fa-lg" title="حذف فاکتور"></i>
                       </a>&nbsp;&nbsp;';


        return $btn;

    }

}
