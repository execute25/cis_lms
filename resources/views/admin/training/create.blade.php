@section('content')
    @parent

    <!-- datapicker CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/datapicker/datepicker3.css">


    <form id="form" action="/admin/training" method="POST" data-validate="parsley">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Name *</h5>
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
                <h5>Description *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-equalizer"></i>
                </div>
                <div class="nk-int-st">
                    <textarea class="form-control" required="" placeholder="Description input" name="description" id=""
                              cols="30" rows="3"></textarea>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Bunny Video Id</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-equalizer"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text" name="bunny_id" class="form-control" placeholder="Bunny Video Id input">
                </div>
            </div>
        </div>


        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Translation Start date *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-calendar"></i>
                </div>
                <div class="nk-int-st">
                    <div class="form-group nk-datapk-ctm form-elet-mg date_normal" id="data_1">
                        <div class="input-group date nk-int-st">
                            <span class="input-group-addon"></span>
                            <input type="text" class="form-control" name="start_at" required="" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Translation Start Time *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-time"></i>
                </div>
                <div class="nk-int-st">

                    <select class="form-control time_select" required="" name="start_at_time"
                            style="width: 100%; margin-bottom: 10px;">
                        <option value="">Select Time</option>
                        <option value="00:00">00:00</option>
                        <option value="00:30">00:30</option>
                        <option value="01:00">01:00</option>
                        <option value="01:30">01:30</option>
                        <option value="02:00">02:00</option>
                        <option value="02:30">02:30</option>
                        <option value="03:00">03:00</option>
                        <option value="03:30">03:30</option>
                        <option value="04:00">04:00</option>
                        <option value="04:30">04:30</option>
                        <option value="05:00">05:00</option>
                        <option value="05:30">05:30</option>
                        <option value="06:00">06:00</option>
                        <option value="06:30">06:30</option>
                        <option value="07:00">07:00</option>
                        <option value="07:30">07:30</option>
                        <option value="08:00">08:00</option>
                        <option value="08:30">08:30</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                        <option value="22:00">22:00</option>
                        <option value="22:30">22:30</option>
                        <option value="23:00">23:00</option>
                        <option value="23:30">23:30</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mg-b-15">
            <div class="nk-int-mk">
                <h5>Users only from this group</h5>
            </div>
            <div class="chosen-select-act fm-cmp-mg">
                <select class="chosen" multiple="" name="include_groups[]" style="width:200px;"/>
                <option value=""></option>
                @foreach($member_groups as $group)
                    <option value="{{{$group->id}}}">{{{$group->name}}}</option>
                    @endforeach
                    </select>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Is use Zoom meeting</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-lamp"></i>
                </div>
                <div class="nk-int-st">
                    <div class="toggle-select-act fm-cmp-mg">
                        <div class="nk-toggle-switch">
                            <input id="ts1" value="1" checked type="checkbox" name="is_use_zoom" hidden="hidden">
                            <label for="ts1" class="ts-helper"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <input type="hidden" class="form-control" name="category_id" required="" value="{{$category_id}}">
        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>Create</button>

    </form>

@stop

@section('app-js')
    @parent
    <!-- datapicker JS
		============================================ -->
    <script src="/src/js/datapicker/bootstrap-datepicker.js"></script>
    <script src="/src/js/datapicker/datepicker-active.js"></script>


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
                        window.location = '/admin/training?category_id={{$category_id}}';
                    });

                },
                error: function (error) {

                    swal({
                        title: "",
                        text: "Was error while training register",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                    // $.alert('Was error while training register: ' + error['responseText']);
                }
            });

        });

    </script>
@stop
