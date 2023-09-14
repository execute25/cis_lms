@extends('layouts.master')



@section('content')
    @parent

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">

                    <section class="panel">
                        <header class="panel-heading">
                            <h4>{{$training_category ? $training_category->title : ''}}</h4>
                        </header>

                        <div class="table-responsive">
                            {!! $dataTable->table(["class" => "stripe"]) !!}
                        </div>

                    </section>


                </div>
            </div>
        </div>
    </div>
@stop

@section('app-js')
    @parent
@stop

@section('inline-js')
    @parent

    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        $(function () {

            var $table = $('.dataTable');

            $("[type='search']").attr("placeholder", "search");

            $('body').on('click', '[data-action="destroy"]', function (e) {
                var id = $(this).attr("data-id");
                if (confirm('Are you sure to delete it?')) {
                    $.ajax({
                        url: '/admin/training/' + id,
                        data: {
                            _method: "DELETE"
                        },
                        method: 'POST',
                        success: function (data) {
                            $table.DataTable().ajax.reload(null, false);
                        }
                    });
                }
            })


            $('[data-download-form]').click(function (e) {
                e.preventDefault();
                //window.location = '/data/form/training.csv';
                window.location = '/download/training.csv?root=/data/form';
            });

            $('[data-form-batch]').ajaxForm({
                url: '/admin/training/batch',
                success: function () {
                    alert('The upload is completed successfully!');
                    $table.DataTable().ajax.reload(null, false);
                },
                error: function () {
                    alert('The upload failed');
                }
            });

        });


    </script>
@stop
