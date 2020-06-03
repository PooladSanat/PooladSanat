<div class="modal fade" id="ajaxModel" aria-hidden="true">
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

                            <form autocomplete="off" id="productForme" name="productForme" class="form-horizontal">
                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="product_id" id="product_id">
                                @csrf
                                <div class="row">

                                    <div class="col-md-12">

                                        <label>شماره حواله
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="number" name="number" class="form-control"
                                               placeholder="لطفا شماره حواله را وارد کنید"
                                               required>

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


<div class="modal fade" id="ajaxModelExit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-body">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption" id="captioon">
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

                            <form autocomplete="off" id="productFormm" name="productFormm" class="form-horizontal">
                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="prod" id="prod">
                                <input type="hidden" name="namee" id="namee">
                                <input type="hidden" name="colorr" id="colorr">
                                <input type="hidden" name="id_exit" id="id_exit">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">

                                        <label>نام محصول
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-control"
                                               readonly
                                               required>

                                    </div>

                                    <div class="col-md-12">

                                        <label>رنگ محصول
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="color" name="color" class="form-control"
                                               readonly
                                               required>

                                    </div>

                                    <div class="col-md-12">

                                        <label>تعداد درخواستی
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="n" name="n" class="form-control"
                                               readonly
                                               required>

                                    </div>


                                    <div class="col-md-12">

                                        <label>تعداد خارج شده
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="numberr" name="numberr" class="form-control"
                                               placeholder="لطفا تعداد خارج شده را وارد کنید"
                                               required>

                                    </div>


                                </div>
                                <br/>
                                <hr/>
                                <div class="modal-footer">
                                    <div class="text-left">
                                        <button style="width: 130px" type="submit" class="btn btn-success" id="exitBtn"
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


<div class="modal fade" id="ajaxModelfac" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-body">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption" id="captioson">
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

                            <form autocomplete="off" id="productFormdf" name="productFormdf" class="form-horizontal">
                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="produc" id="produc">
                                @csrf
                                <div class="row">

                                    <div class="col-md-12">

                                        <label>شماره فاکتور
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="number" name="number" class="form-control"
                                               placeholder="لطفا شماره فاکتور را وارد کنید"
                                               required>

                                    </div>

                                </div>
                                <br/>
                                <hr/>
                                <div class="modal-footer">
                                    <div class="text-left">
                                        <button style="width: 130px" type="submit" class="btn btn-success" id="okBtn"
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


<div class="modal fade" id="changedateModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-body">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption" id="caption">
                        تغیر زمان بارگیری
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

                            <form autocomplete="off" id="changedateForm" name="changedateForm" class="form-horizontal">
                                <input type="hidden" name="id_d" id="id_d">
                                @csrf
                                <div class="row">

                                    <div class="col-md-12">

                                        <label>تاریخ
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="date" name="date" class="form-control example1"
                                               required>

                                    </div>

                                </div>
                                <br/>
                                <hr/>
                                <div class="modal-footer">
                                    <div class="text-left">
                                        <button style="width: 130px" type="submit" class="btn btn-success"
                                                id="saveBtndate"
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


<div class="modal fade" id="cancelModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-body">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption" id="caption">
                        انصراف فروش
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

                            <form autocomplete="off" id="cancelForm" name="cancelForm"
                                  class="form-horizontal">
                                <input type="hidden" name="id_p" id="id_p">
                                @csrf
                                <div class="row">

                                    <div class="hello" id='TextBoxesGroup'>
                                        <div id="TextBoxDiv1">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>دلیل انصراف
                                            <span
                                                style="color: red"
                                                class="required-mark">*</span>
                                        </label>
                                        <input type="text" name="reason" id="reason" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label>توضیحات
                                        </label>
                                        <textarea name="description" id="description" rows="5"
                                                  class="form-control" placeholder="لطفا توضیحات را وارد کنید">
                                                 </textarea>
                                    </div>
                                </div>
                                <br/>
                                <hr/>
                                <div class="modal-footer">
                                    <div class="text-left">
                                        <button style="width: 130px" type="submit" class="btn btn-success"
                                                id="saveBtncancel"
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
