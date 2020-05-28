<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
    @php
        $dat = \Carbon\Carbon::now();
    $date = \Morilog\Jalali\Jalalian::forge($dat)->format('Y/m/d');
    @endphp
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
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    if (aData.status == 'عدم خروج') {
                        $('td:eq(10)', nRow).css('background-color', '#fb4e08');
                    } else if (aData.status == 'خروج کامل') {
                        $('td:eq(10)', nRow).css('background-color', '#84fb03');
                    } else if (aData.status == 'خروج ناقص') {
                        $('td:eq(10)', nRow).css('background-color', '#fbea04');
                    } else if (aData.status == 'اتمام یافته') {
                        $('td:eq(10)', nRow).css('background-color', '#fba6ec');
                    } else if (aData.status == 'تایید حواله') {
                        $('td:eq(10)', nRow).css('background-color', '#0080fb');
                    } else {
                        $('td', nRow).css('background-color', 'white');
                    }
                },
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
                    ajax: "{{ route('admin.scheduling.list') }}",
                    data: {from_date: from_date}
                },
                columns: [
                    {data: 'detail_id', name: 'detail_id'},
                    {data: 'user', name: 'user'},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'product', name: 'product'},
                    {data: 'number', name: 'number'},
                    {data: 'total', name: 'total'},
                    {data: 'type', name: 'type'},
                    {data: 'Carry', name: 'Carry'},
                    {data: 'time', name: 'time'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
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
            var product_id = $(this).data('id');
            $.get("{{ route('admin.scheduling.exit') }}" + '/' + product_id, function (data) {
                $('#ajaxModelExit').modal('show');
                $('#captioon').text('ثبت خروج از انبار');
                $('#product_id').val(product_id);
                $('#name').val(data.product_id.label);
                $('#namee').val(data.product_id.id);
                $('#color').val(data.color_id.name);
                $('#colorr').val(data.color_id.id);
                $('#n').val(data.data.number);
                $('#prod').val(product_id);
            })
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
                data: $('#productFormm').serialize(),
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
                        $('#productFormm').trigger("reset");
                        $('#ajaxModelExit').modal('hide');
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات سفارش با موفقیت در سیستم ثبت دشد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
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
