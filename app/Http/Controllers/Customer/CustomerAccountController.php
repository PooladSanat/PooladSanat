<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\CustomerAccount;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\Exception;
use Morilog\Jalali\Jalalian;
use Yajra\DataTables\DataTables;

class CustomerAccountController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('debtor', function ($row) {
                    $debtor = \DB::table('factors')
                        ->where('customer_id', $row->id)
                        ->sum('sum');
                    return number_format($debtor);
                })
                ->addColumn('creditor', function ($row) {
                    $creditor = \DB::table('customer_accounts')
                        ->where('customer_id', $row->id)
                        ->sum('creditor');
                    return number_format($creditor);
                })
                ->addColumn('action', function ($row) {
                    return $this->actions($row);
                })
                ->rawColumns([])
                ->make(true);
        }
        return view('customeraccount.list');
    }

    public function store(Request $request)
    {
        $created = Carbon::now()->timezone('Asia/Tehran');
        $price = null;
        $CustomerAccount = CustomerAccount::where('customer_id', $request->customer_ider)
            ->first();
        $count = count($request->price);
        for ($i = 0; $i < $count; $i++) {
            try {
                $price = $price + $request->price[$i];
                \DB::table('detail_customer_payment')
                    ->insert([
                        'customer_id' => $request->customer_ider,
                        'type' => $request->type[$i],
                        'shenase' => $request->shenase[$i],
                        'price' => $request->price[$i],
                        'date' => $request->date[$i],
                        'name' => $request->name[$i],
                        'name_user' => $request->user_name[$i],
                        'created_at' => $created,
                    ]);
            } catch (Exception $exception) {
            }
        }
        if (empty($CustomerAccount)) {
            CustomerAccount::create([
                'customer_id' => $request->customer_ider,
                'creditor' => $price,
            ]);
        } else {
            CustomerAccount::where('customer_id', $request->customer_ider)
                ->update([
                    'creditor' => $CustomerAccount->creditor + $price,
                ]);
        }
        return response()->json(['success' => 'success']);
    }

    public function detail(Request $request)
    {


        if ($request->ajax()) {
            $data = \DB::table('detail_customer_payment')
                ->where('customer_id', $request->id_id)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_id', function ($row) {
                    $customer = Customer::where('id', $row->customer_id)->first();
                    return $customer->name;
                })
                ->addColumn('type', function ($row) {
                    if ($row->type == 1) {
                        return 'نقدی';
                    } else {
                        return 'چک';
                    }
                })
                ->addColumn('shenase', function ($row) {
                    if ($row->shenase == null) {
                        return '----';
                    } else {
                        return $row->shenase;
                    }
                })
                ->addColumn('name', function ($row) {
                    if ($row->name == null) {
                        return '----';
                    } else {
                        return $row->name;
                    }
                })
                ->addColumn('name_user', function ($row) {
                    if ($row->name_user == null) {
                        return '----';
                    } else {
                        return $row->name_user;
                    }
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('created', function ($row) {
                    return Jalalian::forge($row->created_at)->format('Y/m/d');
                })
                ->addColumn('action', function ($row) {
                    return $this->action($row);
                })
                ->rawColumns([])
                ->make(true);
        }
        return view('customeraccount.listdetail');
    }

    public function list($id)
    {

        return view('customeraccount.listdetail', compact('id'));

    }

    public function update($id)
    {
        $data = \DB::table('detail_customer_payment')
            ->where('id', $id)
            ->first();
        return response()->json($data);

    }

    public function edit(Request $request)
    {
        $CustomerAccount = CustomerAccount::where('customer_id', $request->id_customer)->first();
        $detail_customer_payment = \DB::table('detail_customer_payment')
            ->where('id', $request->id_payment)->first();
        \DB::beginTransaction();
        try {
            CustomerAccount::where('customer_id', $request->id_customer)
                ->update([
                    'creditor' => $CustomerAccount->creditor - $detail_customer_payment->price + $request->price,
                ]);
            \DB::table('detail_customer_payment')
                ->where('id', $request->id_payment)
                ->update([
                    'type' => $request->type,
                    'name' => $request->name,
                    'name_user' => $request->name_user,
                    'shenase' => $request->shanase,
                    'price' => $request->price,
                    'date' => $request->date,
                ]);
            \DB::commit();
        } catch (Exception $exception) {
            \DB::rollBack();
        }
        return response()->json(['success' => 'success']);

    }

    public function delete($id)
    {
        $detail_customer_payment = \DB::table('detail_customer_payment')
            ->where('id', $id)->first();
        $CustomerAccount = CustomerAccount::where('customer_id', $detail_customer_payment->customer_id)->first();
        \DB::beginTransaction();
        try {
            CustomerAccount::where('customer_id', $detail_customer_payment->customer_id)
                ->update([
                    'creditor' => $CustomerAccount->creditor - $detail_customer_payment->price,
                ]);
            $post = \DB::table('detail_customer_payment')
                ->where('id', $id)->delete();
            \DB::commit();
        } catch (Exception $exception) {
            \DB::rollBack();
        }
        return response()->json($post);
    }

    public function actions($row)
    {
        $btn = null;


        $btn = '<a href="' . route('admin.CustomerAccount.list', $row->id) . '">
                     <i class="fa fa-eye fa-lg" title="ریز پرداختی مشتری"></i>
                      </a>';


        return $btn;

    }

    public function action($row)
    {
        $btn = null;


        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ویرایش"
                       class="editpayment">
                       <i class="fa fa-edit fa-lg" title="ویرایش"></i>
                       </a>&nbsp;&nbsp;';

        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="حذف"
                       class="deletepayment">
                       <i class="fa fa-trash fa-lg" title="حذف"></i>
                       </a>';


        return $btn;

    }
}