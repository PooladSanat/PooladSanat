<?php

namespace App\Http\Controllers\Payment;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BillsController extends Controller
{
    public function list(Request $request)
    {


        if ($request->ajax()) {
//            $data = DB::select("CALL get_invoices()");

            $data = \DB::table('clearing')
                ->where('status', null)
                ->orWhere('status', 2)
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    $price = number_format($row->price);
                    return $price;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 2) {
                        return 'در انتظار تایید مدیریت';
                    } elseif ($row->status == null) {
                        return 'در انتظار بررسی';
                    }
                })
                ->addColumn('customer', function ($row) {
                    $pack_id = \DB::table('clearing_factor')
                        ->where('clearing_id', $row->id)
                        ->first();

                    $id = \DB::table('factors')
                        ->where('pack_id', $pack_id->pack_id)
                        ->first();

                    $name = Customer::where('id', $id->customer_id)
                        ->first();

                    return $name->name;

                })
                ->addColumn('action', function ($row) {
                    return $this->action($row);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('bills.list');
    }

    public function action($row)
    {

        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="تسویه حساب"
                       class="detail-eye">
                       <i class="fa fa-plus fa-lg" title="تسویه حساب"></i>
                       </a>&nbsp;&nbsp;';

        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="تایید مدیریت"
                       class="detail-admin">
                       <i class="fa fa-user fa-lg" title="تایید مدیریت"></i>
                       </a>&nbsp;&nbsp;';


        return $btn;

    }
}
