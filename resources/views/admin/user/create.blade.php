@section('content')
    @parent
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">


                    <form id="form" action="/admin/user" method="POST" data-validate="parsley">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk">
                                <h5>User Level</h5>
                            </div>
                            <div class="chosen-select-act fm-cmp-mg">
                                <select class="chosen" name="role" required="" style="width:200px;">
                                    <option value=""></option>
                                    <?php
                                    $roles = App\Models\UserModel::getRolesList();
                                    ?>
                                    @foreach($roles as $role=>$title)
                                        <option value="{{{$role}}}">{{{$title}}}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Name</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="notika-icon notika-support"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text" name="name" required="" class="form-control"
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
                                    <i class="notika-icon notika-support"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text" name="korean_name" class="form-control"
                                           placeholder="Korean Name input">
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Email</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="notika-icon notika-mail"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text" name="email" class="form-control" required
                                           placeholder="Email input">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Password</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="notika-icon notika-dot"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="password" name="password" id="password" class="form-control" required
                                           placeholder="Password">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Password Confirm</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="notika-icon notika-dot"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="password" name="password-confirm" id="confirmPassword"
                                           class="form-control" required
                                           data-equalTo="#password"
                                           data-parsley-equalto="#password"
                                           placeholder="Password Confirm">
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk">
                                <h5>Department</h5>
                            </div>
                            <div class="chosen-select-act fm-cmp-mg">
                                <select class="chosen" name="department" required="" style="width:200px;"/>
                                <option value=""></option>
                                <?php
                                $roles = App\Models\UserModel::getDepartmentList();
                                ?>
                                @foreach($roles as $department=>$title)
                                    <option value="{{{$department}}}">{{{$title}}}</option>
                                    @endforeach
                                    </select>


                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Telegram nickname</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="notika-icon notika-next"></i>
                                </div>
                                <div class="nk-int-st">
                                    <input type="text" name="telegram_nickname" class="form-control"
                                           placeholder="Telegram nickname input">
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="nk-int-mk  mg-t-10">
                                <h5>Prifile Image</h5>
                            </div>
                            <div class="form-group ic-cmp-int">
                                <div class="form-ic-cmp">
                                    <i class="notika-icon notika-star"></i>
                                </div>
                                <div class="nk-int-st">
                                    <section class="panel">
                                        <header class="panel-heading">Image #1</header>
                                        <div class="panel-body text-center">
                                            <div class="load-image-preview"></div>
                                        </div>
                                        <footer class="panel-footer">
                                            <input type="file"
                                                   data-load-image
                                                   name="photo_1"
                                                   class="btn btn-sm btn-white"
                                                   title="<i class='icon-plus'></i>image upload"/>
                                        </footer>
                                    </section>
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>

                        <button class="btn btn-lg btn-primary btn-block"><i class="icon-plus"></i>User Create</button>

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
            $form.parsley();

            $form.ajaxForm({
                beforeSubmit: function (arr, $form) {
                    if (!$form.parsley('isValid'))
                        return false;
                },
                success: function () {
                    swal({
                        title: "",
                        text: "User was successfully created",
                        type: "success",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                        window.location = '/admin/user';
                    });

                },
                error: function (error) {

                    swal({
                        title: "",
                        text: "Was error while user register",
                        type: "error",
                        // showCancelButton: true,
                        confirmButtonText: "Close",
                    }).then(function () {
                    });
                    // $.alert('Was error while user register: ' + error['responseText']);
                }
            });

        });

    </script>
@stop
