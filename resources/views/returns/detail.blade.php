<!DOCTYPE html>
<html xml:lang="fa">
<head>
    <style>
        div.header {
            display: block;
            text-align: center;
            position: running(header);
        }

        div.footer {
            display: block;
            text-align: center;
            position: running(footer);
        }

        @page {
            @top-center {
                content: element(header)
            }
        }

        @page {
            @bottom-center {
                content: element(footer)
            }
        }
    </style>
    <title>سیستم مدیریت پولاد پویش</title>

    <style>
        @media print {
            .control-group {
                display: none;
            }

            #printer {
                display: none;
            }
        }
    </style>
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/public/icon/logo.png')}}"/>
    <link
        rel="stylesheet"
        href="{{asset('/public/css/2.css')}}">
    <style>
        table {
            font-family: 'Far.YagutBold', Tahoma, Sans-Serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: right;
            padding: 5px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
    <style>
        @font-face {
            font-family: 'Far.YagutBold';
            src: url('{{asset('/public/font/Weblogma_Yekan.eot')}}');
            src: local('☺'),
            url('{{asset('/public/font/Far_Yagut.woff')}}') format('woff'),
            url('{{asset('/public/font/Far_Yagut.ttf')}}') format('truetype'),
            url('{{asset('/public/font/Far_Yagut.svg')}}') format('svg');
            font-weight: normal;
            font-style: normal;
        }

        .myclass {
            font-family: 'Far.YagutBold', Tahoma, Sans-Serif;
            font-size: 17px;
        }


    </style>
    <style>
        th, td {
            border: 1px solid black;
            text-align: center;
        }

        hr {
            border-top: 1px solid black;
            margin-bottom: 0.4em;
            margin-top: 0.4em;
        }
    </style>
    <style type="text/css">
        textarea {
            border: 1px solid black;
            text-align: center;
        }
    </style>

</head>
<body dir="rtl" class="myclass" style="font-family: 'B Yekan'">
<br/>
<br/>
<div class="container-fluid">
    <div class="container-fluid">
        <?php
        $sum_invoice = null;
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">

                    <table>
                        <thead>
                        <tr>
                            <th style="background-color: rgba(135,142,145,0.5)">
                                    <span
                                        style="text-align: center;font-size: 15px">جزییات درخواست مرجوع کالای کد {{$id}}</span>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-10">

                                    </div>
                                    <div class="col-sm-2">

                                    </div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <br/>
                    <table>
                        <thead>
                        <tr>
                            <th style="background-color: rgba(23,0,255,0.14)">
                                <span style="text-align: center;font-size: 15px">مشخصات کالای مرجوعی</span>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-10">

                                    </div>
                                    <div class="col-sm-2">

                                    </div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <table>
                        <thead>
                        <tr>
                            <th width="220">نام مشتری</th>
                            <th>
                                @foreach($customers as $customer)
                                    @if($customer->id == $return->customer_id)
                                        {{$customer->name}}
                                    @endif
                                @endforeach
                            </th>
                            <th width="200">نام کارشناس فروش</th>
                            <th>
                                @foreach($invoices as $invoice)

                                    @if($invoice->id == $invoices_id->invoice_id)
                                        <?php
                                        $sum_invoice += $invoice->number_sell;
                                        ?>
                                        @foreach($users as $user)
                                            @if($invoice->user_id == $user->id)
                                                {{$user->name}}
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>تاریخ خروج از انبار</td>
                            <td>
                                {{\Morilog\Jalali\Jalalian::forge($return->created_at)->format('Y/m/d')}}
                            </td>
                            <td>شماره فاکتور فروش</td>
                            <td>


                                @foreach($invoices_ids as $invoices_id)
                                    @foreach($invoices as $invoice)
                                        @if($invoice->id == $invoices_id->invoice_id)
                                            {{$invoice->invoiceNumber}} -

                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>تعداد محصول فروخته شده</td>
                            <td>{{$sum_invoice}}</td>
                            <td>تعداد محصول مرجوع شده</td>
                            <td>{{$sum}}</td>
                        </tr>
                        <tr>
                            <td>کد شکایت مشتری مربوطه</td>
                            <td>
                                @if(!empty($complaints->code))
                                    <a href="{{route('admin.Complaints.list.detail', $invoices_id->complaints_id)}}">{{$complaints->code}}</a>
                                @endif
                            </td>
                            <td>هزینه حمل به عهده</td>
                            <td>{{$return->Cost}}</td>
                        </tr>
                        <tr>
                            <td>دلایل مشتری جهت عودت محصول</td>
                            <td colspan="3">{{$return->Description_m}}</td>
                        </tr>
                        <tr>
                            <td>دلایل واحد فروش جهت پذیرش</td>
                            <td colspan="3">{{$return->Description_v}}</td>
                        </tr>

                        </tbody>
                    </table>
                    <br/>
                    <br/>
                    @if(!empty($manager))
                        <table>
                            <thead>
                            <tr>
                                <th style="background-color: rgba(23,0,255,0.14)">
                                                            <span
                                                                style="text-align: center;font-size: 15px">اعلام نظر مدیر فروش</span>
                                    <br/>
                                    <div class="row">
                                        <div class="col-sm-10">

                                        </div>
                                        <div class="col-sm-2">

                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th width="250">توضیحات مدیر فروش</th>
                                <th>{{$manager->description}}</th>
                            </tr>
                            </thead>
                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th height="50" width="250">وضعیت</th>
                                @if($manager->status == 1)
                                    <th style="background-color: rgba(0,162,60,0.31)">
                                        تایید
                                    </th>

                                @else
                                    <th style="background-color: rgba(255,0,0,0.32)">
                                        عدم تایید
                                    </th>

                                @endif
                                    <th>امضاء مدیر فروش</th>
                                    <th>
                                        @foreach($users as $user)
                                            @if($user->id == $manager->user_id)
                                                @if(!empty($user->sign))
                                                    <img src="{{url($user->sign)}}" width="100" class="user-image"
                                                         alt="User Image">

                                                @endif
                                            @endif
                                        @endforeach
                                    </th>
                            </tr>
                            </thead>
                        </table>
                    @endif
                    <br/>
                    <br/>
                    @if(!empty($qc))
                        <table>
                            <thead>
                            <tr>
                                <th style="background-color: rgba(23,0,255,0.14)">
                                                            <span
                                                                style="text-align: center;font-size: 15px">اعلام نظر واحد کنترل کیفی</span>
                                    <br/>
                                    <div class="row">
                                        <div class="col-sm-10">

                                        </div>
                                        <div class="col-sm-2">

                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th height="50" width="250">تعداد محصول سالم</th>
                                <th>
                                    {{$Healthy}}
                                </th>
                                <th>تعداد محصول معیوب</th>
                                <th>
                                    {{$wastage}}
                                </th>
                            </tr>
                            </thead>
                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th height="50" width="250">وضعیت</th>
                                @if($qc->status == 1)
                                    <th style="background-color: rgba(0,162,60,0.31)">
                                        تایید
                                    </th>

                                @else
                                    <th style="background-color: rgba(255,0,0,0.32)">
                                        عدم تایید
                                    </th>

                                @endif
                                <th height="50" width="250">توضیحات تکمیلی</th>
                                <th>
                                    {{$qc->description}}
                                </th>
                                <th height="50" width="250">امضاء مدیر کنترل کیفیت</th>
                                <th>
                                    @foreach($users as $user)
                                        @if($user->id == $qc->user_id)
                                            @if(!empty($user->sign))
                                                <img src="{{url($user->sign)}}" width="100" class="user-image"
                                                     alt="User Image">
                                            @endif
                                        @endif
                                    @endforeach
                                </th>
                            </tr>
                            </thead>
                        </table>
                    @endif
                    <br/>
                    <br/>
                    @if(!empty($barn))
                        <table>
                            <thead>
                            <tr>
                                <th style="background-color: rgba(23,0,255,0.14)">
                                                            <span
                                                                style="text-align: center;font-size: 15px">اعلام نظر مسئول انبار</span>
                                    <br/>
                                    <div class="row">
                                        <div class="col-sm-10">

                                        </div>
                                        <div class="col-sm-2">

                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th width="250">توضیحات مسئول انبار در خصوص مرجوع بار</th>
                                <th>{{$barn->description}}</th>
                            </tr>
                            </thead>
                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th height="50" width="250">وضعیت</th>
                                @if($barn->status == 1)
                                    <th style="background-color: rgba(0,162,60,0.31)">
                                        تایید
                                    </th>

                                @else
                                    <th style="background-color: rgba(255,0,0,0.32)">
                                        عدم تایید
                                    </th>

                                @endif
                                <th>امضاء مسئول انبار</th>
                                <th>
                                    @foreach($users as $user)
                                        @if($user->id == $barn->user_id)
                                            @if(!empty($user->sign))
                                                <img src="{{url($user->sign)}}" width="100" class="user-image"
                                                     alt="User Image">

                                            @endif
                                        @endif
                                    @endforeach
                                </th>
                            </tr>
                            </thead>
                        </table>
                    @endif
                    <br/>
                    <br/>
                    <a class="btn btn-primary" href="javascript:void(0)" id="printer">تهیه نسخه چاپی</a>
                    <br/>

                </div>

            </div>

        </div>

    </div>


</div>


</body>
</html>


<script src="{{asset('/public/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('/public/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<script>


    $(document).ready(function () {
        persianToEnNumConvert();

    });

    $('#printer').click(function () {
        window.print();
    });


    function persianToEnNumConvert() {
        persianNums = {0: '۰', 1: '۱', 2: '۲', 3: '۳', 4: '۴', 5: '۵', 6: '۶', 7: '۷', 8: '۸', 9: '۹'};

        function change(el) {
            if (el.nodeType == 3) {
                var list = el.data.match(/[0-9]/g);
                if (list != null && list.length != 0) {
                    for (var i = 0; i < list.length; i++)
                        el.data = el.data.replace(list[i], persianNums[list[i]]);
                }
            }
            for (var i = 0; i < el.childNodes.length; i++) {
                change(el.childNodes[i]);
            }
        }

        change(document.body);
    }
</script>


