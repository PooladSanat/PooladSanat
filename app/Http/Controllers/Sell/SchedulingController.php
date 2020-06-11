<?php

namespace App\Http\Controllers\Sell;

use App\Color;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Product;
use App\Scheduling;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mockery\Exception;
use Morilog\Jalali\Jalalian;
use Response;
use Validator;
use Yajra\DataTables\DataTables;


class SchedulingController extends Controller
{

    public function list(Request $request)
    {
        $id = auth()->user()->id;
        $role_id = \DB::table('role_user')
            ->where('user_id', $id)->first();
        $role = DB::table('roles')
            ->where('id', $role_id->role_id)
            ->first();
        if ($role->name == "مدیریت" or $role->name == "Admin" or $role->name == "کارشناس فروش و مالی" or $role->name == "مسئول حمل و نقل") {
            if ($request->ajax()) {
                $data = Scheduling::where('date', $this->convert2english($request->from_date))
                    ->orderBy('id', 'desc')
                    ->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Product::where('id', $product_id->product_id)->first();
                        return $name->label;
                    })
                    ->addColumn('invoice', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Invoice::where('id', $product_id->invoice_id)->first();
                        return $name->invoiceNumber;
                    })
                    ->addColumn('color', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Color::where('id', $product_id->color_id)->first();
                        return $name->name;
                    })
                    ->addColumn('user', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Invoice::where('id', $product_id->invoice_id)->first();
                        $username = User::where('id', $name->user_id)->first();
                        return $username->name;
                    })
                    ->addColumn('customer_id', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Invoice::where('id', $product_id->invoice_id)->first();
                        $username = Customer::where('id', $name->customer_id)->first();
                        return $username->name;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status != 6) {
                            if (!empty($row->statusfull)) {
                                if ($row->statusfull == 8) {
                                    return 'خروج کامل';
                                } elseif ($row->statusfull == 9) {
                                    return 'خروج ناقص';
                                }
                            } else {
                                if ($row->status == 0) {
                                    return 'ثبت اولیه';
                                } elseif ($row->status == 1) {
                                    return 'تایید ثبت';
                                } elseif ($row->status == 2) {
                                    return 'تایید حواله';
                                } elseif ($row->status == 3) {
                                    return 'خروج کامل';
                                } elseif ($row->status == 4) {
                                    return 'خروج ناقص';
                                } elseif ($row->status == 5) {
                                    return 'عدم خروج';
                                } elseif ($row->status == 6) {
                                    return 'اتمام یافته';
                                }
                            }

                        } else {
                            return 'اتمام یافته';
                        }

                    })
                    ->addColumn('action', function ($row) {
                        return $this->actions($row);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('Scheduling.list');
        }
        if ($role->name == "کارشناس فروش") {
            if ($request->ajax()) {
                $data = Scheduling::where('user_id', $id)
                    ->where('date', $this->convert2english($request->from_date))
                    ->orderBy('id', 'desc')
                    ->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Product::where('id', $product_id->product_id)->first();
                        return $name->label;
                    })
                    ->addColumn('invoice', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Invoice::where('id', $product_id->invoice_id)->first();
                        return $name->invoiceNumber;
                    })
                    ->addColumn('color', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Color::where('id', $product_id->color_id)->first();
                        return $name->name;
                    })
                    ->addColumn('user', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Invoice::where('id', $product_id->invoice_id)->first();
                        $username = User::where('id', $name->user_id)->first();
                        return $username->name;
                    })
                    ->addColumn('customer_id', function ($row) {
                        $product_id = \DB::table('invoice_product')
                            ->where('id', $row->detail_id)
                            ->first();
                        $name = Invoice::where('id', $product_id->invoice_id)->first();
                        $username = Customer::where('id', $name->customer_id)->first();
                        return $username->name;
                    })
                    ->addColumn('status', function ($row) {

                        if (!empty($row->statusfull)) {
                            if ($row->statusfull == 8) {
                                return 'خروج کامل';
                            } elseif ($row->statusfull == 9) {
                                return 'خروج ناقص';
                            }
                        } else {
                            if ($row->status == 0) {
                                return 'ثبت اولیه';
                            } elseif ($row->status == 1) {
                                return 'تایید ثبت';
                            } elseif ($row->status == 2) {
                                return 'تایید حواله';
                            } elseif ($row->status == 3) {
                                return 'خروج کامل';
                            } elseif ($row->status == 4) {
                                return 'خروج ناقص';
                            } elseif ($row->status == 5) {
                                return 'عدم خروج';
                            } elseif ($row->status == 6) {
                                return 'اتمام یافته';
                            }
                        }

                    })
                    ->addColumn('action', function ($row) {
                        return $this->actions($row);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('Scheduling.list');
        }

    }

    public function detaillist(Request $request)
    {
        if ($request->ajax()) {
            $dataa = Scheduling::where('pack', $request->product_id)
                ->orderBy('id', 'desc')->get();
            return Datatables::of($dataa)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    $product_id = \DB::table('invoice_product')
                        ->where('id', $row->detail_id)
                        ->first();
                    $name = Product::where('id', $product_id->product_id)->first();
                    return $name->label;
                })
                ->addColumn('color', function ($row) {
                    $product_id = \DB::table('invoice_product')
                        ->where('id', $row->detail_id)
                        ->first();
                    $name = Color::where('id', $product_id->color_id)->first();
                    return $name->name;
                })
                ->addColumn('actionn', function ($row) {
                    return $this->actionn($row);
                })
                ->rawColumns(['actionn'])
                ->make(true);
        }
        return view('Scheduling.list');

    }

    public function success($id)
    {
        $data = Scheduling::where('pack', $id)->update([
            'status' => 1,
        ]);
        return response()->json($data);

    }

    public function update($id)
    {


        $product = \DB::table('_success_number_invoice')
            ->where('scheduling_id', $id)
            ->first();
        return response()->json($product);
    }

    public function StoreExit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numberrr' => 'required',
        ], [
            'numberrr.required' => 'لطفا مقدار خارج شده از انبار را وارد کنید',
        ]);
        $id = Scheduling::where('id', $request->proder)->first();
        $id_p = \DB::table('invoice_product')
            ->where('id', $id->detail_id)->first();
        $barn = \DB::table('barns_products')
            ->where('product_id', $id_p->product_id)
            ->where('color_id', $id_p->color_id)
            ->first();
        $exitproductbarn = \DB::table('exitproductbarn')
            ->where('id', $request->id_exittt)
            ->first();
        $min = $id->number - $request->numberrr;
        if ($validator->passes()) {
            if (!empty($request->id_exittt)) {
                \DB::beginTransaction();
                try {
                    \DB::table('exitproductbarn')
                        ->where('id', $request->id_exittt)
                        ->update([
                            'number' => $request->numberrr,
                        ]);
                    \DB::table('barns_products')
                        ->where('product_id', $id_p->product_id)
                        ->where('color_id', $id_p->color_id)
                        ->update([
                            'Inventory' => $barn->Inventory + $exitproductbarn->number,
                        ]);
                    \DB::table('invoice_product')
                        ->where('id', $id->detail_id)
                        ->update([
                            'leftover' => $id_p->leftover + $exitproductbarn->number,
                            'end' => null,
                        ]);

                    $idd = Scheduling::where('id', $request->proder)->first();
                    $id_pp = \DB::table('invoice_product')
                        ->where('id', $id->detail_id)->first();
                    $barnn = \DB::table('barns_products')
                        ->where('product_id', $id_p->product_id)
                        ->where('color_id', $id_p->color_id)
                        ->first();
                    $minn = $idd->number - $request->numberrr;


                    if ($request->numberr == $id_p->salesNumber) {
                        Scheduling::find($request->proder)->update([
                            'total' => $request->numberrr,
                        ]);
                        \DB::table('barns_products')
                            ->where('product_id', $id_p->product_id)
                            ->where('color_id', $id_p->color_id)
                            ->update([
                                'Inventory' => $barnn->Inventory - $request->numberrr,
                                'NumberSold' => $barnn->NumberSold - $request->numberrr,
                            ]);
                        \DB::table('invoice_product')
                            ->where('id', $idd->detail_id)
                            ->update([
                                'leftover' => $id_pp->leftover - $minn,
                            ]);
                        if (!empty($id->statusfull)) {
                            if ($id->statusfull == 8) {
                                Scheduling::where('pack', $id->pack)->update([
                                    'statusfull' => 8,
                                ]);
                            }
                        } else {
                            Scheduling::where('pack', $id->pack)->update([
                                'statusfull' => 8,
                            ]);
                        }
                    } elseif ($request->numberrr < $id_p->salesNumber and $request->numberrr > 0) {
                        \DB::table('invoice_product')
                            ->where('id', $idd->detail_id)
                            ->update([
                                'leftover' => $id_pp->leftover - $request->numberrr,
                                'end' => null,
                            ]);
                        Scheduling::find($request->proder)->update([
                            'total' => $request->numberrr,
                        ]);
                        \DB::table('barns_products')
                            ->where('product_id', $id_p->product_id)
                            ->where('color_id', $id_p->color_id)
                            ->update([
                                'Inventory' => $barnn->Inventory - $request->numberrr,
//                                'NumberSold' => $barnn->NumberSold - $idd->number,
                            ]);
                        if (!empty($id->statusfull)) {
                            if ($id->statusfull == 8) {
                                Scheduling::where('pack', $id->pack)->update([
                                    'statusfull' => 9,
                                ]);
                            }
                        } else {
                            Scheduling::where('pack', $id->pack)->update([
                                'statusfull' => 9,
                            ]);
                        }

                    } elseif ($request->numberrr == 0) {
                        Scheduling::find($request->proder)->update([
                            'status' => 5,
                            'statusfull' => null,
                            'end' => 2,
                            'total' => $request->numberrr,
                        ]);
                    }


                    \DB::commit();
                    return response()->json(['success' => 'success']);
                } catch (Exception $exception) {
                    \DB::rollBack();
                }

            } else {
                \DB::beginTransaction();
                try {
                    \DB::table('exitproductbarn')
                        ->insert([
                            'detail_id' => $request->proder,
                            'number' => $request->numberrr,
                        ]);
                    if ($request->numberrr == $id_p->salesNumber) {
                        Scheduling::find($request->proder)->update([
                            'total' => $request->numberrr,
                        ]);
                        \DB::table('barns_products')
                            ->where('product_id', $id_p->product_id)
                            ->where('color_id', $id_p->color_id)
                            ->update([
                                'Inventory' => $barn->Inventory - $request->numberrr,
                                'NumberSold' => $barn->NumberSold - $request->numberrr,
                            ]);
                        \DB::table('invoice_product')
                            ->where('id', $id->detail_id)
                            ->update([
                                'leftover' => $id->number - $request->numberrr + $id_p->leftover,
                            ]);
                        if (!empty($id->statusfull)) {
                            if ($id->statusfull == 8) {
                                Scheduling::where('pack', $id->pack)->update([
                                    'statusfull' => 8,
                                ]);
                            }
                        } else {
                            Scheduling::where('pack', $id->pack)->update([
                                'statusfull' => 8,
                            ]);
                        }
                    } elseif ($request->numberrr < $id_p->salesNumber and $request->numberrr > 0) {
                        \DB::table('invoice_product')
                            ->where('id', $id->detail_id)
                            ->update([
                                'leftover' => $min + $id_p->leftover,
                                'end' => null,
                            ]);
                        Scheduling::find($request->proder)->update([
                            'total' => $request->numberrr,
                        ]);
                        \DB::table('barns_products')
                            ->where('product_id', $id_p->product_id)
                            ->where('color_id', $id_p->color_id)
                            ->update([
                                'Inventory' => $barn->Inventory - $request->numberrr,
                                'NumberSold' => $barn->NumberSold - $id->number,
                            ]);

                        if (!empty($id->statusfull)) {
                            if ($id->statusfull == 8) {
                                Scheduling::where('pack', $id->pack)->update([
                                    'statusfull' => 9,
                                ]);
                            }
                        } else {
                            Scheduling::where('pack', $id->pack)->update([
                                'statusfull' => 9,
                            ]);
                        }


                    } elseif ($request->numberrr == 0) {
                        Scheduling::find($request->proder)->update([
                            'status' => 5,
                            'statusfull' => null,
                            'end' => 2,
                            'total' => $request->numberrr,
                        ]);

                    }
                    \DB::commit();
                    return response()->json(['success' => 'success']);
                } catch (Exception $exception) {
                    \DB::rollBack();
                }
            }
        }
        return Response::json(['errors' => $validator->errors()]);


    }

    public function ExitFac(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required',
        ], [
            'number.required' => 'لطفا شماره فاکتور را وارد کنید',
        ]);
        $ids = Scheduling::where('pack', $request->produc)->get();
        foreach ($ids as $id)
            $detai = \DB::table('invoice_product')
                ->where('id', $id->detail_id)
                ->first();
        $details = \DB::table('invoice_product')
            ->where('id', $id->detail_id)
            ->where('status', null)
            ->get();
        if ($validator->passes()) {
            \DB::beginTransaction();
            try {
                \DB::table('exitproductbarnfacs')
                    ->insert([
                        'detail_id' => $request->produc,
                        'number_fac' => $request->number,
                    ]);
                Scheduling::where('pack', $request->produc)->update([
                    'status' => 6,
                    'end' => 1,
                ]);
                \DB::table('invoice_product')
                    ->where('id', $id->detail_id)
                    ->update([
                        'status' => 1,
                    ]);
                foreach ($details as $detail) {
                    if (empty($detail)) {
                        \DB::table('invoices')
                            ->where('id', $detai->invoice_id)
                            ->update([
                                'status' => 1,
                            ]);
                    }
                }
                \DB::commit();
                return response()->json(['success' => 'success']);
            } catch (Exception $exception) {
                \DB::rollBack();
            }
        }
        return Response::json(['errors' => $validator->errors()]);


    }

    public function exit($id)
    {
        $data = Scheduling::where('pack', $id)->get();
        foreach ($data as $datum) {
            $product = \DB::table('invoice_product')
                ->where('id', $datum->detail_id)
                ->get();
        }
        return response()->json($product);

        $product_id = Product::find($product->product_id);
        $color_id = Color::find($product->color_id);
        $exitproductbarn = \DB::table('exitproductbarn')
            ->where('detail_id', $id)
            ->first();
        return response()->json(['product_id' => $product_id,
            'color_id' => $color_id,
            'data' => $data,
            'exitt' => $exitproductbarn]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required',
        ], [
            'number.required' => 'لطفا شماره حواله را وارد کنید',
        ]);
        $data = Carbon::now();
        if ($validator->passes()) {
            if (empty($request->id)) {
                \DB::beginTransaction();
                try {
                    \DB::table('_success_number_invoice')
                        ->insert([
                            'number' => $request->number,
                            'scheduling_id' => $request->product_id,
                            'created_at' => $data,
                        ]);
                    Scheduling::where('pack', $request->product_id)->update([
                        'status' => 2,
                    ]);
                    \DB::commit();
                    return response()->json(['success' => 'success']);
                } catch (Exception $exception) {
                    \DB::rollBack();
                }
            } else {
                \DB::beginTransaction();
                try {
                    \DB::table('_success_number_invoice')
                        ->where('id', $request->id)
                        ->update([
                            'number' => $request->number,
                            'created_at' => $data,
                        ]);
                    Scheduling::where('pack', $request->product_id)->update([
                        'status' => 2,
                    ]);
                    \DB::commit();
                    return response()->json(['success' => 'success']);
                } catch (Exception $exception) {
                    \DB::rollBack();
                }
            }
        }
        return Response::json(['errors' => $validator->errors()]);


    }

    public function updatedate(Request $request)
    {
        $schedulings = DB::table('schedulings')->latest('id')->first();
        $scheduling = DB::table('schedulings')
            ->where('id', $request->id_d)->first();
        try {
            DB::beginTransaction();
            Scheduling::create([
                'detail_id' => $scheduling->detail_id,
                'number' => $scheduling->number,
                'type' => $scheduling->type,
                'Carry' => $scheduling->Carry,
                'date' => $this->convert2english($request->date),
                'time' => $scheduling->time,
                'description' => $scheduling->description,
                'pack' => '1000' . $schedulings->id,
                'status' => '0',
            ]);
            Scheduling::where('id', $request->id_d)
                ->update([
                    'end' => null,
                    'total' => '0',
                ]);
            Scheduling::where('pack', $scheduling->pack)->update([
                'statusfull' => '9',
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
        return response()->json(['success' => 'success']);
    }

    public function canceldetail(Request $request)
    {
        $id = Scheduling::where('id', $request->id_p)->first();
        $detai = \DB::table('invoice_product')
            ->where('id', $id->detail_id)
            ->first();
        $barn = \DB::table('barns_products')
            ->where('product_id', $detai->product_id)
            ->where('color_id', $detai->color_id)
            ->first();
        \DB::table('_cancel_detail_product')
            ->insert([
                'scheduling_id' => $request->id_p,
                'reason' => $request->reason,
                'description' => $request->description,

            ]);
        Scheduling::where('pack', $id->pack)->update([
            'statusfull' => '9',
            'end' => null,
        ]);
        \DB::table('invoice_product')
            ->where('id', $id->detail_id)
            ->update([
                'leftover' => $id->number + $detai->leftover,
                'end' => null,
            ]);
        \DB::table('barns_products')
            ->where('product_id', $detai->product_id)
            ->where('color_id', $detai->color_id)
            ->update([
                'NumberSold' => $barn->NumberSold - $id->number,
            ]);

        return response()->json(['success' => 'success']);

    }

    public function actions($row)
    {
        $btn = null;
        if ($row->status == 5 and $row->end == 2) {
            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="تغیر تاریخ بارگیری"
                       class="change-date">
                  <i class="fa fa-history fa-lg" title="تغیر تاریخ بارگیری"></i>
                       </a>&nbsp;&nbsp;';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="انصراف فروش"
                       class="cancel">
                  <i class="fa fa-times fa-lg" title="انصراف فروش"></i>
                       </a>&nbsp;&nbsp;';

        }
        if ($row->end == null) {
            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->pack . '" data-original-title="ویرایش"
                       class="success-plus">
                  <i class="fa fa-check fa-lg" title="تایید و ثبت"></i>
                       </a>&nbsp;&nbsp;';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->pack . '" data-original-title="ویرایش"
                       class="plus-number">
                  <i class="fa fa-exchange fa-lg" title="ثبت شماره حواله حسابداری"></i>
                       </a>&nbsp;&nbsp;';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->pack . '" data-original-title="ویرایش"
                       class="plus-exit">
                  <i class="fa fa-send fa-lg" title="ثبت اطلاعات خروج"></i>
                       </a>&nbsp;&nbsp;';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->pack . '" data-original-title="ویرایش"
                       class="send-fac">
                  <i class="fa fa-plus fa-lg" title="ثبت شماره فاکتور"></i>
                       </a>&nbsp;&nbsp;';
        }


        return $btn;

    }

    public function actionn($row)
    {
        $btn = null;
        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"
                      data-id="' . $row->id . '" data-original-title="ثبت تعداد خروجی"
                       class="plus-number-exit">
                  <i class="fa fa-plus fa-lg" title="ثبت تعداد خروجی"></i>
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
