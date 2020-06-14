<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<link rel="stylesheet" href="{{asset('/public/css/kamadatepicker.min.css')}}">
<script src="{{asset('/public/js/kamadatepicker.min.js')}}"></script>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            "language": {
                "search": "جستجو:",
                "lengthMenu": "نمایش _MENU_",
                "zeroRecords": "موردی یافت نشد!",
                "info": "نمایش _PAGE_ از _PAGES_",
                "infoEmpty": "موردی یافت نشد",
                "infoFiltered": "(جستجو از _MAX_ مورد)",
                "processing": "در حال پردازش اطلاعات"
            },
            ajax: "{{ route('admin.returns.list') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'product_id', name: 'product_id'},
                {data: 'color_id', name: 'color_id'},
                {data: 'healthynumber', name: 'healthynumber'},
                {data: 'wastagenumber', name: 'wastagenumber'},
                {data: 'date', name: 'date'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });


        $('body').on('click', '.Print', function () {
            var id = $(this).data('id');
            $.get("{{ route('admin.invoice.check.print') }}" + '/' + id, function (data) {
                $('#ajaxModelPrint').modal('show');
                $('#selectstoressss').val(data.selectstores_id);
                $('#name_bankkk').val(data.bank_id);
                $('#dateeee').val(data.date);
                $('#timee').val(data.time);
                $('#descriptionnn').val(data.description);
            });
            $('#PrintSell').click(function (e) {
                var user = $('#CustomerPrint').serialize();
                $.ajax({
                    type: "GET",
                    url: "{{route('admin.invoice.print')}}?id=" + id,
                    data: user,
                    dataType: 'html',
                    success: function (res) {
                        if (res.errors) {
                            $('#ajaxModelPrint').modal('hide');
                            jQuery.each(data.errors, function (key, value) {
                                Swal.fire({
                                    title: 'خطا!',
                                    text: value,
                                    icon: 'error',
                                    confirmButtonText: 'تایید'
                                })
                            });
                        }
                        if (res) {
                            w = window.open(window.location.href, "_blank");
                            w.document.open();
                            w.document.write(res);
                            w.document.close();
                            w.location.reload();
                            $('#CustomerPrint').trigger("reset");
                            id = null;
                        } else {
                            id = null;
                        }
                    }
                });


            });
        });


        $('#createNewProduct').click(function () {
            $('#productForm').trigger("reset");
            $('#ajaxModel').modal('show');
            $('#caption').text('تعریف مرجوعی جدید');
            $('#product').val('');
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').text('در حال ثبت اطلاعات...');
            $('#saveBtn').prop("disabled", true);
            $.ajax({
                data: $('#productForm').serialize(),
                url: "{{ route('admin.returns.store') }}",
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
                        $('#productForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات با موفقیت در سیستم ثبت شد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
                        $('#saveBtn').text('ثبت');
                        $('#saveBtn').prop("disabled", false);
                    }
                }
            });
        });

    });

    $('#sell').addClass('active');
</script>

<script>
    kamaDatepicker('date',
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
