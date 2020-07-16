<div class="modal fade" id="ffsf" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-body">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption" id="caption">
                        </div>
                        <div class="caption pull-left">
                            <a data-dismiss="modal">
                                <i style="color: white" class="pull-left fa fa-close"></i>
                            </a>
                        </div>

                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group">

                                <form autocomplete="off" id="productForm" name="productForm" class="form-horizontal">
                                    <input type="hidden" name="id" id="id">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">

                                                <label>نام صاحب حساب
                                                    <span
                                                        style="color: red"
                                                        class="required-mark">*</span>
                                                </label>
                                                <input type="text" id="name" name="name" class="form-control"
                                                       placeholder="لطفا نام صاحب حساب را وارد کنید"
                                                       required>

                                        </div>

                                        <div class="col-md-12">

                                                <label>نام بانک
                                                    <span
                                                        style="color: red"
                                                        class="required-mark">*</span>
                                                </label>
                                                <input type="text" id="NameBank" name="NameBank" class="form-control"
                                                       placeholder="لطفا نام بانک را وارد کنید"
                                                       required>

                                        </div>

                                        <div class="col-md-12">

                                                <label>شماره کارت
                                                </label>
                                                <input type="text" id="CardNumber" name="CardNumber"
                                                       class="form-control"
                                                       placeholder="لطفا شماره کارت را وارد کنید"
                                                       required>

                                        </div>
                                        <div class="col-md-12">

                                                <label>شماره حساب
                                                </label>
                                                <input type="text" id="AccountNumber" name="AccountNumber"
                                                       class="form-control"
                                                       placeholder="لطفا شمار حساب را وارد کنید"
                                                       required>

                                        </div>

                                        <div class="col-md-12">

                                                <label>شماره شبا
                                                </label>
                                                <input type="text" id="ShabaNumber" name="ShabaNumber"
                                                       class="form-control"
                                                       placeholder="لطفا شماره شبا را وارد کنید"
                                                       required>

                                        </div>

                                        <div class="col-md-12">

                                                <label>وضعیت حساب
                                                </label>
                                                <select class="form-control" name="status" id="status">
                                                    <option value="1">فعال</option>
                                                    <option value="2"> غیر فعال</option>
                                                </select>

                                        </div>


                                    </div>
                                    <br/>
                                    <hr/>
                                    <div class="modal-footer">
                                        <div class="text-left">

                                            <button style="width: 130px" type="submit" class="btn btn-success"
                                                    id="saveBtn" value="ثبت">
                                                ثبت
                                            </button>

                                            <button style="width: 130px" type="button" class="btn btn-danger"
                                                    data-dismiss="modal">
                                                انصراف
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>


<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog col-md-12">
        <div class="modal-body col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption" id="caption">
                        مشخصات پرداخت مشتری
                    </div>
                    <div class="caption pull-left">
                        <a data-dismiss="modal">
                            <i style="color: white" class="pull-left fa fa-close"></i>
                        </a>
                    </div>

                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">

                            <form autocomplete="off" id="productForm" name="productForm">
                                <input type="hidden" name="customer_ider" id="customer_ider">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>نام مشتری</label>
                                        <input readonly type="text" name="cname" id="cname" class="form-control">

                                    </div>
                                    <div class="col-md-3">
                                        <label>موجودی مشتری</label>
                                        <input readonly type="text" name="cprice" id="cprice" class="form-control">

                                    </div>
                                    <div class="col-md-3">
                                        <label>اخرین بروزرسانی حساب مشتری</label>
                                        <input readonly type="text" name="cupdate" id="cupdate" class="form-control">

                                    </div>

                                    <div class="col-md-3">
                                        <label>مبلغ صورت حساب</label>
                                        <input readonly type="text" name="pricesum" id="pricesum" class="form-control">

                                    </div>

                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <div class="col-md-12">


                                        <table class="table table-striped table-bordered gfgf" id="gfgf">
                                            <thead style="background-color: #e8ecff">
                                            <tr>
                                                <th>ردیف</th>
                                                <td>شماره فاکتور</td>
                                                <td>نام محصول</td>
                                                <td>رنگ</td>
                                                <td>تعداد(عدد)</td>
                                                <td>مبلغ(ریال)</td>
                                                <td>تاریخ</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="table table-responsive">
                                            <table
                                                class="table table-responsive table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <td>نوع پرداخت</td>
                                                    <td>شماره چک</td>
                                                    <td>نام بانک</td>
                                                    <td>نام صادر کننده</td>
                                                    <td>مبلغ(ریال)</td>
                                                    <td>تاریخ</td>
                                                    <td>عملیات</td>
                                                </tr>
                                                </thead>
                                                <tbody
                                                    id="TextBoxContainerbank">

                                                <tr>
                                                    <td id="typee"></td>
                                                    <td id="shanasee"></td>
                                                    <td id="namee"></td>
                                                    <td id="user_namee"></td>
                                                    <td id="pricee"></td>
                                                    <td id="datee"></td>
                                                    <td id="actiontt"></td>
                                                </tr>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="1">
                                                        <button id="btnAddbank"
                                                                type="button"
                                                                onclick="addInput10()"
                                                                class="btn btn-primary"
                                                                data-toggle="tooltip">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                                </tfoot>

                                            </table>

                                        </div>
                                    </div>

                                </div>
                                <br/>
                                <hr/>
                                <div class="modal-footer">
                                    <div class="text-left">
                                        <button style="width: 130px" type="submit" class="btn btn-success" id="saveBtn"
                                                value="ثبت">
                                            ثبت
                                        </button>

                                        <button style="width: 130px" type="button" class="btn btn-danger"
                                                data-dismiss="modal">
                                            انصراف
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

