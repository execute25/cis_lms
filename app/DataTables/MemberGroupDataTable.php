<?php

namespace App\DataTables;

use App\Models\MemberGroup;
use App\Models\MemberGroupModel;
use App\Models\MemberGroupUserModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MemberGroupDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                $html = '';

                $html .= '<a href="/admin/membergroup/' . $data->id . '/edit" class="btn btn-xs btn-success" title="Update"><i class="icon-edit"   ></i> Edit</a>
                                ';


                $html .= '<a data-action="destroy" data-id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon-remove" ></i> Delete</a>
                                ';


                return $html;
            })
            ->editColumn('member_count', function ($data) {
                $members = MemberGroupUserModel::where("membergroup_id", $data->id)
                    ->join("users", "membergroup_user.user_id", "=", "users.id")->get();
                return '<span class="btn btn-default show_membergroup_member_modal"  data-id="' . $data->id . '">' . count($members) . '</span>';
            })
            ->rawColumns(['member_count', 'action'])
//            ->escapeColumns([])
//
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MemberGroupModel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $query = MemberGroupModel::query();
        $query = $query->select($this->getColumns());
//            ->leftjoin("regions", "regions.id", "=", "membergroups.region_id")
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {

        return $this->builder()
            ->setTableId('membergroup-table')
            ->columns($this->getColumnsFromOut())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
            );
    }

    protected function getColumns()
    {
        return [
            'membergroups.id',
            'membergroups.name',
            'membergroups.name as member_count',
        ];
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumnsFromOut()
    {
        return [
            Column::make('id')
                ->name("membergroups.id")
                ->title("ID"),
            Column::make('name')
                ->name('membergroups.name')
                ->title("Name"),


            Column::computed("member_count")
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title("MemberGroup members"),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MemberGroup_' . date('YmdHis');
    }
}
