@section('content')
    @parent
    <form id="form" action="/admin/region" method="POST" data-validate="parsley">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Name</h5>
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
                <h5>Korean Name</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-equalizer"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text" name="korean_name" class="form-control" placeholder="Korean Name input">
                </div>
            </div>
        </div>


        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Create</button>

    </form>

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
                        window.location = '/admin/region';
                    });

                },
                error: function (error) {

                    swal({
                        title: "",
                        text: "Was error while region register",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                    // $.alert('Was error while region register: ' + error['responseText']);
                }
            });

        });

    </script>
@stop
