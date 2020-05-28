@extends('layouts.master')
@section('content')
    <script src="{{asset('/public/js/10.js')}}"></script>
    <style>
        .container {
            width: 65%;
        }

        h2 {
            text-align: center;
        }
    </style>
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    نمودارهای آماری
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#activity" data-toggle="tab">هدف تعدادی</a></li>
                                <li><a href="#settings" data-toggle="tab">فروش ریالی</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <main class="container">
                                        <div>
                                            <canvas id="TargetNumber"></canvas>
                                        </div>
                                        <hr/>
                                        <br/>
                                        <div class="col-md-4">

                                            <label class="col-md-4">فروشنده:</label>
                                            <select class="form-control col-md-8"
                                                    name="id_user" id="id_user">
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-md-3">سال:</label>
                                            <select name="year" id="year" class="form-control col-md-9">
                                                <option value="1398">1398</option>
                                                <option value="1399">1399</option>
                                                <option value="1400">1400</option>
                                                <option value="1401">1401</option>
                                                <option value="1402">1402</option>
                                                <option value="1403">1403</option>
                                                <option value="1404">1404</option>
                                                <option value="1405">1405</option>
                                                <option value="1406">1406</option>
                                                <option value="1407">1407</option>
                                                <option value="1408">1408</option>
                                                <option value="1409">1409</option>
                                                <option value="1410">1410</option>
                                                <option value="1411">1411</option>
                                                <option value="1412">1412</option>
                                                <option value="1413">1413</option>
                                            </select>
                                        </div>
                                        <label></label>
                                        <div class="col-md-4">
                                            <button style="width: 130px" type="submit"
                                                    class="btn btn-success col-md-4"
                                                    id="search_target_number" value="جستجو">
                                                جستجو
                                            </button>
                                        </div>
                                    </main>
                                </div>
                                <div class="tab-pane" id="settings">
                                    <main class="container">
                                        <div>
                                            <canvas id="SellPrice"></canvas>
                                        </div>
                                        <hr/>
                                        <br/>
                                        <div class="col-md-4">
                                            <label class="col-md-4">فروشنده:</label>
                                            <select class="form-control col-md-8"
                                                    name="id_sell" id="id_sell">
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-md-3">سال:</label>
                                            <select name="year_sell" id="year_sell" class="form-control col-md-9">
                                                <option value="1398">1398</option>
                                                <option value="1399">1399</option>
                                                <option value="1400">1400</option>
                                                <option value="1401">1401</option>
                                                <option value="1402">1402</option>
                                                <option value="1403">1403</option>
                                                <option value="1404">1404</option>
                                                <option value="1405">1405</option>
                                                <option value="1406">1406</option>
                                                <option value="1407">1407</option>
                                                <option value="1408">1408</option>
                                                <option value="1409">1409</option>
                                                <option value="1410">1410</option>
                                                <option value="1411">1411</option>
                                                <option value="1412">1412</option>
                                                <option value="1413">1413</option>
                                            </select>
                                        </div>
                                        <label></label>
                                        <div class="col-md-4">
                                            <button style="width: 130px" type="submit"
                                                    class="btn btn-success col-md-4"
                                                    id="search_sell_price" value="جستجو">
                                                جستجو
                                            </button>
                                        </div>
                                    </main>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
    <script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var TargetNumber;
            var SellPrice;
            var ctx = document.getElementById("TargetNumber").getContext('2d');
            TargetNumber = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
                    datasets: [{
                        label: '',
                        data: [],
                    }, {
                        label: '',
                        data: [],
                    }]

                },


            });
            var price = document.getElementById("SellPrice").getContext('2d');
            SellPrice = new Chart(price, {
                type: 'bar',
                data: {
                    labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
                    datasets: [{
                        label: '',
                        data: [],
                    }, {
                        label: '',
                        data: [],
                    }]

                },


            });


            $('#search_target_number').click(function () {
                var id = $('#id_user').val();
                var year = $('#year').val();
                var data = '&id=' + id + '&year=' + year;
                $.ajax({
                    type: "GET",
                    url: "{{route('home.chart')}}?data=" + data,
                    success: function (res) {
                        if (res.target != null || res.a != 0) {
                            var ctx = document.getElementById("TargetNumber").getContext('2d');
                            TargetNumber = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
                                    datasets: [{
                                        label: 'هدف سال',
                                        data: [res.fa, res.ma, res.Ju,
                                            res.Ar, res.Au, res.Se,
                                            res.sta, res.Ab, res.Fi,
                                            res.Ja, res.Av, res.Ma],
                                        backgroundColor: "rgba(255,0,0,1)"
                                    }, {
                                        label: 'فروش سال',
                                        data: [res.farvardin, res.may, res.June, res.Arrows
                                            , res.August, res.September, res.stamp
                                            , res.Aban, res.Fire, res.January, res.Avalanche, res.March],
                                        backgroundColor: "rgba(0,0,255,1)"
                                    }]
                                },
                            });

                        } else {
                            Swal.fire({
                                title: 'توجه!',
                                text: 'فروشنده مورد نظر امسال هدف و فروشی نداشته است!',
                                icon: 'info',
                                confirmButtonText: 'تایید'
                            });
                            var ctx = document.getElementById("TargetNumber").getContext('2d');
                            TargetNumber = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
                                    datasets: [{
                                        label: '',
                                        data: [],
                                    }, {
                                        label: '',
                                        data: [],
                                    }]
                                },
                            });
                        }
                    }
                });

                TargetNumber.destroy();
            });


            $('#search_sell_price').click(function () {
                var id_sell = $('#id_sell').val();
                var year_sell = $('#year_sell').val();
                var data = '&id=' + id_sell + '&year=' + year_sell;
                $.ajax({
                    type: "GET",
                    url: "{{route('home.chart.sell')}}?data=" + data,
                    success: function (res) {

                        if (res.invoices != null) {
                            var ctx = document.getElementById("SellPrice").getContext('2d');
                            SellPrice = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
                                    datasets: [{
                                        label: 'فروش سال',
                                        data: [res.farvardin, res.may, res.June, res.Arrows
                                            , res.August, res.September, res.stamp
                                            , res.Aban, res.Fire, res.January, res.Avalanche, res.March],
                                        backgroundColor: "rgba(0,0,255,1)"
                                    }]
                                },
                            });

                        } else {
                            Swal.fire({
                                title: 'توجه!',
                                text: 'برای فروشنده مورد نظر در این سال فروشی ثبت نشده است!',
                                icon: 'info',
                                confirmButtonText: 'تایید'
                            });
                            var ctx = document.getElementById("SellPrice").getContext('2d');
                            SellPrice = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
                                    datasets: [{
                                        label: '',
                                        data: [],
                                    }, {
                                        label: '',
                                        data: [],
                                    }]
                                },
                            });
                        }
                    }
                });

                SellPrice.destroy();
            });

        });

    </script>
@endsection
