@section('content')
    @parent

    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-ctn">
                                        <h2>{{__("Upcoming events")}}</h2>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="training_list_block">
                    @foreach($trainings as $training)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="lection_article {{$training->is_special == 1 ? "colored_background" : ''}}">
                                <div class="contact-list">
                                    <div class="contact-ctn">
                                        <div class="contact-ad-hd">
                                            <h2>{{$training->name}}</h2>
                                            <p class="ctn-ads">{{$training->category_title}}</p>
                                            <p class="ctn-ads">
                                                  <span class="btn btn-block nk-teal show_time_detail"
                                                        data-id="{{$training->id}}">
                                                <i class="glyphicon glyphicon-time"></i>
                                                {{__("Date")}}
                                                :
                                              {{ normalizeDate(dateTolocal($training->start_at))  }}</span>
                                            </p>
                                        </div>
                                        <p>{{$training->description}}</p>

                                    </div>
                                    <div class="training_btn_block" data-id="{{$training->id}}">

                                        @if(countTrainingMaterials($training) == 1)
                                            <a href="{{getFirstMaterial($training)}}" class="btn btn-block nk-indigo"
                                               target="_blank"> <i
                                                    class="glyphicon glyphicon-book"></i> {{__("Training material")}}
                                            </a>
                                        @elseif(countTrainingMaterials($training) > 1)
                                            <a href="{{route("training.material_list", ['id' => $training->id])}}"
                                               class="btn btn-block nk-indigo"><i
                                                    class="glyphicon glyphicon-book"></i> {{__("Training material")}}
                                            </a>
                                        @endif


                                        @if( isLessonInProgress($training) && $training->training_type == \App\Models\TrainingModel::TRAINING_TYPE_ZOOM )
                                            <span
                                                class="btn btn-success btn-block connect_to_training_zoom"
                                                data-loading-text="{{__("Loading")}}..."
                                            ><i class="glyphicon glyphicon-leaf"></i> {{__("Connect to a lecture via Zoom")}}</span>

                                        @elseif(isLessonNotStartYet($training) && $training->training_type == \App\Models\TrainingModel::TRAINING_TYPE_ZOOM)
                                            <span
                                                class="remind_to_start_text">{{getRemindTime($training->start_at)}}</span>
                                            <span
                                                class="btn btn-default btn-block connect_to_training_zoom">{{__("The online lecture in ZOOM has not started yet")}}</span>
                                        @elseif(isLessonInProgress($training) && $training->training_type == \App\Models\TrainingModel::TRAINING_TYPE_REPEAT_LECTION)
                                            <a href="{{ route('training.show_video', ['id' => $training->id]) }}"
                                               class="btn btn-warning btn-block">{{__("Go to lecture replay")}}</a>

                                        @elseif(isLessonNotStartYet($training) && $training->training_type == \App\Models\TrainingModel::TRAINING_TYPE_REPEAT_LECTION)
                                            <span
                                                class="remind_to_start_text">{{getRemindTime($training->start_at, $training->training_type)}}</span>
                                            <span
                                                class="btn btn-default btn-block"><i
                                                    class="glyphicon glyphicon-play-circle"></i> {{__("Viewing the replay of this lecture is not yet open")}}</span>

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal  fade " id="time_list_modal" role="dialog" style="">
        <div class="modal-dialog modal-large">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">

                    <div class="time_list_block">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect"
                            data-dismiss="modal">{{__("Close")}}</button>
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
            $('body').on('click', ".connect_to_training_zoom", function () {
                var training_id = $(this).parents(".training_btn_block").attr("data-id");
                var $btn = $(this);
                $btn.button('loading');

                $.ajax({
                    url: '/web/training/' + training_id + '/get_zoom_join_link',
                    data: {},
                    method: "GET",
                    success: function (data) {
                        window.open(data.join_zoom_link, "_blank");
                        $btn.button('reset');
                    },
                    error: function (error) {

                        swal({
                            title: "",
                            text: "{{__("An error occurred while starting the lecture. Contact your administrator")}}",
                            type: "error",
                            // showCancelButton: true,
                            confirmButtonText: "{{__("Close")}}",
                        }).then(function () {
                        });

                        console.log(error['responseText'])
                        $btn.button('reset');
                    },
                });
            })


            $('body').on('click', ".show_time_detail", function () {
                $("#time_list_modal").modal();

                var training_id = $(this).attr("data-id");

                $.ajax({
                    url: '/web/training/' + training_id + '/get_time_list',
                    data: {},
                    method: "GET",
                    success: function (data) {
                        var time_list = $(data).find(".time_list").html();
                        $(".time_list_block").html(time_list);

                        console.log(time_list)

                    },
                    error: function (error) {
                        console.log(error['responseText'])
                    },
                });
            })


            setInterval(function () {
                $.ajax({
                    url: '?',
                    data: {},
                    method: "GET",
                    success: function (data) {
                        var training_list_block = $(data).find(".training_list_block").html();
                        $(".training_list_block").html(training_list_block);


                    },
                    error: function (error) {
                    },
                });
            }, 10000);


        });

    </script>
@stop
