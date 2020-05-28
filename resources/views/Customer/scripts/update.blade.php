<script src="{{asset('/public/js/1.js')}}"></script>
<script src="{{asset('/public/assets/sweetalert.js')}}"></script>
<script type="text/javascript">
    $(window).bind("load", function () {
        var D_date = $('#D_date').val();
        $('#date').val(D_date);
    });
</script>
<script src="{{asset('/public/js/2.js')}}"></script>
<script>


    $(document).ready(function () {
        $('#nextBtn').click(function () {
            var H_Established_company = $('#H_Established_company').val();
            var D_date_birth = $('#D_date_birth').val();
            var D_date_work_company = $('#D_date_work_company').val();
            var D_date_personel = $('#D_date_personel').val();
            var C_credibilitylicense_work_company = $('#C_credibilitylicense_work_company').val();
            $('#Established_company').val(H_Established_company);
            $('#date_birth').val(D_date_birth);
            $('#date_work_company').val(D_date_work_company);
            $('#date_personel').val(D_date_personel);
            $('#credibilitylicense_work_company').val(C_credibilitylicense_work_company);


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
        var tests = $('#country').val();
        var zone_id = $('#zone_id').val();
        var areas_id = $('#areas_id').val();
        $.ajax({
            url: "{{route('admin.list.check.city')}}?commodity_id=" + tests,
            context: document.body,
            success: function (res) {
                if (res) {
                    $("#city").empty();
                    $("#city").append('<option>لطفا شهر را انتخاب کنید</option>');
                    $.each(res, function (key, value) {
                        $("#city").val(zone_id);
                        $("#city").append('<option value="' + value.zone_id + '">' + value.name + '</option>');
                    });
                } else {
                    $("#city").empty();
                }
            }
        });
        $.ajax({
            url: "{{route('admin.list.check.state')}}?commodity_id=" + zone_id,
            context: document.body,
            success: function (res) {
                if (res) {
                    $("#staate").empty();
                    $("#staate").append('<option>لطفا منطقه را انتخاب کنید</option>');
                    $.each(res, function (key, value) {
                        console.log(areas_id);
                        $("#staate").val(areas_id);
                        $("#staate").append('<option value="' + value.id + '">' + value.areas + '</option>');
                    });
                } else {
                    $("#staate").empty();
                }
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
                            $("#staate").empty();
                            $("#staate").append('<option>لطفا شهر را انتخاب کنید</option>');
                            $.staate(res, function (key, value) {
                                $("#staate").append('<option value="' + value.zone_id + '">' + value.name + '</option>');
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
                url: "{{ route('admin.customers.edit') }}",
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
                            window.history.back();
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
            var div = $("table tr:last");
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
<script>

    $(function () {


        $("#delete_certificate_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل شناسنامه؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileCertificate')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_certificate_documents_company').hide();
                            $('#delete_certificate_documents_company').hide();
                            $('#view_certificate_documents_company').hide();
                            document.getElementById("uncheck_certificate_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل شناسنامه با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_cart_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل کارت ملی؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileCart')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_cart_documents_company').hide();
                            $('#delete_cart_documents_company').hide();
                            $('#view_cart_documents_company').hide();
                            document.getElementById("uncheck_cart_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل کارت ملی با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_activity_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل محل فعالیت؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileActivity')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_activity_documents_company').hide();
                            $('#delete_activity_documents_company').hide();
                            $('#view_activity_documents_company').hide();
                            document.getElementById("uncheck_activity_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل محل فعالیت با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_store_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل مالکیت فروشگاه؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileStore')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_store_documents_company').hide();
                            $('#delete_store_documents_company').hide();
                            $('#view_store_documents_company').hide();
                            document.getElementById("uncheck_store_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل مالکیت فروشگاه با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_ownership_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل مالکیت انبار؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileOwnership')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_ownership_documents_company').hide();
                            $('#delete_ownership_documents_company').hide();
                            $('#view_ownership_documents_company').hide();
                            document.getElementById("uncheck_ownership_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل مالکیت انبار با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_established_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل تاسیس و بهره برداری؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileEstablished')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_established_documents_company').hide();
                            $('#delete_established_documents_company').hide();
                            $('#view_established_documents_company').hide();
                            document.getElementById("uncheck_established_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل تاسیس و بهره برداری با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_sstore_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل عکس فروشگاه؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileSstore')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_sstore_documents_company').hide();
                            $('#delete_sstore_documents_company').hide();
                            $('#view_sstore_documents_company').hide();
                            document.getElementById("uncheck_sstore_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل عکس فروشگاه با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_pstore_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل عکس انبار؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.filePstore')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_pstore_documents_company').hide();
                            $('#delete_pstore_documents_company').hide();
                            $('#view_pstore_documents_company').hide();
                            document.getElementById("uncheck_pstore_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل عکس انبار با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });
        $("#delete_final_documents_company").bind("click", function () {
            var id = $('#id_customer').val();
            Swal.fire({
                title: 'حذف فایل نتیجه استعلام حسابهای بانکی؟',
                text: "فایل های حذف شده قابل بازیابی نیستند!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.customers.delete.fileFinal')}}" + '/' + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function (data) {
                            $('#check_final_documents_company').hide();
                            $('#delete_final_documents_company').hide();
                            $('#view_final_documents_company').hide();
                            document.getElementById("uncheck_final_documents_company").style.visibility = "visible";
                            Swal.fire({
                                title: 'موفق',
                                text: 'فایل نتیجه استعلام حسابهای بانکی با موفقیت از پرونده ای مشتری حذف شد',
                                icon: 'success',
                                confirmButtonText: 'تایید'
                            })
                        }
                    });
                }
            })
        });


    });

    $('#customer').addClass('active');

</script>
