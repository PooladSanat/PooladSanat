@extends('layouts.master')
@section('content')
    @include('message.msg')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        صورت وضعیت مشتریان
                    </div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-3">
                            <label>نام مشتری:</label>
                            <select class="form-control" id="customer_id" name="customer_id">
                                <option value="0">تمام مشتریان</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>از تاریخ:</label>
                            <input autocomplete="off" type="text" id="indate" name="indate" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>تا تاریخ:</label>
                            <input autocomplete="off" type="text" id="todate" name="todate" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <br/>
                            <button type="button" name="filter" id="filter" class="form-control btn btn-primary">
                                جستجو
                            </button>
                        </div>
                    </div>
                    <br/>
                    <table class="table table-striped table-bordered data-table" id="data-table">
                        <thead style="background-color: #e8ecff">
                        <tr>
                            <th>ردیف</th>
                            <th>کد صورت حساب</th>
                            <th>تاریخ صدور</th>
                            <th>مبلغ صورت حساب(ریال)</th>
                            <th>جمع اسناد دریافتی(ریال)</th>
                            <th>مانده حساب(ریال)</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="5">جمع کل</th>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th colspan="5">جمع مبالغ در جریان</th>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th colspan="5">صورت وضعیت</th>
                            <th>0</th>
                        </tr>
                        </tfoot>
                    </table>
                    <div class="text-left">

                        <button style="width: 130px" type="submit" class="btn btn-success"
                                id="saveBtn" value="ثبت">
                            چاپ صورت وضعیت
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('CustomerStatusReport.modals.modal')
    @include('CustomerStatusReport.scripts.script')
@endsection
