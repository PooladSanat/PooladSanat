<?php

namespace App\Http\Controllers\Report;

use App\Color;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Product;
use App\Scheduling;
use App\User;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Repository;
use Morilog\Jalali\Jalalian;
use Yajra\DataTables\DataTables;

class ReportMonthlyController extends Controller
{
    public function list(Request $request)
    {


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
            $data = $this->displayDates($request->indate, $request->todate);


//            $data = \DB::table('view_unionreportmontly')
//                ->whereBetween('date', array($indate, $todate))
//                ->distinct()
//                ->orderBy('date', 'ASC')
//                ->get(['date']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    $total = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('total');
                    $number_return = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('number_return');
                    $number = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('number');
                    if ($total > 0 or $number_return > 0 or $number > 0) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                     data-id="' . $row . '" data-original-title="جزییات"
                     class="detail-tolid">
                  ' . $row . '
                   </a>';
                        return $btn;
                    } else {
                        return $row;
                    }
                })
                ->addColumn('total', function ($row) {

                    $total = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('total');
                    return $total;

                })
                ->addColumn('sum', function ($row) {

                    $sum = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('sum');
                    return number_format($sum);

                })
                ->addColumn('sa', function ($row) {

                    $number_return = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('number_return');
                    return $number_return;

                })
                ->addColumn('as', function ($row) {
                    $number = DB::table('View_UnionReportmontly')
                        ->where('date', $row)
                        ->sum('number');
//                    if ($number > 0) {
//                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
//                      data-id="' . $row . '" data-original-title="جزییات"
//                       class="detail-tolid">
//                      ' . $number . '
//                       </a>';
//                        return $btn;
//                    } else {
//
//                    }
                    return $number;
                })
                ->rawColumns(['date'])
                ->make(true);

        }
        return view('ReportMonthly.list');
    }

    public function ExitPrint(Request $request)
    {

        $users = User::all();
        $customers = Customer::all();
        $customer = [$request->id_custome];
        $user = [$request->id_user];
        $array_user = array();
        $array_customer = array();
        if ($request->type == 0) {
            $type = [0, 1];
        } elseif ($request->type == 1) {
            $type = [1];
        } else {
            $type = [0];
        }
        foreach ($users as $u) {
            $array_user[] = $u->id;
        }
        foreach ($customers as $c) {
            $array_customer[] = $c->id;
        }

        if (!empty($request->id_user)) {
            $user_id = [$request->id_user];
        } else {
            $user_id = $array_user;
        }
        if (!empty($request->id_custome)) {
            $customer_id = [$request->id_custome];
        } else {
            $customer_id = $array_customer;
        }


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
        if (!empty($request->id_custome)) {
            $namer = Customer::where('id', $request->id_custome)->first();
            $name = $namer->name;
        } else {
            $name = null;
        }
        if (!empty($request->id_user)) {
            $nameer = User::where('id', $request->id_user)->first();
            $namee = $nameer->name;
        } else {
            $namee = null;
        }

        if ($request->type == 0) {
            $types = 'همه';
        } elseif ($request->type == 1) {
            $types = 'پرداخت شده';
        } else {
            $types = 'پرداخت نشده';
        }


        $data = \DB::table('factors')
            ->whereIn('payment', $type)
            ->whereIn('user_id', $user_id)
            ->whereIn('customer_id', $customer_id)
            ->whereBetween('date', array($indate, $todate))
            ->get();


        $view = \View::make('ExitReport.print',
            compact('name'
                , 'in', 'to', 'namee', 'data', 'users', 'customers', 'types'));
        return $view->render();

    }

    public function CustomerTransactions(Request $request)
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

            $data = \DB::table('View_Transaction')
                ->where('date', '!=', "1399")
                ->whereIn('customer_id', $customer)
                ->whereBetween('date', array($indate, $todate))
                ->orderBy('date', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('sum', function ($row) {
                    return number_format($row->sum);
                })
                ->addColumn('description', function ($row) {
                    if (!empty($row->price)) {
                        return $row->descriptionn;
                    } else {
                        return 'بدهی بابت فاکتور' . ' ' . $row->rahkaran;
                    }
                })
                ->rawColumns([])
                ->make(true);

        }
        return view('CustomerTransactions.list', compact('customers'));
    }

    public function ExitList(Request $request)
    {
        $role_id = DB::table('role_user')
            ->where('user_id', auth()->user()->id)
            ->first();
        $role_name = DB::table('roles')
            ->where('id', $role_id->role_id)
            ->first();
        if ($role_name->name == "Admin" or $role_name->name == "مدیریت") {
            $customers = Customer::all();
            $users = User::all();
        } else {
            $customers = Customer::where('expert', auth()->user()->id)->get();
            $users = User::where('id', auth()->user()->id)->get();
        }

        $array_customer = array();
        $array_user = array();
        if ($request->type == 0) {
            $type = [0, 1];
        } elseif ($request->type == 1) {
            $type = [1];
        } else {
            $type = [0];
        }
        foreach ($users as $u) {
            $array_user[] = $u->id;
        }
        foreach ($customers as $c) {
            $array_customer[] = $c->id;
        }
        if (!empty($request->user_id)) {
            $user_id = [$request->user_id];
        } else {
            $user_id = $array_user;
        }
        if (!empty($request->customer_id)) {
            $customer = [$request->customer_id];
        } else {
            $customer = $array_customer;
        }

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

            $data = \DB::table('factors')
                ->whereIn('payment', $type)
                ->whereIn('user_id', $user_id)
                ->whereIn('customer_id', $customer)
                ->whereBetween('date', array($indate, $todate))
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pack_id', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->pack_id . '" data-original-title="جزییات"
                       class="detail-factor">
                      ' . $row->pack_id . '
                       </a>';
                    return $btn;
                })
                ->addColumn('customer_id', function ($row) {
                    $name = Customer::where('id', $row->customer_id)->first();
                    return $name->name;

                })
                ->addColumn('user_id', function ($row) {
                    $name = User::where('id', $row->user_id)->first();
                    return $name->name;

                })
                ->addColumn('payment', function ($row) {
                    if ($row->payment == 1) {
                        return 'پرداخت شده';
                    } else {
                        return 'پرداخت نشده';
                    }
                })
                ->addColumn('total', function ($row) {
                    $total = Scheduling::where('pack', $row->pack_id)->sum('total');
                    return $total;
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->sum);
                })
//                ->addColumn('payment', function ($row) {
//
//                    $pack = DB::table('clearing_factor')
//                        ->where('pack_id', $row->pack_id)->first();
//                    if (!empty($pack)) {
//                        $payment = DB::table('payments')
//                            ->where('clearing_id', $pack->clearing_id)->first();
//
//                        if (!empty($payment)) {
//                            return 'پرداخت شده';
//                        } else {
//                            return 'پرداخت نشده';
//                        }
//                    } else {
//                        return 'پرداخت نشده';
//                    }
//
//
//                })
                ->rawColumns(['pack_id'])
                ->make(true);

        }
        return view('ExitReport.list', compact('customers', 'users'));
    }

    public function Tolid(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('receiptproduct')
                ->where('date', $request->detail_factor)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    $detail = Product::
                    where('id', $row->product_id)
                        ->first();
                    return $detail->label;
                })
                ->addColumn('color', function ($row) {
                    $color = Color::where('id', $row->color_id)
                        ->first();
                    return $color->name;
                })
                ->addColumn('time', function ($row) {
                    $created = Carbon::make($row->created_at)->format('H:i:s');
                    return $created;
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('ReportMonthly.list');

    }

    public function Frosh(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('factors')
                ->where('date', $request->detail_factor)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('number', function ($row) {
                    $detail = DB::table('schedulings')->
                    where('pack', $row->pack_id)
                        ->sum('total');
                    return $detail;
                })
                ->addColumn('price', function ($row) {

                    return number_format($row->sum);
                })
                ->addColumn('user_id', function ($row) {
                    $detail = User::
                    where('id', $row->user_id)
                        ->first();
                    return $detail->name;
                })
                ->addColumn('customer_id', function ($row) {
                    $detail = Customer::
                    where('id', $row->customer_id)
                        ->first();
                    return $detail->name;
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('ReportMonthly.list');

    }


    public function Mar(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('returns')
                ->where('date', $request->detail_factor)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    $name = Customer::where('id', $row->customer_id)->first();
                    return $name->name;
                })
                ->addColumn('user', function ($row) {
                    $id = DB::table('detail_returns')->where('return_id', $row->id)
                        ->first();
                    $name = Invoice::where('id', $id->invoice_id)->first();
                    $user = User::where('id', $name->user_id)->first();
                    return $user->name;
                })
                ->addColumn('factor', function ($row) {
                    $id = DB::table('detail_returns')->where('return_id', $row->id)
                        ->first();
                    return $id->invoice_id;
                })
                ->addColumn('number', function ($row) {
                    $id = DB::table('detail_returns')->where('return_id', $row->id)
                        ->sum('number');
                    return $id;
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('ReportMonthly.list');

    }


    public function show($id)
    {
        return response()->json($id);

    }


    function displayDates($date1, $date2)
    {
        $format = 'Y/m/d';
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }
}
