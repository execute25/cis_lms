@extends('layouts.master')



@section('content')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">

                    <section class="panel">
                        <header class="panel-heading">
                        </header>

                        <div class="table-responsive">
                            {!! $dataTable->table(["class" => "stripe"]) !!}
                        </div>

                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="member_modal" role="dialog" style="display: none;">
        <div class="modal-dialog modal-large">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h2>Cell Member List</h2>
                    <select class="select2 form-control add_new_member_select" required=""
                            style="width: 100%;">

                    </select>

                    <hr>

                    <div class="member_list">
                        <table class="table selected_member_table table-bordered table-sc-ex">
                            <thead>
                            <tr class="warning">
                                <th>Name</th>
                                <th>Korean Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        $(function () {

            var $table = $('.dataTable');

            $("[type='search']").attr("placeholder", "search");

            $('body').on('click', '[data-action="destroy"]', function (e) {
                var id = $(this).attr("data-id");
                if (confirm('Are you sure to delete it?')) {
                    $.ajax({
                        url: '/admin/cell/' + id,
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


            $('#member_modal').on('hidden.bs.modal', function () {
                $table.DataTable().ajax.reload(null, false);
            });

            $('[data-download-form]').click(function (e) {
                e.preventDefault();
                //window.location = '/data/form/cell.csv';
                window.location = '/download/cell.csv?root=/data/form';
            });

            $('[data-form-batch]').ajaxForm({
                url: '/admin/cell/batch',
                success: function () {
                    alert('The upload is completed successfully!');
                    $table.DataTable().ajax.reload(null, false);
                },
                error: function () {
                    alert('The upload failed');
                }
            });

        });

        var current_cell_id = 0
        $('body').on('click', '.show_cell_member_modal', function (e) {
            current_cell_id = $(this).attr("data-id");
            $("#member_modal").modal();
            refreshMemberList()

        })

        $('body').on('keyup', '.member_search_input', function (e) {
            var search = $(this).val();

            if (search.length < 2)
                return false;


            $.ajax({
                url: '/admin/user/search_users',
                data: {
                    search: search,
                    current_cell_id: current_cell_id,
                },
                method: "GET",
                success: function (data) {

                    var html = "";

                    $.each(data, function (key, val) {
                        html += "<tr>";
                        html += "<td>" + val.name + "</td>";
                        html += "<td>" + val.korean_name + "</td>";
                        html += "<td><span class='btn btn-success btn-sm'>Add to Cell</span></td>";
                        html += "</tr>";
                    })

                    $(".search_member_table tbody").html(html);

                },
                error: function (error) {
                    console.log(error['responseText'])
                },
            });


        })

        $('.add_new_member_select').select2({
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

        $('.add_new_member_select').on('select2:select', function (e) {
            var selectedData = e.params.data;
            var member_id = selectedData.id;
            attachMemberToCell(member_id);

        });

        function attachMemberToCell(member_id) {
            $.ajax({
                url: '/admin/cell/' + current_cell_id + '/attach_member',
                data: {
                    member_id: member_id,
                },
                method: "POST",
                success: function (data) {
                    refreshMemberList()
                },
                error: function (error) {
                    console.log(error['responseText'])
                },
            });
        }

        function refreshMemberList() {
            $.ajax({
                url: '/admin/cell/' + current_cell_id + '/get_member_list',
                data: {},
                method: "GET",
                success: function (data) {
                    var html = "";

                    $.each(data, function (key, val) {
                        html += "<tr>"
                        html += "<td>" + val.name + "</td>"
                        html += "<td>" + val.korean_name + "</td>"
                        html += "<td><span class='btn btn-danger btn-sm detach_member' data-id='" + val.id + "'>Detach</span></td>"
                        html += "</tr>"
                    })

                    $(".selected_member_table tbody").html(html);

                },
                error: function (error) {
                    console.log(error['responseText'])
                },
            });
        }

        $('body').on('click', '.detach_member', function (e) {
            var id = $(this).attr("data-id");
            $.ajax({
                url: '/admin/cell/' + current_cell_id + '/detach_member',
                data: {
                    member_id: id
                },
                method: "POST",
                success: function (data) {
                    refreshMemberList()
                },
                error: function (error) {
                    console.log(error['responseText'])
                },
            });
        })


    </script>
@stop
