<script src="{{asset('/public/js/a1.js')}}" type="text/javascript"></script>
<script src="{{asset('/public/js/a2.js')}}" type="text/javascript"></script>
<link rel="stylesheet" href="{{asset('/public/css/kamadatepicker.min.css')}}">
<script src="{{asset('/public/js/kamadatepicker.min.js')}}"></script>
<meta name="_token" content="{{ csrf_token() }}"/>
<script type="text/javascript">

    invoice_product = [];
    invoice_color = [];
        @foreach($products as $product)

    var invoice_products = {
            'id': "{{$product->id}}",
            'label': "{{$product->label}}",
        };
    invoice_product.push(invoice_products);
        @endforeach

        @foreach($colors as $color)

    var invoice_colors = {
            'id': "{{$color->id}}",
            'name': "{{$color->name}}",
        };
    invoice_color.push(invoice_colors);
    @endforeach

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
                url: "{{ route('admin.returns.store.store') }}",
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


<script language="javascript">


    added_inputs2_array = [];
    if (added_inputs2_array.length >= 1)
        for (var a in added_inputs2_array)
            added_inputs_array_table2(added_inputs2_array[a], a);


    function added_inputs_array_table2(data, a) {
        var myNode = document.createElement('div');

        myNode.id = 'invoicee' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<select id=\'invoice" + a + "\'  name=\"invoice[]\"\n" +
            "class=\"form-control\"/>" +
            "</select>" +
            "</div></div></div>";
        document.getElementById('invoicee').appendChild(myNode);


        var myNode = document.createElement('div');
        myNode.id = 'productt' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<select id=\'product" + a + "\'  name=\"product[]\"\n" +
            "class=\"form-control\"/>" +
            "</select>" +
            "</div></div></div>";
        document.getElementById('productt').appendChild(myNode);


        var myNode = document.createElement('div');
        myNode.id = 'colorr' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<select id=\'color" + a + "\'  name=\"color[]\"\n" +
            "class=\"form-control\"/>" +
            "</select>" +
            "</div></div></div>";
        document.getElementById('colorr').appendChild(myNode);


        var myNode = document.createElement('div');
        myNode.id = 'totalnumberr' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<input type=\"text\" id=\'totalnumber" + a + "\' readonly  name=\"totalnumber[]\"\n" +
            "class=\"form-control totalnumber\"/>" +
            "</div></div></div>";
        document.getElementById('totalnumberr').appendChild(myNode);


        var myNode = document.createElement('div');
        myNode.id = 'numberr' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<input type=\"text\" id=\'number" + a + "\'  name=\"number[]\"\n" +
            "class=\"form-control number\"/>" +
            "</div></div></div>";
        document.getElementById('numberr').appendChild(myNode);


        var myNode = document.createElement('div');
        myNode.id = 'reasonss' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<select id=\'reasons" + a + "\'  name=\"reasons[]\"\n" +
            "class=\"form-control\"/>" +
            "<option>انتخاب کنید</option>" +
            "<option value='1'>ایراد کیفی</option>" +
            "<option value='2'>ارسال محصول اشتباه</option>" +
            "<option value='3'>عدم نیاز مشتری</option>" +
            "<option value='4'>سایر</option>" +
            "</select>" +
            "</div></div></div>";
        document.getElementById('reasonss').appendChild(myNode);


        var myNode = document.createElement('div');
        myNode.id = 'actiont' + a;
        myNode.innerHTML += "<div class='form-group'>" +
            "<button onclick='deleteService2(" + a + ", event)' class=\"form-control btn btn-danger\"><i class=\"fa fa-remove\"></button></div>";
        document.getElementById('actiont').appendChild(myNode);


        $('#customer_id').change(function () {
            var invoices = [];
            var commodityID = null;
            commodityID = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{route('admin.invoice.number.return')}}?commodity_id=" + commodityID,
                success: function (res) {
                    if (res) {

                        $('#invoice' + a + '').empty();
                        $.each(res, function (key, value) {
                            invoices.push({
                                'id': value.id,
                                'invoice_id': value.invoiceNumber,
                            });
                        });
                        $('#invoice' + a + '').append('<option>' + "لطفا فاکتور را انتخاب کنید" + '</option>');
                        for (var i in invoices) {

                            $('#invoice' + a + '').append('<option value="' + invoices[i].id + '">' + invoices[i].invoice_id + '</option>');
                        }
                    } else {
                        $('#invoice' + a + '').append('<option>' + fsfsd + '</option>');

                    }
                }
            });
        }).change();

        $('#invoice' + a + '').change(function () {
            var products = [];
            var product = null;
            product = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{route('admin.invoice.product.return')}}?product=" + product,
                success: function (res) {
                    if (res) {
                        $('#product' + a + '').empty();
                        $('#product' + a + '').append('<option>' + "انتخاب محصول" + '</option>');
                        $.each(res, function (key, value) {
                            products.push({
                                'product_id': value.product_id,
                            });
                        });
                        for (var p in products) {
                            for (var io in invoice_product) {
                                if (invoice_product[io].id == products[p].product_id) {
                                    $('#product' + a + '').append('<option value="' + invoice_product[io].id + '">' + invoice_product[io].label + '</option>');

                                }
                            }
                        }
                    } else {
                    }
                }
            });
        }).change();

        $('#product' + a + ',#invoice' + a + '').change(function () {
            var colors = [];
            var color = null;
            var p = null;
            color = $('#invoice' + a + '').val();
            p = $('#product' + a + '').val();
            $.ajax({
                type: "GET",
                data: {
                    color: color,
                    p: p
                },
                url: "{{route('admin.invoice.color.return')}}",
                success: function (res) {
                    if (res) {
                        $('#color' + a + '').empty();
                        $('#color' + a + '').append('<option>' + "انتخاب رنگ" + '</option>');
                        $.each(res, function (key, value) {
                            colors.push({
                                'color_id': value.color_id,
                            });
                        });

                        for (var c in colors) {
                            for (var io in invoice_color) {
                                if (invoice_color[io].id == colors[c].color_id) {
                                    $('#color' + a + '').append('<option value="' + invoice_color[io].id + '">' + invoice_color[io].name + '</option>');
                                }
                            }
                        }
                    } else {
                    }
                }
            });
        }).change();


        $('#product' + a + ',#invoice' + a + ',#color' + a + '')
            .change(function () {
                var color = null;
                var p = null;
                var c = null;
                color = $('#invoice' + a + '').val();
                p = $('#product' + a + '').val();
                c = $('#color' + a + '').val();
                $.ajax({
                    type: "GET",
                    data: {
                        color: color,
                        p: p,
                        c: c,
                    },
                    url: "{{route('admin.invoice.totalnumber.return')}}",
                    success: function (res) {
                        if (res) {

                            $('#totalnumber' + a + '').val(res.salesNumber);
                        } else {
                        }
                    }
                });
            }).change();

    }

    function deleteService2(id, event) {
        event.preventDefault();
        $('#invoicee' + id).remove();
        $('#productt' + id).remove();
        $('#colorr' + id).remove();
        $('#numberr' + id).remove();
        $('#datee' + id).remove();
        $('#reasonss' + id).remove();
        $('#totalnumberr' + id).remove();
        $('#actiont' + id).remove();

    }

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    function addInput10() {


        var data = {
            'title': '',
            'icon': '',


        };
        added_inputs2_array.push(data);
        added_inputs_array_table2(data, added_inputs2_array.length - 1);

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

    }


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
