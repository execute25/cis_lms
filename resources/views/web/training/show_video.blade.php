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
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-support"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>{{$training->name}}</h2>
                                        <p>{{$training->category_title}}</p>
                                    </div>
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
                    <h2>Body Copy</h2>
                    <div class="typography-bd">
                        <h3>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non
                            commodo luctus.</h3>
                        <p>Pellentesque lacinia sagittis libero et feugiat. Etiam volutpat adipiscing tortor non luctus.
                            Vivamus venenatis vitae metus et aliquet. Praesent vitae justo purus. In hendrerit lorem
                            nisl, ac lacinia urna aliquet non. Quisque nisi tellus, rhoncus quis est sit amet, lacinia
                            euismod nunc. Aenean nec nibh velit. Fusce quis ante in nisl molestie fringilla. Nunc vitae
                            ante id magna feugiat condimentum. Maecenas sit amet urna massa.</p>
                        <p class="tab-mg-b-0">Integer eu lectus sollicitudin, hendrerit est ac, sollicitudin nisl.
                            Quisque viverra sodales lectus nec ultrices. Fusce elit dolor, dignissim a nunc id, varius
                            suscipit turpis. Cras porttitor turpis vitae leo accumsan molestie. Morbi vitae luctus leo.
                            Sed nec scelerisque magna, et adipiscing est. Proin lobortis lectus eu sem ullamcorper,
                            commodo malesuada quam fringilla. Curabitur ac nunc dui. Class aptent taciti sociosqu ad
                            litora torquent per conubia nostra, per inceptos himenaeos. Fusce sagittis enim eu est
                            lacinia, ut egestas ligula imperdiet, ute egestas ligulan imperdiet.</p>
                    </div>
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
            $('body').on('click', ".connect_to_training_zoom", function () {
                var training_id = $(this).parents(".training_btn_block").attr("data-id");
                var $btn = $(this);
                $btn.button('loading');

                $.ajax({
                    url: '/web/training/' + training_id + '/get_zoom_join_link',
                    data: {},
                    method: "GET",
                    success: function (data) {
                        window.open(data.join_zoom_link, "_blank");
                        $btn.button('reset');
                    },
                    error: function (error) {

                        swal({
                            title: "",
                            text: "{{__("An error occurred while starting the lecture. Contact your administrator")}}",
                            type: "error",
                            // showCancelButton: true,
                            confirmButtonText: "{{__("Close")}}",
                        }).then(function () {
                        });

                        console.log(error['responseText'])
                        $btn.button('reset');
                    },
                });
            })

        });

    </script>
@stop
