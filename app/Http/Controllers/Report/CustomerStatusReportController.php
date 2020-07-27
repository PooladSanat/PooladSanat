<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerStatusReportController extends Controller
{
    public function list(Request $request)
    {
        $customers = Customer::all();
        $status = collect([1]);
        foreach ($customers as $c) {
            $array_customer[] = $c->id;
        }
        $customer = [$request->customer_id];
        if (!empty($request->indate)) {
            $indate = $request->indate;
        } else {
            $indate = "1370/04/10";
        }
        if (!empty($request->todate)) {
            $todate = $request->todate;
        } else {
            $todate = "1470/04/10";
        }


        if ($request->ajax()) {

//            $data = DB::select("CALL get_invoices()");

//            $data = DB::select("CALL get_invoices()");

            $data = \DB::table('clearing')
                ->whereIn('status', $status)
                ->whereIn('customer_id', $customer)
                ->whereBetween('date', array($indate, $todate))
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    $price = number_format($row->price);
                    return $price;
                })
                ->addColumn('reciveprice', function ($row) {
                    $detail = \DB::table('detail_customer_payment')
                        ->where('payment_id', $row->id)
                        ->sum('price');
                    return number_format($detail);
                })
                ->addColumn('total', function ($row) {
                    $detail = \DB::table('detail_customer_payment')
                        ->where('payment_id', $row->id)
                        ->sum('price');
                    return number_format($detail - $row->price);
                })
                ->addColumn('t', function ($row) {
                    $detail = \DB::table('detail_customer_payment')
                        ->where('payment_id', $row->id)
                        ->sum('price');
                    return $detail - $row->price;
                })
                ->addColumn('customer', function ($row) {
                    $sum = \DB::table('factors')
                        ->where('status', 0)
                        ->where('customer_id', $row->customer_id)
                        ->sum('sum');
                    return $sum;
                })
                ->addColumn('customerr', function ($row) {
                    $sum = \DB::table('factors')
                        ->where('status', 0)
                        ->where('customer_id', $row->customer_id)
                        ->sum('sum');
                    return number_format($sum);
                })
                ->addColumn('sum_customer', function ($row) {
                    $sum = \DB::table('customer_accounts')
                        ->where('customer_id', $row->customer_id)
                        ->first();
                    return $sum->creditor;
                })
                ->addColumn('sum_customerr', function ($row) {
                    $sum = \DB::table('customer_accounts')
                        ->where('customer_id', $row->customer_id)
                        ->first();
                    return number_format($sum->creditor);
                })
                ->rawColumns([])
                ->make(true);

        }
        return view('CustomerStatusReport.list', compact('customers'));
    }


    public function detail(Request $request)
    {

        $customer = [$request->id_customer];
        if (!empty($request->intime)) {
            $indate = $request->intime;
        } else {
            $indate = "1370/04/10";
        }
        if (!empty($request->totime)) {
            $todate = $request->totime;
        } else {
            $todate = "1470/04/10";
        }

        if ($request->ajax()) {
//            $data = DB::select("CALL get_invoices()");

//            $data = DB::select("CALL get_invoices()");


            $data = \DB::table('detail_customer_payment')
                ->whereIn('customer_id', $customer)
                ->whereBetween('datee', array($indate, $todate))
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    if ($row->type == 1) {
                        return "نقدی";
                    } else {
                        return "چکی";
                    }
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('status', function ($row) {
                    if ($row->type == 2) {
                        if ($row->status == 1) {
                            return "بدون وضعیت";
                        } elseif ($row->status == 2) {
                            return "پاس شده";
                        } else {
                            return "برگشت خورده";
                        }
                    } else {
                        return "پرداخت شده";
                    }

                })
                ->rawColumns([])
                ->make(true);
        }
        return response()->json($data);
    }


    public function print(Request $request)
    {

        $status = collect([1]);
        $customer = [$request->id_customer];
        if (!empty($request->intime)) {
            $indate = $request->intime;
        } else {
            $indate = "1370/04/10";
        }
        if (!empty($request->totime)) {
            $todate = $request->totime;
        } else {
            $todate = "1470/04/10";
        }
        $in = $request->intime;
        $to = $request->totime;

        $name = Customer::where('id', $customer)->first();

        $clearings = \DB::table('clearing')
            ->whereIn('status', $status)
            ->whereIn('customer_id', $customer)
            ->whereBetween('date', array($indate, $todate))
            ->get();
        $details = \DB::table('detail_customer_payment')->get();

        $sumtotal = \DB::table('factors')
            ->where('status', '!=', 1)
            ->where('customer_id', $customer)
            ->sum('sum');
        $sumcustomer = \DB::table('customer_accounts')
            ->where('customer_id', $customer)
            ->first();

        $detail_customer_payments = \DB::table('detail_customer_payment')
            ->whereIn('customer_id', $customer)
            ->whereBetween('datee', array($indate, $todate))
            ->get();

        $sum = \DB::table('factors')
            ->where('status', 0)
            ->where('customer_id', $customer)
            ->sum('sum');

        $view = \View::make('CustomerStatusReport.print',
            compact('clearings', 'details', 'sumtotal',
                'detail_customer_payments', 'sumcustomer', 'sum', 'name'
                , 'in', 'to'));
        return $view->render();

//        $string = preg_split("/,+/", "$request->pack_id");
//        $count = count($string);
//        $date = Carbon::now();
//        DB::beginTransaction();
//        try {
//            DB::table('clearing')->insert([
//                'date' => Jalalian::forge($date)->format('Y/m/d'),
//                'takhfif' => $request->takhfif,
//                'price' => $request->sum,
//                'status' => 0,
//                'customer_id' => $request->name_cusomer,
//            ]);
//
//            $id = DB::table('clearing')
//                ->latest('id')->first();
//            for ($i = 0; $i < $count; $i++) {
//                try {
//                    DB::table('clearing_factor')->insert([
//                        'clearing_id' => $id->id,
//                        'pack_id' => $string[$i],
//                    ]);
//                    DB::table('factors')
//                        ->where('pack_id', $string[$i])
//                        ->update([
//                            'status' => 3,
//                        ]);
//                } catch (Exception $exception) {
//                }
//            }
//            DB::commit();
//        } catch (Exception $exception) {
//            DB::rollBack();
//        }
//        $user = DB::table('factors')
//            ->where('pack_id', $string[0])
//            ->first();
//        $user_id = User::where('id', $user->user_id)->first();
//        $CustomerAccount = DB::table('customer_accounts')->
//        where('customer_id', $request->customer_idd)
//            ->first();
//        if (!empty($CustomerAccount)) {
//            $customeraccount = $CustomerAccount->creditor;
//        } else {
//            $customeraccount = 0;
//        }
//        $price_customer = DB::table('detail_customer_payment')
//            ->where('customer_id', $request->customer_idd)
//            ->sum('price');
//        $customer_name = Customer::where('id', $request->customer_idd)
//            ->first();
//        $factors = DB::table('factors')
//            ->whereIn('pack_id', $string)
//            ->get();
//        $sum_price = $request->sum_price;
//        $takhfif = $request->takhfif;
//        $final = $request->sum_price - $request->takhfif;

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


}
