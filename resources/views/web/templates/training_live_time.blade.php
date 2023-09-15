<div class="reg_item training_live_time_bloсk training_live_time_bloсk_{{$index}}"
     style="position: relative; display: {{isset($training_live_times[$index]["start_at"]) && $training_live_times[$index]["start_at"] != '' ? 'block' : 'none'}}">
    <i style="" class="glyphicon glyphicon-remove training_live_time_bloсk_remove"></i>
    <input name="training_live_time[{{$index}}][is_delete]" type="hidden" value="{{isset($training_live_times[$index]["start_at"]) && $training_live_times[$index]["start_at"] != '' ? '0' : '1'}}" class="is_delete">
    <p>
    <h4>Live Time # {{$index + 1}}</h4>
    </p>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">


            <div class="nk-int-mk  mg-t-10">
                <h5>Translation Start Date *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-calendar"></i>
                </div>
                <div class="nk-int-st">
                    <div class="form-group">
                        <div class="input-group  datetimepicker" id="datetimepicker1"
                             data-target-input="nearest">
                            <input type="text" class="form-control  datetimepicker"
                                   value="{{isset($training_live_times[$index]["start_at"]) ? $training_live_times[$index]["start_at"] : ''}}"
                                   name="training_live_time[{{$index}}][start_at]"
                                   data-target="#datetimepicker1"/>
                            {{--                            <span class="input-group-addon" data-target="#datetimepicker1"--}}
                            {{--                                  data-toggle="datetimepicker">--}}
                            {{--                                           <span class="glyphicon glyphicon-calendar"></span>--}}
                            {{--                                           </span>--}}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Translation End Date *</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-calendar"></i>
                </div>
                <div class="nk-int-st">
                    <div class="form-group">
                        <div class="input-group  datetimepicker" id="datetimepicker2"
                             data-target-input="nearest">
                            <input type="text" class="form-control  datetimepicker"
                                   value="{{isset($training_live_times[$index]["end_at"]) ? $training_live_times[$index]["end_at"] : ''}}"
                                   name="training_live_time[{{$index}}][end_at]"
                                   data-target="#datetimepicker2"/>
                            <span class="input-group-addon" data-target="#datetimepicker2"
                                  data-toggle="datetimepicker">
{{--                                                                   <span class="glyphicon glyphicon-calendar"></span>--}}
                                                                   </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


