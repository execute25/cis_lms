@section('content')
    @parent

    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="rating_cont" style="">
                                        <div class="easypiechart easyPieChart easypiechart"
                                             data-percent="{{ isset($training_user->progress) ? round($training_user->progress) : ''}}"
                                             data-line-wdth="11" data-loop="false" data-size="60"
                                             style="width: 60px; height: 60px; line-height: 60px;">
                                            <span class="progress_text"
                                                  style="">{{isset($training_user->progress) ? round($training_user->progress) : 0}}%</span>
                                            <canvas width="75" height="75" style="width: 60px; height: 60px;"></canvas>
                                            <canvas width="60" height="60"></canvas>
                                        </div>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>{{$training->name}}</h2>
                                        <p>{{$training->category_title}}</p>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="material_btn_block">
                                    @if(countTrainingMaterials($training) == 1)
                                        <a href="{{getFirstMaterial($training)}}" class="btn nk-indigo"
                                           target="_blank"> <i
                                                class="glyphicon glyphicon-book"></i> {{__("Training material")}}
                                        </a>
                                    @elseif(countTrainingMaterials($training) > 1)
                                        <a href="{{route("training.material_list", ['id' => $training->id])}}"
                                           class="btn nk-indigo"><i
                                                class="glyphicon glyphicon-book"></i> {{__("Training material")}}
                                        </a>
                                    @endif
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
                <div class="typography-list typography-mgn">
                    {{--                    <h2>Body Copy</h2>--}}
                    <div class="typography-bd">
                        @if($training->bunny_id != "")
                            <div class="row">
                                <div class="panel_type panel_type_1" style="">
                                    <video
                                        id="normal-player_0"
                                        class="video-js training_video"
                                        controls
                                        preload="auto"
                                        oncontextmenu="return false;">

                                        <p class="vjs-no-js">
                                            To view this video please enable JavaScript, and consider upgrading to a
                                            web browser that
                                            <a href="https://videojs.com/html5-video-support/" target="_blank">
                                                supports HTML5 video
                                            </a>
                                        </p>
                                    </video>

                                </div>

                                <div class="alert alert-warning" role="alert">
                                    {{--                                    - После начала просмотра лекции, лекция будет доступна на протяжении 2-х часов после--}}
                                    {{--                                    зарезервированного Вами--}}
                                    {{--                                    времени. <br>--}}
                                    {{--                                    (В случае отключения электроэнергии или проблем с Интернетом вы сможете досмотреть--}}
                                    {{--                                    лекцию в течении--}}
                                    {{--                                    2-х часов.) <br>--}}
                                    - Пришло время слушать Слово Божье. Приготовьте Ваше сердце, Библию и письменные
                                    принадлежности. <br>
                                    - При просмотре лекции отключена функция перемотки видео. ⏩❌ <br>
                                    (Система запоминает на каком моменте лекции Вы завершили просмотр, после, можете
                                    начать просмотр с места, на
                                    котором остановили просмотр) <br>
                                </div>

                            </div>

                        @else
                            <div class="notice_cont">
                                ⚠️Лекция пока не загружена, пожалуйста подождите
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>





@stop

@section('app-js')
    @parent

    <script src="/src/js/parsley/parsley.new.js"></script>
    {{--    <script src="/src/js/load-image/load-image.min.js"></script>--}}

    <script>
        window.onerror = function () {
            return true;
        }
    </script>

    <link href="//vjs.zencdn.net/7.10.2/video-js.min.css" rel="stylesheet">
    <script src="//vjs.zencdn.net/7.10.2/video.min.js"></script>


    <link href="https://unpkg.com/@silvermine/videojs-quality-selector/dist/css/quality-selector.css" rel="stylesheet">


    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-quality-levels/3.0.0/videojs-contrib-quality-levels.min.js"
        integrity="sha512-gRsKAtm19gssgDKLCPy5uELW2NtqRVE/5M6R+b1Ieh0phkSFumS1odBCOolmItb1tKt+FfKuNqL5nnwtX0KZrQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.4/dist/videojs-hls-quality-selector.min.js"></script>


@stop

@section('inline-js')
    @parent
    <script>
        pluginModule.easyPieChartReload()
    </script>

    <script>


        // $(".navbar-brand").html("<i class='glyphicon glyphicon-chevron-left'></i>   Перейти к списку трансляций")
        var lection_id = $(".lection_id").val();
        var is_finished = $(".is_finished").val();
        var timeWatched = "{{{isset($training_user->watch_time) ? $training_user->watch_time : 0}}}"
        var timerId;
        var video_selector = "normal-player_0";
        var watching_seconds = 0;
        var video_link = "{{{$training->bunny_id}}}"
        if ($("#" + video_selector).length > 0) {
            // setTimeout(function () {
            var video_norm_option = {
                controls: true,
                autoplay: false,
                preload: 'auto',
                sources: [
                    {
                        src: "https://vz-3f71c788-229.b-cdn.net/" + video_link + "/playlist.m3u8",
                        type: "application/x-mpegURL"
                    }
                ],
                // playbackRates: [0.5, 1, 1.5, 2],
            }
            var player_normal = videojs(video_selector, video_norm_option, function onPlayerReady() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                player_normal.pause();
                // In this context, `this` is the player that was created by Video.js.
                player_normal.on('play', function (event) {
                    console.log("11^55")
                    timerId = setInterval(function () {
                        var duration = player_normal.duration();

                        watching_seconds = watching_seconds + 1

                        if (watching_seconds % 10 == 0) {

                            var seconds = player_normal.currentTime()
                            updateWatchPoint(seconds, duration)
                        }
                    }, 1000);
                });

                // How about an event listener?
                this.on('ended', function () {
                    // videojs.log('Awww...over so  soon?!');
                });

                player_normal.on('pause', function (data) {
                    if (data.duration - data.seconds <= 10) {
                        finishLection()
                    }
                    clearInterval(timerId);
                });


                player_normal.on('ended ', function (data) {
                    console.log('ended  the video!');
                });


                player_normal.on("seeking", function (event) {
                    if (timeWatched < player_normal.currentTime()) {
                        player_normal.currentTime(timeWatched);
                    }
                });

                player_normal.on("seeked", function (event) {
                    if (timeWatched < player_normal.currentTime()) {
                        player_normal.currentTime(timeWatched);
                    }
                });

                setInterval(function () {
                    if (!player_normal.paused()) {
                        if (player_normal.currentTime() > timeWatched) {
                            timeWatched = player_normal.currentTime();
                        }
                    }
                }, 1000);


            });
        }


        function updateWatchPoint(watching_seconds, duration) {

            $.ajax({
                url: '/web/training/{{$training->id}}/update_watch_point',
                method: 'post',
                data: {
                    watching_seconds: watching_seconds,
                    duration: duration,
                },
                success: function (data) {
                    var progress = Math.round(data.progress);
                    var current_progress = $(".easypiechart").attr("data-percent");

                    if (progress > current_progress) {
                        console.log(progress)
                        $(".easypiechart").attr("data-percent", progress);
                        $(".progress_text").html(progress + "%");
                        $('.easypiechart').data('easyPieChart').update(progress);
                        // pluginModule.easyPieChartReload()
                    }


                },
                error: function (error) {
                    console.log(error['responseText'])
                },
            });
        }

        function finishLection() {
            $.ajax({
                url: '/web/training/{{$training->id}}//finish_lection',
                method: 'post',
                data: {},
                success: function (data) {

                },
                error: function (error) {
                    console.log(error['responseText'])
                },
            });
        }

    </script>



@stop
