<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Scheduling;
use DB;
use Illuminate\Http\Request;
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

            $data = \DB::table('view_unionreportmontly')
                ->whereBetween('date', array($indate, $todate))
                ->distinct()
                ->orderBy('date', 'ASC')
                ->get(['date']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('total', function ($row) {
                    $total = DB::table('view_unionreportmontly')
                        ->where('date', $row->date)
                        ->sum('total');
                    return $total;

                })
                ->addColumn('sum', function ($row) {

                    $sum = DB::table('view_unionreportmontly')
                        ->where('date', $row->date)
                        ->sum('sum');
                    return number_format($sum);
                })
                ->addColumn('sa', function ($row) {


                    $number_return = DB::table('view_unionreportmontly')
                        ->where('date', $row->date)
                        ->sum('number_return');
                    return $number_return;


                })
                ->addColumn('as', function ($row) {

                    $number = DB::table('view_unionreportmontly')
                        ->where('date', $row->date)
                        ->sum('number');
                    return $number;

                })
                ->rawColumns([])
                ->make(true);

        }
        return view('ReportMonthly.list');
    }
}
