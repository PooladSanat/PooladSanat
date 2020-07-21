@extends('layouts.master')
@section('content')
    @include('message.msg')


    <div class="row">
        <div class="col-md-12">
            <div class="portlet box red-pink">
                <div class="portlet-title">
                    <div class="caption">
                        لیست پیش فاکتور های لغو شده
                    </div>
                </div>
                <div class="portlet-body">
                    <form autocomplete="off" id="productForm"
                          name="productForm" class="form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <input type="text" name="from_date" id="from_date" class="form-control example1" placeholder="از تاریخ" />

                            </div>
                            <div class="col-md-3">
                                <input type="text" name="to_date" id="to_date" class="form-control example1" placeholder="تا تاریخ"/>

                            </div>
                            <div class="col-md-1">
                                <button type="button" name="filter" id="filter" class="form-control btn btn-primary">جستجو</button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" name="refresh" id="refresh" class="form-control btn btn-default">تازه سازی</button>

                            </div>
                        </div>
                    </form>
                    <br/>
                    <hr/>
                    <br/>
                    <table class="table table-striped table-bordered data-table" id="data-table">
                        <thead style="background-color: #e8ecff">
                        <tr>
                            <th>
                                <input type="checkbox" id="select_all">
                            </th>
                            <th>شماره پیش فاکتور</th>
                            <th>تاریخ صدور</th>
                            <th>نام فروشنده</th>
                            <th>نام خریدار</th>
                            <th>تعداد(عدد)</th>
                            <th>مبلغ محصولات(ریال)</th>
                            <th>نحوه پرداخت</th>
                            <th>نوع فاکتور</th>
                            <th>وضعیت</th>
                            <th>جمع کل(ریال)</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <button type="button" name="bulk_delete"
                            id="bulk_delete" class="btn btn-danger"
                    >حذف
                    </button>

                </div>

            </div>
        </div>
    </div>
    @include('sell.modals.trash')
    @include('sell.scripts.trash')
@endsection
