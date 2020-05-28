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
            "language": {
                "search": "جستجو:",
                "lengthMenu": "نمایش _MENU_",
                "zeroRecords": "موردی یافت نشد!",
                "info": "نمایش _PAGE_ از _PAGES_",
                "infoEmpty": "موردی یافت نشد",
                "infoFiltered": "(جستجو از _MAX_ مورد)",
                "processing": "در حال پردازش اطلاعات"
            },
            ajax: "{{ route('admin.receiptproduct.list') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'product_id', name: 'product_id'},
                {data: 'color_id', name: 'color_id'},
                {data: 'number', name: 'number'},
                {data: 'created', name: 'created'},
                {data: 'action', name: 'action'},
            ]
        });


        $('body').on('click', '.checkProduct', function () {
            var id = $(this).data("id");
            Swal.fire({
                title: 'تایید ورودی تولید به انبار!',
                text: "مقدار تولیدذ شده با تایید شما به انبار اضافه حواهد شد",
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
                        url: "{{route('admin.receiptproduct.wizard')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#data-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'موفق',
                                text: 'تعداد تولید شده با موفقیت به انبار اضافه شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });


    });
    $('#barn').addClass('active');
</script>
