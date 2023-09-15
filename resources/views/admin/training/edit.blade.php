\@section('content')
    @parent

    <!-- Tempus Dominus Styles -->
    <link rel="stylesheet" href="/src/css/datapicker/bootstrap-datetimepicker.css">

    <form id="form" action="/admin/training/{{{ $training->id }}}" method="POST">

        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="nk-int-mk  mg-t-10">
                                    <h5>Name *</h5>
                                </div>
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="glyphicon glyphicon-equalizer"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" name="name" value="{{$training->name}}" required=""
                                               class="form-control"
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
                    <textarea class="form-control" required=""
                              placeholder="Description input" name="description" id="" cols="30"
                              rows="3">{{$training->description}}</textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="nk-int-mk  mg-t-10">
                                    <h5>Bunny Video Id</h5>
                                </div>
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="glyphicon glyphicon-equalizer"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" name="bunny_id" value="{{$training->bunny_id}}"
                                               class="form-control"
                                               placeholder="Bunny Video Id input">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="nk-int-mk  mg-t-10">
                                    <h5>Is use Zoom meeting</h5>
                                </div>
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="glyphicon glyphicon-lamp"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <div class="toggle-select-act fm-cmp-mg">
                                            <div class="nk-toggle-switch">
                                                <input id="ts1" value="1" type="checkbox"
                                                       {{{ $training->is_use_zoom == 1 ? "checked" : '' }}} name="is_use_zoom"
                                                       hidden="hidden">
                                                <label for="ts1" class="ts-helper"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


            <div class="row mg-t-30">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">

                        <span class="btn btn-success btn-block add_training_live_time"><i
                                class="glyphicon glyphicon-plus"></i>  Add Training Live Time</span>

                        @include('web.templates.training_live_time', ["index" => 0])
                        @include('web.templates.training_live_time', ["index" => 1])
                        @include('web.templates.training_live_time', ["index" => 2])
                        @include('web.templates.training_live_time', ["index" => 3])
                        @include('web.templates.training_live_time', ["index" => 4])


                    </div>

                </div>
            </div>

            <div class="row mg-t-30">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">

                        <span class="btn btn-success btn-block add_training_repeat_time"><i
                                class="glyphicon glyphicon-plus"></i>  Add Training Repeat Time</span>

                        @include('web.templates.training_repeat_time', ["index" => 0])
                        @include('web.templates.training_repeat_time', ["index" => 1])
                        @include('web.templates.training_repeat_time', ["index" => 2])
                        @include('web.templates.training_repeat_time', ["index" => 3])
                        @include('web.templates.training_repeat_time', ["index" => 4])
                        @include('web.templates.training_repeat_time', ["index" => 5])
                        @include('web.templates.training_repeat_time', ["index" => 6])
                        @include('web.templates.training_repeat_time', ["index" => 7])
                        @include('web.templates.training_repeat_time', ["index" => 8])
                        @include('web.templates.training_repeat_time', ["index" => 9])
                        @include('web.templates.training_repeat_time', ["index" => 10])
                        @include('web.templates.training_repeat_time', ["index" => 11])


                    </div>

                </div>
            </div>

            <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Training Update
            </button>
        </div>
    </form>

@stop

@section('app-js')
    @parent
    <script src="/src/js/datapicker/bootstrap-datepicker.js"></script>
    <script src="/src/js/datapicker/datepicker-active.js"></script>


    <script src="/src/js/parsley/parsley.new.js"></script>
    <script src="/src/js/load-image/load-image.min.js"></script>
    <script src="/src/js/moment.js"></script>

    <script src="/src/js/datapicker/bootstrap-datetimepicker.min.js"></script>

@stop

@section('inline-js')
    @parent
    <script type="text/javascript">
        $(function () {
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                // inline: true,
                sideBySide: true
            });


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
                        text: "Training was successfully update",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.location = '/admin/training?category_id={{$training->category_id}}';
                    });

                },
                error: function (error) {
                    swal({
                        title: "",
                        text: "Was error while training update",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                }
            });
        });

    </script>

    <script>
        $(function () {
            $('body').on('click', ".training_live_time_bloсk_remove", function () {
                console.log(1)
                $(this).parents(".training_live_time_bloсk").css("display", "none")
                $(this).parents(".training_live_time_bloсk").find(".is_delete").val("1")
            });


            $('body').on('click', ".add_training_live_time", function () {
                var index = $(".training_live_time_bloсk:visible").length;
                console.log(index)
                $(".training_live_time_bloсk_" + index).css("display", "block")
                $(".training_live_time_bloсk_" + index).find(".is_delete").val("0")

            });


            $('body').on('click', ".training_repeat_time_bloсk_remove", function () {
                console.log(1)
                $(this).parents(".training_repeat_time_bloсk").css("display", "none")
                $(this).parents(".training_repeat_time_bloсk").find(".is_delete").val("1")
            });


            $('body').on('click', ".add_training_repeat_time", function () {
                var index = $(".training_repeat_time_bloсk:visible").length;
                console.log(index)
                $(".training_repeat_time_bloсk_" + index).css("display", "block")
                $(".training_repeat_time_bloсk_" + index).find(".is_delete").val("0")

            });
        });
    </script>
@stop
