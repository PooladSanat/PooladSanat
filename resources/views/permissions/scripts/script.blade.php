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

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).css('background-color', '#e8ecff');
            },
            "columnDefs": [
                {"orderable": false, "targets": 0},
            ],
            "order": [[3, "Acs"]],
            "language": {
                "search": "جستجو:",
                "lengthMenu": "نمایش _MENU_",
                "zeroRecords": "موردی یافت نشد!",
                "info": "نمایش _PAGE_ از _PAGES_",
                "infoEmpty": "موردی یافت نشد",
                "infoFiltered": "(جستجو از _MAX_ مورد)",
                "processing": "در حال پردازش اطلاعات"
            },
            ajax: "{{ route('admin.user.permission') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex' , "className": "dt-center"},
                {data: 'name', name: 'name'},
                {data: 'label', name: 'label'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#createNewProduct').click(function () {
            $('#productForm').trigger("reset");
            $('#ajaxModel').modal('show');
            $('#caption').text('افزودن دسترسی');
            $('#product_id').val('');
        });

        $('body').on('click', '.editProduct', function () {
            var product_id = $(this).data('id');
            $.get("{{ route('admin.permission.update') }}" + '/' + product_id, function (data) {
                $('#ajaxModel').modal('show');
                $('#caption').text('ویرایش دسترسی');
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#label').val(data.label);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').text('در حال ثبت اطلاعات...');
            $('#saveBtn').prop("disabled", true);
            $.ajax({
                data: $('#productForm').serialize(),
                url: "{{ route('admin.permission.store') }}",
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
                            text: 'مشخصات دسترسی با موفقیت در سیستم ثبت شد',
                            icon: 'success',
                            confirmButtonText: 'تایید',
                        });
                        $('#product_id').val('');
                        $('#name').val('');
                        $('#label').val('');
                        $('#saveBtn').text('ثبت');
                        $('#saveBtn').prop("disabled", false);

                    }
                }
            });
        });

        $('#product_id').val('');

        $('#name').val('');

        $('#label').val('');

    });
    $('body').on('click', '.deleteProduct', function () {
        var id = $(this).data("id");
        Swal.fire({
            title: 'حذف دسترسی؟',
            text: "مشخصات حذف شده قابل بازیابی نیستند!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'حذف',
            cancelButtonText: 'انصراف',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{route('admin.permission.delete')}}" + '/' + id,
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function (data) {
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'موفق',
                            text: 'مشخصات دسترسی با موفقیت از سیستم حذف شد',
                            icon: 'success',
                            confirmButtonText: 'تایید'
                        })
                    }
                });
            }
        })
    });

    $('#admin-user').addClass('active');
</script>
