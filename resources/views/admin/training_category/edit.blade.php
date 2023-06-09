\@section('content')
    @parent
    <form id="form" action="/admin/training_category/{{{ $training_category->id }}}" method="POST" data-validate="parsley">


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Title *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-equalizer"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text" name="title" value="{{{ $training_category->title }}}" required="" class="form-control" placeholder="Name input">
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
                              cols="30" rows="3">{{{ $training_category->description }}}</textarea>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Order</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-sort-by-order"></i>
                </div>
                <div class="nk-int-st">
                    <input type="number" name="order" value="{{{ $training_category->order }}}" class="form-control" placeholder="Order input">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Is hidden</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-eye-open"></i>
                </div>
                <div class="nk-int-st">
                    <div class="toggle-select-act fm-cmp-mg">
                        <div class="nk-toggle-switch">
                            <input id="ts1" value="1" type="checkbox" {{{ $training_category->is_hidden == 1 ? "checked" : '' }}} name="is_hidden" hidden="hidden">
                            <label for="ts1" class="ts-helper"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="clearfix"></div>

        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Region Update</button>

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
                        text: "Training Category was successfully update",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.location = '/admin/training_category';
                    });

                },
                error: function (error) {
                    swal({
                        title: "",
                        text: "Was error while training category update",
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
