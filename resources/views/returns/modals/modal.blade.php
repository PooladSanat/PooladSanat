<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog col-md-12">
        <div class="modal-content">
            <div class="modal-body col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
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

                                <form autocomplete="off" id="productForm" name="productForm"
                                      class="form-horizontal">
                                    @csrf
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <label>محصول:</label>
                                                    <select name="product_id" id="product_id"
                                                            class="form-control">
                                                        @foreach($products as $product)
                                                            <option
                                                                value="{{$product->id}}">{{$product->label}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>رنگ:</label>
                                                    <select name="color_id" id="color_id"
                                                            class="form-control">
                                                        @foreach($colors as $color)
                                                            <option
                                                                value="{{$color->id}}">{{$color->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>تاریخ:</label>
                                                    <input type="text" name="date" id="date" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label>شماره فاکتور:</label>
                                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label>تعداد سالم:</label>
                                                    <input type="text" name="healthynumber" id="healthynumber"
                                                           class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label>تعداد ضایعات:</label>
                                                    <input type="text" name="wastagenumber" id="wastagenumber"
                                                           class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>دلیل مرجوعی:</label>
                                                    <textarea name="description" id="description"
                                                              placeholder="لطفا دلیل مرجوعی را وارد کنید" class="form-control"
                                                              rows="2" cols="50">
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
