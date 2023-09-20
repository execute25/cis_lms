<div class="reg_item zoom_setting_bloсk zoom_setting_bloсk_{{$index}}"
     style="position: relative; display: {{isset($zoom_settings[$index]["host_email"]) && $zoom_settings[$index]["host_email"] != '' ? 'block' : 'none'}}">
    <i style="" class="glyphicon glyphicon-remove zoom_setting_bloсk_remove"></i>
    <input name="zoom_settings[{{$index}}][is_delete]" type="hidden"
           value="{{isset($zoom_settings[$index]["host_email"]) && $zoom_settings[$index]["host_email"] != '' ? '0' : '1'}}"
           class="is_delete">
    <p>
    <h4>Zoom Setting # {{$index + 1}}</h4>
    </p>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Host Email</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-globe"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text"
                           name="zoom_settings[{{$index}}][host_email]"
                           value="{{isset($zoom_settings[$index]["host_email"]) ? $zoom_settings[$index]["host_email"] : ''}}"
                           class="form-control"
                           placeholder="Host Name Input">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Zoom Account ID</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-globe"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text"
                           name="zoom_settings[{{$index}}][zoom_account_id]"
                           value="{{isset($zoom_settings[$index]["zoom_account_id"]) ? $zoom_settings[$index]["zoom_account_id"] : ''}}"
                           class="form-control"
                           placeholder="Zoom Account ID Input">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Zoom Client ID</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-globe"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text"
                           name="zoom_settings[{{$index}}][zoom_client_id]"
                           value="{{isset($zoom_settings[$index]["zoom_client_id"]) ? $zoom_settings[$index]["zoom_client_id"] : ''}}"
                           class="form-control"
                           placeholder="Zoom Client ID Input">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Zoom Client Secret</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-globe"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text"
                           name="zoom_settings[{{$index}}][zoom_client_secret]"
                           value="{{isset($zoom_settings[$index]["zoom_client_secret"]) ? $zoom_settings[$index]["zoom_client_secret"] : ''}}"
                           class="form-control"
                           placeholder="Zoom Client Secret Input">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="nk-int-mk  mg-t-10">
                <h5>Zoom Redirect</h5>
            </div>
            <div class="form-group ic-cmp-int">
                <div class="form-ic-cmp">
                    <i class="glyphicon glyphicon-globe"></i>
                </div>
                <div class="nk-int-st">
                    <input type="text"
                           name="zoom_settings[{{$index}}][zoom_redirect_url]"
                           value="{{isset($zoom_settings[$index]["zoom_redirect_url"]) ? $zoom_settings[$index]["zoom_redirect_url"] : ''}}"
                           class="form-control"
                           placeholder="Zoom Redirect Input">
                </div>
            </div>
        </div>

    </div>

</div>


