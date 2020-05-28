<?php

namespace App\Http\Controllers\Barn;

use App\BarnTemporary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BarnTemporaryController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = BarnTemporary::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('device_id', function ($row) {
                    return $row->device->name;
                })
                ->addColumn('product_id', function ($row) {
                    return $row->product->label;
                })
                ->addColumn('color_id', function ($row) {
                    return $row->color->manufacturer . ' - ' . $row->color->name;
                })
                ->rawColumns([])
                ->make(true);

        }
        return view('barntemporary.list');


    }
}
