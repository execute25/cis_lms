@section('content')
    @parent


    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="time_list">
                    @if(count($training->training_live_times))
                        <h2>{{__("List of broadcasts via zoom")}}</h2>
                        <ul class="list-group">
                            @foreach($training->training_live_times as $training_live_time)
                                <li class="list-group-item">
                                    #{{$loop->index + 1}}. {{ normalizeDate(dateTolocal($training_live_time->start_at)) }}</li>
                            @endforeach
                        </ul>
                    @endif

                        <hr>

                    @if(count($training->training_repeat_times))
                        <h2>{{__("List of broadcasts via the site")}}</h2>
                        <ul class="list-group">
                            @foreach($training->training_repeat_times as $training_time)
                                <li class="list-group-item">#{{$loop->index + 1}}. {{ normalizeDate(dateTolocal($training_time->start_at)) }}</li>
                            @endforeach
                        </ul>
                    @endif


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
