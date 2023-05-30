@section('content')
    @parent

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <form id="form" action="/admin/cell/{{{ $cell->id }}}" method="POST" data-validate="parsley">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Name</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-equalizer"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text" name="name" value="{{$cell->name}}" required="" class="form-control"
                           placeholder="Name input">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mg-b-15">
            <div class="nk-int-mk">
                <h5>Region</h5>
            </div>
            <div class="chosen-select-act fm-cmp-mg">
                <select class="chosen" name="region_id" required="" style="width:200px;"/>
                <option value=""></option>
                @foreach($regions as $region)
                    <option
                        {{$cell->region_id == $region->id ? "selected" : ""}}  value="{{{$region->id}}}">{{{$region->name}}}</option>
                    @endforeach
                    </select>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mg-b-15">
            <div class="nk-int-mk">
                <h5>Leader of cell</h5>
            </div>
            <div class="chosen-select-act fm-cmp-mg">
                <select name="leader_id" id="" class="select2 form-control leader_id" required=""
                        style="width: 100%;">

                    @if($selected_leader)
                        <option value="{{$selected_leader->id}}"
                                selected>{{$selected_leader->name}}({{$selected_leader->korean_name}})</option>
                    @endif

                </select>
            </div>
        </div>


        <div class="clearfix"></div>

        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>User Update</button>

    </form>

@stop

@section('app-js')
    @parent
    <script src="/src/js/parsley/parsley.new.js"></script>
    <script src="/src/js/load-image/load-image.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        text: "User was successfully update",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.location = '/admin/cell';
                    });

                },
                error: function (error) {
                    swal({
                        title: "",
                        text: "Was error while cell update",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                }
            });

            $('.leader_id').select2({
                placeholder: "Select a State",
                allowClear: true,
                // tags: true,
                delay: 250,
                language: "ru",
                minimumInputLength: 2,

                cache: false,
                ajax: {
                    url: '/admin/user/get_user_list',
                    dataType: 'json',
                    success: function (e) {
                    }
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                }

            });

        });

    </script>
@stop
