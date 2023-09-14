@section('content')
    @parent
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">

                    <form id="form" action="/admin/setting/{{{ $setting->id }}}" method="POST" data-validate="parsley">


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Zoom Account ID</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="zoom_account_id"
                                           value="{{$setting->zoom_account_id}}"
                                           required=""
                                           class="form-control"
                                           placeholder="Name input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Zoom Client ID</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="zoom_client_id"
                                           value="{{$setting->zoom_client_id}}"
                                           class="form-control"
                                           placeholder="Korean Name input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Zoom Client Secret</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="zoom_client_secret"
                                           value="{{$setting->zoom_client_secret}}"
                                           class="form-control"
                                           placeholder="Korean Name input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Zoom Redirect URL</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="zoom_redirect_url"
                                           value="{{$setting->zoom_redirect_url}}"
                                           class="form-control"
                                           placeholder="Korean Name input">
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>

                        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Setting Update
                        </button>

                    </form>


                </div>
            </div>
        </div>
    </div>

@stop

@section('app-js')
    @parent
    <script src="/src/js/parsley/parsley.new.js"></script>
    <script src="/src/js/load-image/load-image.min.js"></script>
@stop

@section('inline-js')
    @parent
    <script type="text/javascript">
        $(function () {
            var $form = $('#form');
            $form.ajaxForm({
                data: {
                    _method: 'PUT'
                },
                beforeSubmit: function (arr, $form) {

                    if (!$form.parsley('isValid'))
                        return false;
                },
                success: function () {
                    swal({
                        title: "",
                        text: "Settings was successfully update",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.history.back();
                    });

                },
                error: function (error) {
                    swal({
                        title: "",
                        text: "Was error while setting update",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                }
            });
        });

    </script>
@stop
