@extends('layouts.master')
@section('content')
    @include('message.msg')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                       زمان بندی بارگیری
                    </div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                    <form autocomplete="off" id="productForm"
                          name="productForm" class="form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-3">
                                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="از تاریخ" />

                            </div>
                            <div class="col-md-1">
                                <button type="button" name="filter" id="filter" class="form-control btn btn-primary">جستجو</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered display data-table" id="data-table">
                        <thead style="background-color: #e8ecff">
                        <tr>
                            <th>کد</th>
                            <th>شماره فاکتور</th>
                            <th>فروشنده</th>
                            <th>خریدار</th>
                            <th>محصول</th>
                            <th>رنگ</th>
                            <th>تعداد</th>
                            <th>تعداد خارج شده</th>
                            <th>نوع وسیله</th>
                            <th>حمل از طرف</th>
                            <th>زمان حمل</th>
                            <th>توضیحات</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('Scheduling.modals.modal')
    @include('Scheduling.scripts.script')
@endsection
