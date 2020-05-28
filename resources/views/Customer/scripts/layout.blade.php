<script src="{{asset('/public/js/1.js')}}"></script>
<script src="{{asset('/public/assets/sweetalert.js')}}"></script>
<link href="{{asset('/public/css/1.css')}}" rel="stylesheet" id="bootstrap-css">
<style>
    .registercontpage {
        position: relative;
        z-index: 0;
        background: #fff;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 10px;
        font-size: 12px;
        border-radius: 50px;
    }

    .btn-circle {
        background: #000;
    }

    .btn-default[disabled] {
        background-color: #cccccc;
        border-color: #cccccc;
    }

    .stepwizard-step p {
        margin-top: 10px;
    }

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard {
        display: table;
        width: 50%;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;
    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 50px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    .field {
        position: relative;
        float: left;
        clear: both;
        margin: .35em 0;
        width: 100%;
    }


</style>
<style>
    .pt-3-half {
        padding-top: 1.4rem;
    }
</style>

<script src="{{asset('/public/js/2.js')}}"></script>
<script>


    $(document).ready(function () {
        $('#nextBtn').click(function () {
            var type = $('#type').val();
            if (type) {
                $.ajax({
                    type: "GET",
                    url: "{{route('admin.customers.filter')}}?type=" + type,
                    success: function (res) {
                        if (res == 1) {
                            $('#company').show();
                            $('#personal').hide();
                            $('#id').val('1');
                        } else {
                            $('#company').hide();
                            $('#personal').show();
                            $('#id').val('2');
                        }
                    }
                });
            }
        });
        $('#country').change(function () {
            var commodityID = $(this).val();
            if (commodityID) {
                $.ajax({
                    type: "GET",
                    url: "{{route('admin.list.check.city')}}?commodity_id=" + commodityID,
                    success: function (res) {
                        if (res) {
                            $("#city").empty();
                            $("#city").append('<option>لطفا شهر را انتخاب کنید</option>');
                            $.each(res, function (key, value) {
                                $("#city").append('<option value="' + value.zone_id + '">' + value.name + '</option>');
                            });
                        } else {
                            $("#city").empty();
                        }
                    }
                });
            } else {
                $("#city").empty();
            }
        });


        $('#city').change(function () {
            var commodityID = $(this).val();
            if (commodityID) {
                $.ajax({
                    type: "GET",
                    url: "{{route('admin.list.check.state')}}?commodity_id=" + commodityID,
                    success: function (res) {
                        if (res) {
                            console.log(res);
                            $("#staate").empty();
                            $("#staate").append('<option>لطفا منطقه را انتخاب کنید</option>');
                            $.each(res, function (key, value) {
                                $("#staate").append('<option value="' + value.id + '">' + value.areas + '</option>');
                            });
                        } else {
                            $("#staate").empty();
                        }
                    }
                });
            } else {
                $("#staate").empty();
            }
        });
    });

</script>
<script>
    function numberOnly(input) {
        var regex = /[^0-9]/gi;
        input.value = input.value.replace(regex, "");
    }

    $(document).ready(function () {
        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);
            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allNextBtn.click(function () {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url'],textarea[textarea]"),
                isValid = true;
            console.log(curStepBtn);
            $(".form-group").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                console.log(curInputs);
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid)
                nextStepWizard.removeAttr('disabled').trigger('click');
        });

        $('div.setup-panel div a.btn-primary').trigger('click');
    });

</script>
<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').text('در حال ثبت اطلاعات...');
            $('#saveBtn').prop("disabled", true);
            var form = $('#productForm')[0];
            var data = new FormData(form);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{ route('admin.customers.store') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
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
                        Swal.fire({
                            title: 'موفق',
                            text: data.success,
                            icon: 'success',
                            confirmButtonText: 'تایید'
                        }).then((result) => {

                            location.reload();

                        });
                        $('#saveBtn').text('ثبت');
                        $('#saveBtn').prop("disabled", false);

                    }
                }
            });
        });
    });

</script>
<script>

    $(function () {
        $("#btnAdd").bind("click", function () {
            var div = $("<tr />");
            div.html(GetDynamicTextBox(""));
            $("#TextBoxContainer").append(div);
        });
        $("body").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
    });

    function GetDynamicTextBox(value) {


        return '<td><input name = "per_side_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><select name="per_sex_company[]" class="form-control"><option>انتخاب کنید</option><option value="1"> مرد</option><option  value="2"> زن</option></select></td>' +
            '<td><input name = "per_title_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "per_name_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "per_phone_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "per_inside_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "per_email_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "per_tel_company_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><button type="button" data-original-title="حذف پرسنل" class="btn btn-danger remove"><i class="fa fa-remove"></i></button></td>'
    }

</script>
<script>

    $(function () {
        $("#btnAddbank").bind("click", function () {
            var div = $("<tr />");
            div.html(GetDynamicTextBoxx(""));
            $("#TextBoxContainerbank").append(div);
        });
        $("body").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
    });

    function GetDynamicTextBoxx(value) {


        return '<td><input name = "name_bank_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "branch_bank_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "account_bank_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "date_bank_company[]" type="text" value = "' + value + '" class="form-control example1" /></td>' +
            '<td><button type="button" data-original-title="حذف پرسنل" class="btn btn-danger remove"><i class="fa fa-remove"></i></button></td>'
    }

</script>
<script>

    $(function () {
        $("#btnAddtamin").bind("click", function () {
            var div = $("<tr />");
            div.html(GetDynamicTextBoxxx(""));
            $("#TextBoxContainertamin").append(div);
        });
        $("body").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
    });

    function GetDynamicTextBoxxx(value) {


        return '<td><input name = "name_securing_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><input name = "date_securing_company[]" type="text" value = "' + value + '" class="form-control" /></td>' +
            '<td><button type="button" data-original-title="حذف پرسنل" class="btn btn-danger remove"><i class="fa fa-remove"></i></button></td>'
    }

</script>
<style>
    .vertical {
        border-left: 1px solid black;
        height: 470px;
        position: absolute;
        margin-top: 40px;
        left: 50%;
    }
</style>
