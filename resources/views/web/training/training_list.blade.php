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
                                        <h2>{{$category->title}}</h2>
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
                        <article class="training_item {{$training->in_process == 1 ? 'in_process' : ''}}"
                                 onclick="redirectToTrainingDetailPage('{{ $training->in_process == 0 ?  route('training.show_video',['id' => $training->id]) : route('training.upcoming_trainings')  }}')"
                        >
                            <div>
                                <div class="training_title">{{$training->name}}</div>
                                <div class="training_description">{{$training->description}}</div>



                                <div class="training_footer">
                                    @if($training->in_process == 1)
                                        <span style="color: #cb3939;"> <i class="glyphicon glyphicon-folder-close"></i> {{__("Access to this lecture is closed because the lecture has not yet ended. You can join on the 'Upcoming Lectures' page")}}</span>
                                    @else
                                        <span> <i class="glyphicon glyphicon-lamp"></i> {{__("Listened through Zoom")}}: {{isset($training->attend_duration) && $training->attend_duration >= 1800 ? __("Yes") : __("No")}}</span>
                                        <span> {!! ' | <i class="glyphicon glyphicon-time"></i> ' .  __("listened in repeat") !!}: {{isset($training->progress) && $training->progress != 0  ? round($training->progress) : 0}}%</span>
                                        <span> {{$training->file_1 != "" || $training->file_2 != ""   ? ' | ' .  __("Summary is loaded") : ""}}</span>
                                    @endif
                                </div>
                            </div>

                            @if(\App\Models\UserModel::isLeader())
                                {{--                            @role(['super-admin', 'secretary', 'team-leader', 'cell-leader'])--}}
                                <div class="training_setting_panel">
                                    <a href="{{route("training.attendance_list",["id"=>$training->id])}}"><i
                                            class="glyphicon glyphicon-user"></i></a>
                                </div>
                            @endif
                            {{--                            @endrole--}}
                        </article>

                    @endforeach

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
        function redirectToTrainingDetailPage(link) {
            window.location.href = link;
        }


        $(function () {


        });

    </script>
@stop
