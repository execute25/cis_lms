@section('content')
    @parent

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">

                    <!-- datapicker CSS
                        ============================================ -->
                    <link rel="stylesheet" href="/src/css/datapicker/datepicker3.css">


                    <form id="form" action="/admin/training_category" method="POST" data-validate="parsley">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Title *</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-equalizer"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text" name="title" required="" class="form-control"
                                           placeholder="Name input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Description *</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-equalizer"></i>
                                </div>
                                <div class="nk-int-st">
                    <textarea class="form-control" placeholder="Description input" name="description" id=""
                              cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Order</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-sort-by-order"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="number" name="order" class="form-control" placeholder="Order input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mg-b-15">
                            <div class="nk-int-mk">
                                <h5>Zoom Account</h5>
                            </div>
                            <div class="chosen-select-act fm-cmp-mg">
                                <select class="chosen" name="zoom_host_email" required="" style="width:200px;"/>
                                <option value=""></option>
                                @foreach($zoom_account_settings as $zoom_account_setting)
                                    <option value="{{{$zoom_account_setting->host_email}}}">{{{$zoom_account_setting->host_email}}}</option>
                                    @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Is hidden</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                </div>
                                <div class="nk-int-st">
                                    <div class="toggle-select-act fm-cmp-mg">
                                        <div class="nk-toggle-switch">
                                            <input type="hidden" name="is_hidden" value="0">
                                            <input id="ts1" value="1" name="is_hidden" type="checkbox">
                                            <label for="ts1" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Is Special</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                </div>
                                <div class="nk-int-st">
                                    <div class="toggle-select-act fm-cmp-mg">
                                        <div class="nk-toggle-switch">
                                            <input type="hidden" name="is_special" value="0">
                                            <input id="ts1" value="1" name="is_special" type="checkbox">
                                            <label for="ts1" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mg-b-15">
                            <div class="nk-int-mk">
                                <h5>Users only from this group</h5>
                            </div>
                            <div class="chosen-select-act fm-cmp-mg">
                                <select class="chosen" multiple="" name="include_groups[]" style="width:200px;"/>
                                <option value=""></option>
                                @foreach($member_groups as $group)
                                    <option value="{{{$group->id}}}">{{{$group->name}}}</option>
                                    @endforeach
                                    </select>
                            </div>
                        </div>


                        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Create</button>

                    </form>

                </div>
            </div>
        </div>
    </div>


@stop

@section('app-js')
    @parent
    <!-- datapicker JS
		============================================ -->
    <script src="/src/js/datapicker/bootstrap-datepicker.js"></script>
    <script src="/src/js/datapicker/datepicker-active.js"></script>


    <script src="/src/js/parsley/parsley.new.js"></script>
    <script src="/src/js/load-image/load-image.min.js"></script>
@stop

@section('inline-js')
    @parent
    <script type="text/javascript">
        $(function () {
            var $form = $('#form');
            $form.parsley();

            $form.ajaxForm({
                beforeSubmit: function (arr, $form) {
                    if (!$form.parsley('isValid'))
                        return false;
                },
                success: function () {
                    swal({
                        title: "",
                        text: "Region was successfully created",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.location = '/admin/training_category';
                    });

                },
                error: function (error) {

                    swal({
                        title: "",
                        text: "Was error while training_category register",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                    // $.alert('Was error while training_category register: ' + error['responseText']);
                }
            });

        });

    </script>
@stop
