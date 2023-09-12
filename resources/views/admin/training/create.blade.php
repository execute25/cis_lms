@section('content')
    @parent

    <!-- datapicker CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/datapicker/datepicker3.css">


    <form id="form" action="/admin/training" method="POST" data-validate="parsley">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Name *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-equalizer"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text" name="name" required="" class="form-control" placeholder="Name input">
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
                    <textarea class="form-control" required="" placeholder="Description input" name="description" id=""
                              cols="30" rows="3"></textarea>
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
                    <input type="text" name="bunny_id" class="form-control" placeholder="Bunny Video Id input">
                </div>
            </div>
        </div>


        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Start date *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-calendar"></i>
                </div>
                <div class="nk-int-st">
                    <div class="form-group nk-datapk-ctm form-elet-mg date_normal" id="data_1">
                        <div class="input-group date nk-int-st">
                            <span class="input-group-addon"></span>
                            <input type="text" class="form-control" name="start_at" required="" readonly value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Start Time *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-time"></i>
                </div>
                <div class="nk-int-st">

                    <select class="form-control time_select" required="" name="start_at_time"
                            style="width: 100%; margin-bottom: 10px;">
                        <?php

                        $time = new DateTime('00:00');
                        $interval = new DateInterval('PT30M');

                        ?>

                        <option value="">Select Time</option>
                        @for ($i = 0; $i < 24; $i++)
                            @for ($j = 0; $j < 60; $j+=30)
                                <?php
                                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $minutes = str_pad($j, 2, '0', STR_PAD_LEFT);
                                $time = $hour . ":" . $minutes;
                                ?>

                                <option
                                    value="{{ $time }}">{{$time}}
                                </option>
                            @endfor
                        @endfor
                    </select>
                </div>
            </div>
        </div>


        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>End date *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-calendar"></i>
                </div>
                <div class="nk-int-st">
                    <div class="form-group nk-datapk-ctm form-elet-mg date_normal" id="data_1">
                        <div class="input-group date nk-int-st">
                            <span class="input-group-addon"></span>
                            <input type="text" class="form-control" name="end_at" readonly required="" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>End Time *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-time"></i>
                </div>
                <div class="nk-int-st">

                    <select class="form-control time_select" required="" name="end_at_time"
                            style="width: 100%; margin-bottom: 10px;">
                        <?php

                        $time = new DateTime('00:00');
                        $interval = new DateInterval('PT30M');

                        ?>

                        <option value="">Select Time</option>
                        @for ($i = 0; $i < 24; $i++)
                            @for ($j = 0; $j < 60; $j+=30)
                                <?php
                                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $minutes = str_pad($j, 2, '0', STR_PAD_LEFT);
                                $time = $hour . ":" . $minutes;
                                ?>

                                <option
                                      value="{{ $time }}">{{$time}}
                                </option>
                            @endfor
                        @endfor
                    </select>
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
                            <input id="ts1" value="1" checked type="checkbox" name="is_use_zoom" hidden="hidden">
                            <label for="ts1" class="ts-helper"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <input type="hidden" class="form-control" name="category_id" required="" value="{{$category_id}}">
        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Create</button>

    </form>

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
                        window.location = '/admin/training?category_id={{$category_id}}';
                    });

                },
                error: function (error) {

                    swal({
                        title: "",
                        text: "Was error while training register",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                    // $.alert('Was error while training register: ' + error['responseText']);
                }
            });

        });

    </script>
@stop
