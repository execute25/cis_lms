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
                                        <h2>{{__("Report of")}}: {{$training->name}}</h2>
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

                @foreach($training_reports as $training_report)

                    <h4 class="cell_name">{{__("Name of Cell")}}: {{$training_report->name}} ({{$training_report->team}}
                        )</h4>

                    <table class="table">

                        <thead>
                        <tr>
                            <th>{{__("Student name")}}</th>
                            <th>{{__("Attend time by zoom")}}</th>
                            <th>{{__("Watch time in repeat")}}</th>
                            <th>{{__("Is Attended Offline")}}</th>

                        </tr>
                        </thead>

                        <tbody>
                        @foreach($training_report->members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>

                                <td>{{ isset($member->report->attend_duration)  && $member->report->attend_duration != 0 ? gmdate("H:i:s", (int) $member->report->attend_duration) : ''}}
                                </td>
                                <td>{{ isset($member->report->watch_time) && $member->report->watch_time != 0 ? gmdate("H:i:s", $member->report->watch_time) . " (".round($member->report->progress)."%)" : ''}}</td>
                                <td>
                                    <select name="" data-member_id="{{$member->id}}"
                                            data-cell_id="{{$training_report->id}}"
                                            class="form-control is_offline_select">
                                        <option
                                            value="0" {{!isset($member->report->is_offline) || (isset($member->report->is_offline) && $member->report->is_offline == 0) ? "selected" : ''}} >{{__("No")}}</option>
                                        <option
                                            value="1" {{(isset($member->report->is_offline) && $member->report->is_offline == 1) ? "selected" : ''}}>{{__("Yes")}} </option>
                                    </select>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <hr>

                @endforeach


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
            $('body').on('change', ".is_offline_select", function () {

                var member_id = $(this).attr("data-member_id");
                var cell_id = $(this).attr("data-cell_id");
                var is_offline = $(this).val();

                $.ajax({
                    url: '/web/training/{{$training_id}}/change_is_offline',
                    data: {
                        member_id: member_id,
                        cell_id: cell_id,
                        is_offline: is_offline,
                    },
                    method: "POST",
                    success: function (data) {


                    },
                    error: function (error) {
                        console.log(error['responseText'])
                    },
                });
            })

        });

    </script>
@stop
