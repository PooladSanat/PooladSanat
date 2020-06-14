<?php

namespace App\Http\Controllers\Sell;

use App\Bank;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\SelectStore;
use App\User;
use DB;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Yajra\DataTables\DataTables;

class SalesArchiveController extends Controller
{
    public function list(Request $request)
    {
        $id = auth()->user()->id;
        $role_id = \DB::table('role_user')
            ->where('user_id', $id)->first();
        $role = DB::table('roles')
            ->where('id', $role_id->role_id)
            ->first();
        $banks = Bank::where('status', 1)->get();
        $selectstores = SelectStore::where('status', 1)->get();
        if ($role->name == "مدیریت" or $role->name == "Admin" or $role->name == "کارشناس فروش و مالی") {
            if ($request->ajax()) {
                $data = Invoice::where('status', 1)->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('user_id', function ($row) {
                        $user = User::where('id', $row->user_id)->first();
                        return $user->name;
                    })
                    ->addColumn('customer_id', function ($row) {
                        $customer = Customer::where('id', $row->customer_id)->first();
                        return $customer->name;
                    })
                    ->addColumn('price_sell', function ($row) {
                        return number_format($row->price_sell);
                    })
                    ->addColumn('num_factor', function ($row) {
                        $invoice_products = \DB::table('invoice_product')
                            ->where('invoice_id', $row->id)
                            ->whereNotNull('end')
                            ->get();
                        foreach ($invoice_products as $invoice_product)
                            $ids = \DB::table('schedulings')
                                ->where('detail_id', $invoice_product->id)
                                ->whereNotNull('end')
                                ->get();
                        foreach ($ids as $id)
                            $factor = \DB::table('exitproductbarnfacs')
                                ->where('detail_id', $id->pack)
                                ->get();
                        foreach ($factor as $item)
                            return $item->number_fac;


                    })
                    ->addColumn('num_havale', function ($row) {
                        $invoice_products = \DB::table('invoice_product')
                            ->where('invoice_id', $row->id)
                            ->whereNotNull('end')
                            ->get();
                        foreach ($invoice_products as $invoice_product)
                            $ids = \DB::table('schedulings')
                                ->where('detail_id', $invoice_product->id)
                                ->whereNotNull('end')
                                ->get();
                        foreach ($ids as $id)
                            $factor = \DB::table('_success_number_invoice')
                                ->where('scheduling_id', $id->pack)
                                ->get();
                        foreach ($factor as $item)
                            return $item->number;


                    })
                    ->addColumn('action', function ($row) {
                        return $this->actions($row);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('SellesArchive.list', compact('banks', 'selectstores'));

        }
        if ($role->name == "کارشناس فروش") {
            if ($request->ajax()) {
                $data = Invoice::where('user_id', $id)->where('status', 1)->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('user_id', function ($row) {
                        $user = User::where('id', $row->user_id)->first();
                        return $user->name;
                    })
                    ->addColumn('customer_id', function ($row) {
                        $customer = Customer::where('id', $row->customer_id)->first();
                        return $customer->name;
                    })
                    ->addColumn('price_sell', function ($row) {
                        return number_format($row->price_sell);
                    })
                    ->addColumn('num_factor', function ($row) {
                        $invoice_products = \DB::table('invoice_product')
                            ->where('invoice_id', $row->id)
                            ->whereNotNull('end')
                            ->get();
                        foreach ($invoice_products as $invoice_product)
                            $ids = \DB::table('schedulings')
                                ->where('detail_id', $invoice_product->id)
                                ->whereNotNull('end')
                                ->get();
                        foreach ($ids as $id)
                            $factor = \DB::table('exitproductbarnfacs')
                                ->where('detail_id', $id->pack)
                                ->get();
                        foreach ($factor as $item)
                            return $item->number_fac;


                    })
                    ->addColumn('num_havale', function ($row) {
                        $invoice_products = \DB::table('invoice_product')
                            ->where('invoice_id', $row->id)
                            ->whereNotNull('end')
                            ->get();
                        foreach ($invoice_products as $invoice_product)
                            $ids = \DB::table('schedulings')
                                ->where('detail_id', $invoice_product->id)
                                ->whereNotNull('end')
                                ->get();
                        foreach ($ids as $id)
                            $factor = \DB::table('_success_number_invoice')
                                ->where('scheduling_id', $id->pack)
                                ->get();
                        foreach ($factor as $item)
                            return $item->number;


                    })
                    ->addColumn('action', function ($row) {
                        return $this->actions($row);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('SellesArchive.list', compact('banks', 'selectstores'));

        }

    }

    public function actions($row)
    {
        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="چاپ فاکتور"
                       class="Print">
                        <i class="fa fa-print fa-lg" title="چاپ فاکتور"></i>
                       </a>&nbsp;&nbsp;';

        $btn = $btn . ' <a href="' . route('admin.print.detaill', $row->id) . '" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="جزییات فاکتور"
                       class="deletep">
                        <i class="fa fa-info-circle fa-lg" title="جزییات فاکتور"></i>
                       </a>';
        return $btn;
    }
}
