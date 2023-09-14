@section('content')
    @parent

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">


                    <form id="form" action="/admin/region/{{{ $region->id }}}" method="POST" data-validate="parsley">


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Name</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="name"
                                           value="{{$region->name}}"
                                           required=""
                                           class="form-control"
                                           placeholder="Name input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Korean Name</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="glyphicon glyphicon-globe"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text"
                                           name="korean_name"
                                           value="{{$region->korean_name}}"
                                           class="form-control"
                                           placeholder="Korean Name input">
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>

                        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Region Update</button>

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
                        text: "Region was successfully update",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.location = '/admin/region';
                    });

                },
                error: function (error) {
                    swal({
                        title: "",
                        text: "Was error while region update",
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
