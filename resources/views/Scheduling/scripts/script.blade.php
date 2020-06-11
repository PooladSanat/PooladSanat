<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
<script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<link rel="stylesheet" href="{{asset('/public/css/kamadatepicker.min.css')}}">
<script src="{{asset('/public/js/kamadatepicker.min.js')}}"></script>
<style>


    .as-console-wrapper {
        display: none !important;
    }
</style>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
    @php
        $dat = \Carbon\Carbon::now();
    $date = \Morilog\Jalali\Jalalian::forge($dat)->format('Y/m/d');
    @endphp
    $('#from_date').val('{{$date}}');
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        load_data();

        function load_data(from_date = '{{$date}}') {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                "ordering": false,
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    if (aData.status == 'عدم خروج') {
                        $('td:eq(11)', nRow).css('background-color', '#ff6a6b');
                    } else if (aData.status == 'خروج کامل') {
                        $('td:eq(11)', nRow).css('background-color', '#ccff8d');
                    } else if (aData.status == 'خروج ناقص') {
                        $('td:eq(11)', nRow).css('background-color', '#fffa81');
                    } else if (aData.status == 'اتمام یافته') {
                        $('td:eq(11)', nRow).css('background-color', '#ec97dd');
                    } else if (aData.status == 'تایید حواله') {
                        $('td:eq(11)', nRow).css('background-color', '#90ddfb');
                    } else {
                        $('td', nRow).css('background-color', 'white');
                    }

                    if (parseInt(aData.total) == parseInt(aData.number)) {
                        $('td:eq(6)', nRow).css('background-color', '#ccff8d');
                    } else if (parseInt(aData.total) == parseInt("0")) {
                        $('td:eq(6)', nRow).css('background-color', '#ff6a6b');
                    } else if (parseInt(aData.total) < parseInt(aData.number)) {
                        $('td:eq(6)', nRow).css('background-color', '#fffa82');
                    } else {
                        $('td:eq(6)', nRow).css('background-color', 'white');
                    }
                }
                ,
                "language":
                    {
                        "search":
                            "جستجو:",
                        "lengthMenu":
                            "نمایش _MENU_",
                        "zeroRecords":
                            "موردی یافت نشد!",
                        "info":
                            "نمایش _PAGE_ از _PAGES_",
                        "infoEmpty":
                            "موردی یافت نشد",
                        "infoFiltered":
                            "(جستجو از _MAX_ مورد)",
                        "processing":
                            "در حال پردازش اطلاعات"
                    },
                ajax: {
                    ajax: "{{ route('admin.scheduling.list') }}",
                    data:
                        {
                            from_date: from_date
                        }
                },
                columns: [
                    {data: 'detail_id', name: 'detail_id'},
                    {data: 'pack', name: 'pack', visible: false},
                    {data: 'user', name: 'user'},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'product', name: 'product'},
                    {data: 'color', name: 'color'},
                    {data: 'number', name: 'number'},
                    {data: 'total', name: 'total'},
                    {data: 'type', name: 'type'},
                    {data: 'Carry', name: 'Carry'},
                    {data: 'time', name: 'time'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                rowsGroup:
                    [
                        1, 2, 3, 8, 9, 10, 11, 12, 13,
                    ],

            });

        }

        $('#filter').click(function () {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' && to_date != '') {
                $('#data-table').DataTable().destroy();
                load_data(from_date, to_date);
            } else {
                Swal.fire({
                    title: 'توجه',
                    text: 'بازه تاریخ مورد نظر را انتخاب کنید!',
                    icon: 'info',
                    confirmButtonText: 'تایید'
                });

            }
        });

        $('#refresh').click(function () {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#data-table').DataTable().destroy();
            load_data();
        });

        $('body').on('click', '.change-date', function () {
            var id = $(this).data('id');
            $('#changedateModel').modal('show');
            $('#id_d').val(id);
        });

        $('#saveBtndate').click(function (e) {
            e.preventDefault();
            $('#saveBtndate').text('در حال ثبت اطلاعات...');
            $('#saveBtndate').prop("disabled", true);
            $.ajax({
                data: $('#changedateForm').serialize(),
                url: "{{ route('admin.scheduling.update.date') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if (data.errors) {
                        $('#changedateModel').modal('hide');
                        jQuery.each(data.errors, function (key, value) {
                            Swal.fire({
                                title: 'خطا!',
                                text: value,
                                icon: 'error',
                                confirmButtonText: 'تایید'
                            })
                        });
                        $('#saveBtndate').text('ثبت');
                        $('#saveBtndate').prop("disabled", false);
                    }
                    if (data.success) {
                        $('#changedateForm').trigger("reset");
                        $('#changedateModel').modal('hide');
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات با موفقیت در سیستم ثبت دشد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
                        $('#saveBtndate').text('ثبت');
                        $('#saveBtndate').prop("disabled", false);
                    }
                }
            });
        });

        $('body').on('click', '.plus-number', function () {
            var product_id = $(this).data('id');
            $.get("{{ route('admin.scheduling.update') }}" + '/' + product_id, function (data) {
                $('#ajaxModel').modal('show');
                $('#caption').text('ثبت شماره حواله حسابداری');
                $('#number').val(data.number);
                $('#product_id').val(product_id);
                $('#id').val(data.id);
            })
        });

        $('body').on('click', '.plus-exit', function () {
            $('#detail-table').DataTable().destroy();
            var product_id = $(this).data('id');
            $.get("{{ route('admin.scheduling.exit') }}" + '/' + product_id, function (data) {
                $('#ajaxModelExit').modal('show');
                $('#captioon').text('ثبت خروج از انبار');

                var tablee = $('.detail-table').DataTable({
                    processing: true,
                    serverSide: true,
                    "ordering": false,
                    "searching": false, "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false,
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
                        url: "{{ route('admin.scheduling.detail.list') }}",
                        data: {product_id: product_id}
                    },

                    columns: [
                        {data: 'product', name: 'product', orderable: false, searchable: false},
                        {data: 'color', name: 'color', orderable: false, searchable: false},
                        {data: 'number', name: 'number', orderable: false, searchable: false},
                        {data: 'actionn', name: 'actionn', orderable: false, searchable: false},
                    ],
                });

            })
        });

        $('body').on('click', '.plus-number-exit', function () {
            var product_id = $(this).data('id');
            $('#ajaxModelExitDetail').modal('show');
            $('#captionEx').text('خروج از انبار');
            $('#proder').val(product_id);
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').text('در حال ثبت اطلاعات...');
            $('#saveBtn').prop("disabled", true);
            $.ajax({
                data: $('#productForme').serialize(),
                url: "{{ route('admin.scheduling.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if (data.errors) {
                        $('#ajaxModel').modal('hide');
                        jQuery.each(data.errors, function (key, value) {
                            Swal.fire({
                                title: 'خطا!',
                                text: value,
                                icon: 'error',
                                confirmButtonText: 'تایید'
                            })
                        });
                        $('#saveBtn').text('ثبت');
                        $('#saveBtn').prop("disabled", false);
                    }
                    if (data.success) {
                        $('#productForme').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات سفارش با موفقیت در سیستم ثبت دشد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
                        $('#saveBtn').text('ثبت');
                        $('#saveBtn').prop("disabled", false);
                    }
                }
            });
        });

        $('#exitBtn').click(function (e) {
            e.preventDefault();
            $('#exitBtn').text('در حال ثبت اطلاعات...');
            $('#exitBtn').prop("disabled", true);
            $.ajax({
                data: $('#productFormeeeeeee').serialize(),
                url: "{{ route('admin.scheduling.store.exit') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if (data.errors) {
                        $('#ajaxModelExit').modal('hide');
                        jQuery.each(data.errors, function (key, value) {
                            Swal.fire({
                                title: 'خطا!',
                                text: value,
                                icon: 'error',
                                confirmButtonText: 'تایید'
                            })
                        });
                        $('#exitBtn').text('ثبت');
                        $('#exitBtn').prop("disabled", false);
                    }
                    if (data.success) {
                        $('#productFormeeeeeee').trigger("reset");
                        $('#ajaxModelExitDetail').modal('hide');
                        $('#detail-table').DataTable().ajax.reload();
                        $('#data-table').DataTable().ajax.reload();
                        $('#exitBtn').text('ثبت');
                        $('#exitBtn').prop("disabled", false);
                    }
                }
            });
        });

        $('#okBtn').click(function (e) {
            e.preventDefault();
            $('#okBtn').text('در حال ثبت اطلاعات...');
            $('#okBtn').prop("disabled", true);
            $.ajax({
                data: $('#productFormdf').serialize(),
                url: "{{ route('admin.scheduling.store.exit.fac') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if (data.errors) {
                        $('#ajaxModelfac').modal('hide');
                        jQuery.each(data.errors, function (key, value) {
                            Swal.fire({
                                title: 'خطا!',
                                text: value,
                                icon: 'error',
                                confirmButtonText: 'تایید'
                            })
                        });
                        $('#okBtn').text('ثبت');
                        $('#okBtn').prop("disabled", false);
                    }
                    if (data.success) {
                        $('#productFormdf').trigger("reset");
                        $('#ajaxModelfac').modal('hide');
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات سفارش با موفقیت در سیستم ثبت دشد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
                        $('#okBtn').text('ثبت');
                        $('#okBtn').prop("disabled", false);
                    }
                }
            });
        });

        $('body').on('click', '.send-fac', function () {
            var product_id = $(this).data('id');
            $('#ajaxModelfac').modal('show');
            $('#captioson').text('ثبت شماره فاکتور');
            $('#produc').val(product_id);
        });

        $('body').on('click', '.cancel', function () {
            var id = $(this).data('id');
            $('#cancelModel').modal('show');
            $('#id_p').val(id);
        });

        $('#saveBtncancel').click(function (e) {
            e.preventDefault();
            $('#saveBtncancel').text('در حال ثبت اطلاعات...');
            $('#saveBtncancel').prop("disabled", true);
            $.ajax({
                data: $('#cancelForm').serialize(),
                url: "{{ route('admin.scheduling.cancel.detail') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if (data.errorr) {
                        $('#schedulingModal').modal('hide');
                        jQuery.each(data.errorr, function (key, value) {
                            Swal.fire({
                                title: 'خطا!',
                                text: value,
                                icon: 'error',
                                confirmButtonText: 'تایید'
                            })
                        });
                        $('#saveBtncancel').text('ثبت');
                        $('#saveBtncancel').prop("disabled", false);
                    }

                    if (data.error) {
                        $('#cancelForm').trigger("reset");
                        $('#cancelModel').modal('hide');
                        Swal.fire({
                            title: 'خطا!',
                            text: 'تعداد ارسالی برای بارگیری نمیتواند بیشتر از تعداد درخواستی مشتری باشد!',
                            icon: 'error',
                            confirmButtonText: 'تایید',
                        });
                        $('#saveBtncancel').text('ثبت');
                        $('#saveBtncancel').prop("disabled", false);
                    }
                    if (data.success) {
                        $('#cancelForm').trigger("reset");
                        $('#cancelModel').modal('hide');
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات با موفقیت در سیستم ثبت شد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
                        $('#saveBtncancel').text('ثبت');
                        $('#saveBtncancel').prop("disabled", false);
                    }
                }
            });
        });

    });

    $('body').on('click', '.success-plus', function () {
        var id = $(this).data("id");
        Swal.fire({
            title: 'تایید و ثبت سفارش!',
            text: "سفارش تایید شده برای ثبت شماره حواله ارسال خواهد شد!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'تایید',
            cancelButtonText: 'انصراف',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.scheduling.success')}}" + '/' + id,
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function (data) {
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات سفارش با موفقیت در سیستم تایید و ثبت شد',
                            icon: 'success',
                            confirmButtonText: 'تایید'
                        })
                    }
                });
            }
        })
    });

    $('#sell').addClass('active');

</script>

<script>
    kamaDatepicker('from_date',
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
