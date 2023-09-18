<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>CIS Platform</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="/src/img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/owl.carousel.css">
    <link rel="stylesheet" href="/src/css/owl.theme.css">
    <link rel="stylesheet" href="/src/css/owl.transitions.css">
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/meanmenu/meanmenu.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/normalize.css">

    <!-- chosen CSS
    ============================================ -->
    <link rel="stylesheet" href="/src/css/chosen/chosen.css">


    <!-- dialog CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/dialog/sweetalert2.min.css">
    <link rel="stylesheet" href="/src/css/dialog/dialog.css">

    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- jvectormap CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/jvectormap/jquery-jvectormap-2.0.3.css">
    <!-- notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/notika-custom-icon.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/wave/waves.min.css">
    <link rel="stylesheet" href="/src/css/wave/button.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="/src/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="/src/js/vendor/modernizr-2.8.3.min.js"></script>

    <!-- yajra datatables JS
		============================================ -->
    <link rel="stylesheet" type="text/css" href="/src/css/datatables.min.css"/>

    <!-- style CSS
    ============================================ -->
    <link rel="stylesheet" href="/src/css/custom.css">
    <link rel="stylesheet" href="/src/css/media.css">

</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Start Header Top Area -->
<div class="header-top-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-6">
                <div class="logo-area">
                    <a href="#" class="logo_link"><img src="/src/img/logo/logo.png" alt=""/> CIS</a>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-6">
                <div class="header-top-menu">
                    <ul class="nav navbar-nav notika-top-nav">

                        @role(['super-admin'])
                        <li class="nav-item nc-al">
                            <a href="/admin/setting/change_setting" role="button"
                               aria-expanded="false" class="nav-link dropdown-toggle"><span><i
                                        class="notika-icon notika-settings"></i></span>
                            </a>
                        </li>
                        @endrole

                        <li class="nav-item nc-al">
                            <a href="/logout"
                               aria-expanded="false" class="nav-link dropdown-toggle"><span><i
                                        class="glyphicon glyphicon-log-out"></i></span>
                            </a>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Top Area -->
<!-- Mobile Menu start -->
<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul class="mobile-menu-nav">
                            <li><a data-toggle="collapse" data-target="#Charts" href="#">Home</a>
                                <ul class="collapse dropdown-header-top">
                                    <li><a href="index.html">Dashboard One</a></li>
                                    <li><a href="index-2.html">Dashboard Two</a></li>
                                    <li><a href="index-3.html">Dashboard Three</a></li>
                                    <li><a href="index-4.html">Dashboard Four</a></li>
                                    <li><a href="analytics.html">Analytics</a></li>
                                    <li><a href="widgets.html">Widgets</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demoevent" href="#">Обучения</a>
                                <ul id="demoevent" class="collapse dropdown-header-top">
                                    <li><a href="inbox.html">Продстоящие обучения</a></li>
                                </ul>
                            </li>
                            {{--                            <li><a data-toggle="collapse" data-target="#democrou" href="#">Interface</a>--}}
                            {{--                                <ul id="democrou" class="collapse dropdown-header-top">--}}
                            {{--                                    <li><a href="animations.html">Animations</a></li>--}}
                            {{--                                    <li><a href="google-map.html">Google Map</a></li>--}}
                            {{--                                    <li><a href="data-map.html">Data Maps</a></li>--}}
                            {{--                                    <li><a href="code-editor.html">Code Editor</a></li>--}}
                            {{--                                    <li><a href="image-cropper.html">Images Cropper</a></li>--}}
                            {{--                                    <li><a href="wizard.html">Wizard</a></li>--}}
                            {{--                                </ul>--}}
                            {{--                            </li>--}}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu end -->
<!-- Main Menu area start-->
<div class="main-menu-area mg-tb-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                    @role(['super-admin', 'secretary'])
                    <li class="{{ request()->route()->getPrefix() === '/admin' ? 'active' : ''}}">
                        <a data-toggle="tab" href="#Admin"><i class="notika-icon notika-house"></i> Admin Panel</a>
                    </li>
                    @endrole
                    <li class="{{ Route::is('training.*') && !Route::is('admin.training.*') ? 'active' : '' }}"><a
                            data-toggle="tab" href="#edu"><i
                                class="notika-icon notika-mail"></i> Обучения</a>
                    </li>
                </ul>
                <div class="tab-content custom-menu-content">

                    @role(['super-admin', 'secretary'])
                    <div id="Admin"
                         class="tab-pane in notika-tab-menu-bg animated flipInX {{isActiveSubTab('admin') ? "active" : ''}}">
                        <ul class="notika-main-menu-dropdown">
                            <li class="{{Route::is('user.*') ? 'active' : ''}}"><a
                                    href="{{ route('user.index') }}">Users</a></li>
                            <li class="{{ Route::is('cell.*') ? 'active' : '' }}"><a
                                    href="{{ route('cell.index') }}">Cells</a></li>
                            <li class="{{ Route::is('region.*') ? 'active' : '' }}"><a
                                    href="{{ route('region.index') }}">Regions</a></li>
                            <li class="{{ Route::is('training_category.*') || Route::is('admin.training.*') ? 'active' : '' }}">
                                <a href="{{ route('training_category.index') }} ">Training Categories</a></li>
                            <li class="{{ Route::is('membergroup.*') ? 'active' : '' }}"><a
                                    href="{{ route('membergroup.index') }} ">Member Groups</a></li>

                        </ul>
                    </div>
                    @endrole


                    <div id="edu"
                         class="tab-pane notika-tab-menu-bg animated flipInX {{ Route::is('training.*') ? 'active' : '' }}">
                        <ul class="notika-main-menu-dropdown">
                            <li class="{{ Route::is('training.upcoming_trainings') ? 'active' : '' }}"><a
                                    href="{{ route('training.upcoming_trainings') }} ">{{ __("Upcoming lectures") }}</a>
                            </li>
                            <li class="{{ Route::is('training.available_training_categories') ? 'active' : '' }}"><a
                                    href="{{ route('training.available_training_categories') }} ">{{ __("Available training") }}</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Menu area End-->

<!-- Start Sale Statistic area-->
<div class="sale-statistic-area">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @section('content')

    @show

</div>
<!-- End Sale Statistic area-->

<!-- Start Footer area-->
<div class="footer-copyright-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="footer-copy-right">
                    <p>Copyright © 2023
                        . All rights reserved. CIS Region</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Footer area-->

@section('app-js')

    <!-- jquery
    ============================================ -->
    <script src="/src/js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
        ============================================ -->
    <script src="/src/js/bootstrap.min.js"></script>

    <!-- owl.carousel JS
        ============================================ -->
    <script src="/src/js/owl.carousel.min.js"></script>

    <!-- chosen JS
    ============================================ -->
    <script src="/src/js/chosen/chosen.jquery.js"></script>


    <!-- scrollUp JS
        ============================================ -->
    <script src="/src/js/jquery.form.min.js"></script>
    <!-- meanmenu JS
        ============================================ -->
    <script src="/src/js/meanmenu/jquery.meanmenu.js"></script>

    <!-- mCustomScrollbar JS
        ============================================ -->
    <script src="/src/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- jQuery Form JS
    ============================================ -->
    <script src="/src/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>

    {{--<script src="/src/js/flot/jquery.flot.js"></script>--}}
    {{--<script src="/src/js/flot/jquery.flot.resize.js"></script>--}}
    {{--<script src="/src/js/flot/curvedLines.js"></script>--}}
    {{--<script src="/src/js/flot/flot-active.js"></script>--}}

    <!-- plugins JS
    ============================================ -->



    <script src="/src/js/easypiechart/jquery.easy-pie-chart.js"></script>

    <script src="/src/js/plugins.js"></script>
    <!--  Chat JS
        ============================================ -->
    <script src="/src/js/dialog/sweetalert2.min.js"></script>
    <script src="/src/js/chat/moment.min.js"></script>
    <script src="/src/js/chat/jquery.chat.js"></script>
    <!-- main JS
        ============================================ -->
    <script src="/src/js/main.js"></script>
    <!-- yajra datatables JS
        ============================================ -->
    <script type="text/javascript" src="/src/js/datatables/datatables.min.js"></script>
@show


@section('inline-js')

@show

<script src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.6/jstz.min.js"></script>

<script>
    @if(Auth::check())
    document.addEventListener('DOMContentLoaded', function () {
        var saved_timezone = "{{Auth::user()->timezone}}";
        var current_timezone = jstz.determine();
        var current_timezone = current_timezone.name();
        console.log("saved_timezone " + saved_timezone)
        console.log("current_timezone " + current_timezone)

        if (saved_timezone == current_timezone)
            return;

        $.ajax({
            url: '/web/user/update_timezone',
            data: {
                timezone: current_timezone
            },
            method: "POST",
            success: function (data) {


            },
            error: function (error) {
                console.log(error['responseText'])
            },
        });

    });

    @endif
</script>

</body>

</html>
