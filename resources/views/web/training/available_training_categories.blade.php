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
                                        <h2>{{__("Available training")}}</h2>
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
                <div class="training_category_list_block">
                    @foreach($training_categories as $training_category)

                        <article class="category_item"
                                 onclick="redirectToTrainingListPage('{{ route('training.training_list',['category_id' => $training_category->id]) }}')">
                            <div class="category_title">
                                <span class="category_title_text">{{$training_category->title}}</span>
                            </div>
                            <div class="category_description">{{$training_category->description}}</div>
                            <div class="category_header">
{{--                                <span class="lesson_number">--}}
{{--                                   <i class="glyphicon glyphicon-book"></i> {{__("Lessons count:")}}  {{$training_category->lection_count}}--}}
{{--                                </span>--}}

                                <span class="btn btn-warning">{{__("Go to the list of lectures")}}</span>
                            </div>
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
        function redirectToTrainingListPage(link) {
            window.location.href = link;
        }


        $(function () {


        });

    </script>
@stop
