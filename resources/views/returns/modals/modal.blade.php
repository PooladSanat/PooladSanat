<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog col-md-12">
        <div class="modal-content">
            <div class="modal-body col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            تعریف مرجوعی جدید
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

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">


                                                <div class="col-md-4">
                                                    <label>مشتری:</label>
                                                    <select name="customer_id" id="customer_id"
                                                            class="form-control">
                                                        <option>انتخاب کنید...</option>
                                                        @foreach($customers as $customer)
                                                            <option
                                                                value="{{$customer->id}}">{{$customer->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>هزینه حمل:</label>
                                                    <select name="Carry" id="Carry"
                                                            class="form-control">
                                                        <option value="پولاد پویش">پولاد پویش</option>
                                                        <option value="مشتری">مشتری</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>تاریخ مرجوع:</label>
                                                    <input type="text" name="date" id="date" class="form-control">
                                                </div>


                                                <div class="col-md-12">
                                                    <label> دلایل مشتری جهت عودت محصول:</label>
                                                    <textarea name="description_m" id="description_m"
                                                              placeholder="لطفا  دلایل مشتری جهت عودت محصول را وارد کنید"
                                                              class="form-control"
                                                              rows="2" cols="50">
                                                    </textarea>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>دلایل واحد فروش جهت پذیرش:</label>
                                                    <textarea name="description_f" id="description_f"
                                                              placeholder="لطفا دلایل واحد فروش جهت پذیرش خود را وارد کنید"
                                                              class="form-control"
                                                              rows="2" cols="50">
                                                    </textarea>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>توضیحات:</label>
                                                    <textarea name="description" id="description"
                                                              placeholder="لطفا توضیحات خود را وارد کنید"
                                                              class="form-control"
                                                              rows="2" cols="50">
                                                    </textarea>
                                                </div>


                                            </div>

                                            <br/>
                                            <div class="table table-responsive">
                                                <table
                                                    class="table table-responsive table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <td>فاکتور</td>
                                                        <td>محصول</td>
                                                        <td>رنگ</td>
                                                        <td>تعداد کل</td>
                                                        <td>تعداد مرجوع شده</td>
                                                        <td>دلیل مشتری</td>
                                                        <td>عملیات</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody
                                                        id="TextBoxContainerbank">

                                                    <tr>
                                                        <td id="invoicee"></td>
                                                        <td id="productt"></td>
                                                        <td id="colorr"></td>
                                                        <td id="totalnumberr"></td>
                                                        <td id="numberr"></td>
                                                        <td id="reasonss"></td>
                                                        <td id="actiont"></td>
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
                                            <button style="width: 130px" type="button" class="btn btn-success"
                                                    id="saveBtn"
                                                    data-dismiss="modal">
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
</div>

<div class="modal fade" id="qcmodel" aria-hidden="true">
    <div class="modal-dialog col-md-12">
        <div class="modal-content">
            <div class="modal-body col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            نظر QC
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

                                <form autocomplete="off" id="productFor" name="productFor">
                                    <input type="hidden" id="id_id" name="id_id">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">


                                                <div class="col-md-4">
                                                    <label>مشتری:</label>
                                                    <select readonly name="customer_id" id="customerr_id"
                                                            class="form-control">
                                                        <option>انتخاب کنید...</option>
                                                        @foreach($customers as $customer)
                                                            <option
                                                                value="{{$customer->id}}">{{$customer->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label>توضیحات:</label>
                                                    <textarea readonly name="description" id="descriptiuon"
                                                              placeholder="لطفا توضیحات خود را وارد کنید"
                                                              class="form-control"
                                                              rows="1" cols="50">
                                                    </textarea>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>وضعیت:</label>
                                                    <select name="statusq" id="statusq" class="form-control">
                                                        <option value="1">تایید</option>
                                                        <option value="2">عدم تایید</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-10">
                                                    <label>توضیحات:</label>
                                                    <textarea name="descriptionq" id="descriptionq"
                                                              placeholder="لطفا توضیحات خود را وارد کنید"
                                                              class="form-control"
                                                              rows="5" cols="20">
                                                    </textarea>
                                                </div>

                                            </div>

                                            <br/>
                                            <div class="table table-responsive">
                                                <table
                                                    class="table table-responsive table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <td>فاکتور</td>
                                                        <td>محصول</td>
                                                        <td>رنگ</td>
                                                        <td>تعداد کل مرجوعی</td>
                                                        <td>تعداد معیوب</td>
                                                        <td>تعداد سالم</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody
                                                        id="TextBoxContainer">

                                                    <tr>
                                                        <td id="invoiceee"></td>
                                                        <td id="producttt"></td>
                                                        <td id="colorrr"></td>
                                                        <td id="numberrr"></td>
                                                        <td id="mm"></td>
                                                        <td id="ss"></td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th colspan="1">
                                                            <button id="btnAddbankk"
                                                                    type="button"
                                                                    onclick="addInput11()"
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
                                            <button style="width: 130px" type="button" class="btn btn-success"
                                                    id="savea"
                                                    data-dismiss="modal">
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
</div>


<div class="modal fade" id="user_" aria-hidden="true">
    <div class="modal-dialog col-md-12">
        <div class="modal-content">
            <div class="modal-body col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            نظر مدیر فروش
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

                                <form autocomplete="off" id="userform" name="userform">
                                    <input type="hidden" id="id_" name="id_">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>وضعیت:</label>
                                                    <select name="statusu" id="statusu" class="form-control">
                                                        <option value="1">تایید</option>
                                                        <option value="2">عدم تایید</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-10">
                                                    <label>توضیحات:</label>
                                                    <textarea name="descriptionu" id="descriptionu"
                                                              placeholder="لطفا توضیحات خود را وارد کنید"
                                                              class="form-control"
                                                              rows="5" cols="20">
                                                    </textarea>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    <br/>
                                    <hr/>
                                    <div class="modal-footer">
                                        <div class="text-left">
                                            <button style="width: 130px" type="button" class="btn btn-success"
                                                    id="saveu"
                                                    data-dismiss="modal">
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
</div>


<div class="modal fade" id="databasemodel" aria-hidden="true">
    <div class="modal-dialog col-md-12">
        <div class="modal-content">
            <div class="modal-body col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            نظر انبار
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

                                <form autocomplete="off" id="databaseFor" name="databaseFor">
                                    <input type="hidden" id="id_d" name="id_d">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>وضعیت:</label>
                                                    <select name="statusd" id="statusd" class="form-control">
                                                        <option value="1">تایید</option>
                                                        <option value="2">عدم تایید</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-10">
                                                    <label>توضیحات:</label>
                                                    <textarea name="descriptiond" id="descriptiond"
                                                              placeholder="لطفا توضیحات خود را وارد کنید"
                                                              class="form-control"
                                                              rows="5" cols="20">
                                                    </textarea>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="table table-responsive">
                                                <table
                                                    class="table table-responsive table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <td>فاکتور</td>
                                                        <td>محصول</td>
                                                        <td>رنگ</td>
                                                        <td>تعداد کل مرجوعی</td>
                                                        <td>تعداد معیوب</td>
                                                        <td>تعداد سالم</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody
                                                        id="TextBoxContainer">

                                                    <tr>
                                                        <td id="invoiceeee"></td>
                                                        <td id="productttt"></td>
                                                        <td id="colorrrr"></td>
                                                        <td id="numberrrr"></td>
                                                        <td id="mmm"></td>
                                                        <td id="sss"></td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th colspan="1">
                                                            <button id="btnAddbankk"
                                                                    type="button"
                                                                    onclick="addInput12()"
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
                                            <button style="width: 130px" type="button" class="btn btn-success"
                                                    id="saved"
                                                    data-dismiss="modal">
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
</div>
