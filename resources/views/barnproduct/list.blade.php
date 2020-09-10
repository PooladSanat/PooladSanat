@extends('layouts.master')
@section('content')
    @include('message.msg')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        لیست موجودی انبار کالاهای ساخته شده
                    </div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered data-table" id="data-table">
                        <thead style="background-color: #e8ecff">
                        <tr>
                            <th style="width: 1px">ردیف</th>
                            <th>محصول</th>
                            <th>رنگ</th>
                            <th>موجودی فیزیکی</th>
                            <th>تعداد تقاضا داده شده</th>
                            <th>موجودی قابل فروش</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @can('افزایش موجودی انبار')
                        <a class="btn btn-primary" href="javascript:void(0)" id="createNewProduct">افزایش موجودی</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @include('barnproduct.modals.modal')
    @include('barnproduct.scripts.script')
@endsection
