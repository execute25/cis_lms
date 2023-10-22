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
                                        <h2>{{__("Training materials for:")}} {{$training->name}}</h2>
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
                <div class="training_material_list">
                    <ul class="list-group">
                        @for ($i = 1; $i <= 6; $i++)
                            @if($training->{"file_".$i."_name"} != "")
                                <a href="{{$training->{"file_".$i} }}" target="_blank">
                                    <li class="list-group-item">{{$training->{"file_".$i."_name"} }}</li>
                                </a>

                            @endif
                        @endfor

                    </ul>


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
