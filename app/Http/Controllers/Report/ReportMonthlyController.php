<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Scheduling;
use Cache;
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
                    return $row;
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
                    return $number;

                })
                ->rawColumns([])
                ->make(true);

        }
        return view('ReportMonthly.list');
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
