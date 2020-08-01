<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/jquery.maskedinput.js')}}" type="text/javascript"></script>
<link rel="stylesheet" href="{{asset('/public/css/kamadatepicker.min.css')}}">
<script src="{{asset('/public/js/kamadatepicker.min.js')}}"></script>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">

    <?php
    $v = verta();
    $months = $v->month;
    if ($months < 10) {
        $year = $v->year;
        $month = $months;
        $m = 0;
    } else {
        $year = $v->year;
        $month = $months;
    }
    ?>
    $('#indate').val({{$year}}+'/' + '{{$m}}'+{{$month}}+
    '/' + '01'
    )
    ;
    $('#todate').val({{$year}}+'/' + '{{$m}}'+{{$month}}+
    '/' + '31'
    )
    ;


    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    var ind = {{$year}}+'/' + '{{$m}}'+{{$month}}+
    '/' + '01';
    var tod = {{$year}}+'/' + '{{$m}}'+{{$month}}+
    '/' + '31';

    $(function () {


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        load_data();


        function load_data(indate = ind, todate = tod) {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('td:eq(0)', nRow).css('background-color', '#e8ecff');

                    var api = this.api(), aData;
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    var wedTotal = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var thuTotal = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return formatNumber(intVal(a) + intVal(b));
                        }, 0);

                    var friTotal = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var friTotaal = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    $(api.column(0).footer()).html('جمع کل');
                    $(api.column(1).footer()).html(wedTotal);
                    $(api.column(2).footer()).html(thuTotal);
                    $(api.column(3).footer()).html(friTotal);
                    $(api.column(4).footer()).html(friTotaal);


                }
                ,
                "bInfo": false,
                "paging": false,
                "bPaginate": false,
                "columnDefs": [
                    {"orderable": false, "targets": 0},
                ],
                "order": [[1, "ACS"]],
                "language": {
                    "search": "جستجو:",
                    "lengthMenu": "نمایش _MENU_",
                    "zeroRecords": "موردی یافت نشد!",
                    "info": "نمایش _PAGE_ از _PAGES_",
                    "infoEmpty": "موردی یافت نشد",
                    "infoFiltered": "(جستجو از _MAX_ مورد)",
                    "processing": "در حال پردازش اطلاعات"
                },

                ajax: {
                    ajax: "{{ route('admin.ReportMonthly.list') }}",
                    data:
                        {
                            indate: indate,
                            todate: todate,
                        }
                },
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'total', name: 'total'},
                    {data: 'sum', name: 'sum'},
                    {data: 'sa', name: 'sa'},
                    {data: 'as', name: 'as'},

                ],
                "order": [[0, "desc"]]
            });
        }


        $('#filter').click(function () {
            var indate = $('#indate').val();
            var todate = $('#todate').val();
            $('.data-table').DataTable().destroy();
            load_data(indate, todate);
        });

    });

    $('#report').addClass('active');

</script>
<script>
    kamaDatepicker('indate',
        {
            buttonsColor: "red",
            forceFarsiDigits: false,
            sync: true,
            gotoToday: true,
            highlightSelectedDay: true,
            markHolidays: true,
            markToday: true,
            previousButtonIcon: "fa fa-arrow-circle-left",
            nextButtonIcon: "fa fa-arrow-circle-right",

        });
    kamaDatepicker('todate',
        {
            buttonsColor: "red",
            forceFarsiDigits: false,
            sync: true,
            gotoToday: true,
            highlightSelectedDay: true,
            markHolidays: true,
            markToday: true,
            previousButtonIcon: "fa fa-arrow-circle-left",
            nextButtonIcon: "fa fa-arrow-circle-right",

        });
</script>
