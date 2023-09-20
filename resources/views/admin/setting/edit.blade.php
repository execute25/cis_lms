@section('content')
    @parent
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">

                    <form id="form" action="/admin/setting/{{{ $setting->id }}}" method="POST" data-validate="parsley">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Common Password</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="common_password"
                                           value="{{$setting->common_password}}"
                                           class="form-control"
                                           placeholder="Korean Name input">
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>


                        <div class="row mg-t-30">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="breadcomb-list">

                        <span class="btn btn-success btn-block add_zoom_setting"><i
                                class="glyphicon glyphicon-plus"></i>  Add Zoom Setting</span>

                                    @include('web.templates.zoom_setting', ["index" => 0])
                                    @include('web.templates.zoom_setting', ["index" => 1])
                                    @include('web.templates.zoom_setting', ["index" => 2])
                                    @include('web.templates.zoom_setting', ["index" => 3])
                                    @include('web.templates.zoom_setting', ["index" => 4])
                                    @include('web.templates.zoom_setting', ["index" => 5])


                                </div>

                            </div>
                        </div>



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


        $('body').on('click', ".zoom_setting_bloсk_remove", function () {
            console.log(1)
            $(this).parents(".zoom_setting_bloсk").css("display", "none")
            $(this).parents(".zoom_setting_bloсk").find(".is_delete").val("1")
        });


        $('body').on('click', ".add_zoom_setting", function () {
            var index = $(".zoom_setting_bloсk:visible").length;
            console.log(index)
            $(".zoom_setting_bloсk_" + index).css("display", "block")
            $(".zoom_setting_bloсk_" + index).find(".is_delete").val("0")

        });

    </script>
@stop
