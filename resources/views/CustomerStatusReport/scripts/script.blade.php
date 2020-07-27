<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/jquery.maskedinput.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/datatab.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/row.js')}}" type="text/javascript"></script>
<link rel="stylesheet" href="{{asset('/public/css/kamadatepicker.min.css')}}">
<script src="{{asset('/public/js/kamadatepicker.min.js')}}"></script>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    var id_id = null;
    var id_customer = null;
    var intime = null;
    var totime = null;
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        load_data();

        function load_data(customer_id = '', indate = '', todate = '') {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                "bInfo": false,
                "paging": false,
                "bPaginate": false,
                "ordering": false,
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('td:eq(0)', nRow).css('background-color', '#e8ecff');
                    if (parseInt(aData.t) > parseInt("0")) {
                        $('td:eq(5)', nRow).css('background-color', 'rgba(204,255,141,0.65)');
                    } else if (parseInt(aData.t) < parseInt("0")) {
                        $('td:eq(5)', nRow).css('background-color', 'rgba(255,106,107,0.64)');
                    } else if (parseInt(aData.t) == parseInt("0")) {
                        $('td:eq(5)', nRow).css('background-color', 'rgba(0,183,255,0.64)');
                    }


                    var api = this.api(), aData;
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    var wedTotal = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return formatNumber(intVal(a) + intVal(b));
                        }, 0);
                    var thuTotal = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return formatNumber(intVal(a) + intVal(b));
                        }, 0);

                    var friTotal = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return formatNumber(intVal(a) + intVal(b));
                        }, 0);

                    $(api.column(0).footer()).html('جمع کل');
                    $(api.column(3).footer()).html(wedTotal);
                    $(api.column(4).footer()).html(thuTotal);
                    $(api.column(5).footer()).html(friTotal);


                    $('#sum_j').text(aData.customerr);
                    $('#sum_customer').text(aData.sum_customerr);

                    if (aData.sum_customer - aData.customer < 0) {
                        $('#summ').text(formatNumber(aData.sum_customer - aData.customer)).css('background-color', 'rgba(255,106,107,0.64)');

                    } else if (aData.sum_customer - aData.customer > 0) {
                        $('#summ').text(formatNumber(aData.sum_customer - aData.customer)).css('background-color', 'rgba(204,255,141,0.65)');

                    } else if (aData.sum_customer - aData.customer == 0) {
                        $('#summ').text(formatNumber(aData.sum_customer - aData.customer)).css('background-color', 'rgba(0,183,255,0.66)');
                    }
                }
                ,
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
                    ajax: "{{ route('admin.CustomerStatusReport.list') }}",
                    data:
                        {
                            customer_id: customer_id,
                            indate: indate,
                            todate: todate,
                        }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'id', name: 'id'},
                    {data: 'date', name: 'date'},
                    {data: 'price', name: 'price'},
                    {data: 'reciveprice', name: 'reciveprice'},
                    {data: 'total', name: 'total'},
                ]
            });
        }


        $('#filter').click(function () {
            var customer_id = $('#customer_id').val();
            id_customer = customer_id;
            var indate = $('#indate').val();
            intime = indate;
            var todate = $('#todate').val();
            totime = todate;
            $('.data-table').DataTable().destroy();
            $('.data').DataTable().destroy();
            load_data(customer_id, indate, todate);
            var data = {'id_customer': id_customer, "intime": intime, "totime": totime};
            $('.data').DataTable({
                processing: false,
                serverSide: true,
                stateSave: true,
                searching: false,
                "bInfo": false,
                "paging": false,
                "bPaginate": false,
                "ordering": false,
                "columnDefs": [
                    {"orderable": false, "targets": 0},
                ],
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('td:eq(0)', nRow).css('background-color', '#e8ecff');
                },
                "language": {
                    "search": "جستجو:",
                    "lengthMenu": "نمایش _MENU_",
                    "zeroRecords": "محصولی را انتخاب کنید",
                    "info": "نمایش _PAGE_ از _PAGES_",
                    "infoEmpty": "موردی یافت نشد",
                    "infoFiltered": "(جستجو از _MAX_ مورد)",
                    "processing": "در حال پردازش اطلاعات",
                },
                "ajax": {
                    "url": "{{ route('admin.CustomerStatusReport.list.detail') }}",
                    "data": data
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'type', name: 'type'},
                    {data: 'shenase', name: 'shenase'},
                    {data: 'date', name: 'date'},
                    {data: 'name', name: 'name'},
                    {data: 'name_user', name: 'name_user'},
                    {data: 'price', name: 'price'},
                    {data: 'status', name: 'status'},

                ]
            });

        });


        $('#bulk_delete').click(function (e) {
            e.preventDefault();
            $.ajax({
                data: {
                    'id_customer': id_customer,
                    'intime': intime,
                    'totime': totime,
                },
                url: "{{ route('admin.CustomerStatusReport.print') }}",
                dataType: 'html',
                success: function (data) {
                    if (data) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data);
                        w.document.close();
                        w.location.reload();
                    }
                }
            });
        });

    });

    $('#payment').addClass('active');

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
    $(document).ready(function () {
        $('#customer_id').select2({
            width: '100%',
            dir: 'rtl',
            placeholder: 'مشتریان',
            language: {
                noResults: function () {
                    return 'مشتری با این نام یافت نشد';
                },
            },
        });
    });
</script>
