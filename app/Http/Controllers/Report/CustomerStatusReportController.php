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
        if ($request->ajax()) {
//            $data = DB::select("CALL get_invoices()");
            if ($request->customer_id == 0) {
                $customer = $array_customer;
            } else {
                $customer = [$request->customer_id];
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
//            $data = DB::select("CALL get_invoices()");
            $data = \DB::table('clearing')
                ->whereIn('status', $status)
                ->whereIn('customer_id', $customer)
                ->whereBetween('date', array($indate, $todate))
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    $price = number_format($row->price);
                    return $price;
                })
                ->rawColumns([])
                ->make(true);
        }
        return view('CustomerStatusReport.list', compact('customers'));
    }
}
