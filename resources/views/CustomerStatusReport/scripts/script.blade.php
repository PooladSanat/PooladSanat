<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/jquery.maskedinput.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/datatab.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/row.js')}}" type="text/javascript"></script>
<link rel="stylesheet" href="{{asset('/public/css/kamadatepicker.min.css')}}">
<script src="{{asset('/public/js/kamadatepicker.min.js')}}"></script>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
    var id_id = null;
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
                    "ordering": false,
                    "bInfo": false,
                    "paging": false,
                    "bPaginate": false,
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
                        {data: 'price', name: 'price'},
                        {data: 'price', name: 'price'},
                        ]
                });
            }

            $('#filter').click(function () {
                var customer_id = $('#customer_id').val();
                var indate = $('#indate').val();
                var todate = $('#todate').val();
                $('.data-table').DataTable().destroy();
                load_data(customer_id, indate, todate);
            });
        }
    );

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
