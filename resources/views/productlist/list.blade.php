@extends('layouts.master')
@section('content')
    @include('message.msg')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        صف تولید
                    </div>

                    <div class="tools"></div>

                </div>


                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-3">
                            <label>انتخاب محصول
                            </label>
                            <select id="sort" name="sort" class="form-control">
                                <option>انتخاب کنید</option>
                                @foreach($products as $productt)
                                    @foreach($product as $produc)
                                        @if($productt->id == $produc->product_id)
                                            <option value="{{$productt->id}}">{{$productt->label}}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br/>
                    <table class="table table-striped table-bordered data-table" id="data-table">
                        <thead style="background-color: #e8ecff">
                        <tr>
                            <th>ردیف</th>
                            <th>محصول</th>
                            <th>رنگ</th>
                            <th>تعداد(عدد)</th>
                            <th>تاریخ مورد نظر مشتری</th>
                            <th>تاریخ مورد نظر برنامه ریز</th>
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
    @include('productlist.modals.modal')
    @include('productlist.scripts.script')
@endsection
