<?php

namespace App\DataTables;

use App\Models\Cell;
use App\Models\CellModel;
use App\Models\CellUserModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CellDataTable extends DataTable
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

                $html .= '<a href="/admin/cell/' . $data->id . '/edit" class="btn btn-xs btn-success" title="Update"><i class="icon-edit"   ></i> Edit</a>
                                ';


                $html .= '<a data-action="destroy" data-id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon-remove" ></i> Delete</a>
                                ';


                return $html;
            })
            ->editColumn('member_count', function ($data) {
                $members = CellUserModel::where("cell_id", $data->id)->join("users", "cell_user.user_id", "=", "users.id")->get();
                return '<span class="btn btn-default show_cell_member_modal"  data-id="' . $data->id . '">' . count($members) . '</span>';
            })
            ->rawColumns(['member_count', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CellModel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CellModel $model)
    {
        return $model
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('cell-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            Column::make('id'),
            Column::make('name')
                ->title("Name"),
            Column::make('leader_id')
                ->title("Leader Name"),

            Column::computed("member_count")
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title("Cell members"),
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
        return 'Cell_' . date('YmdHis');
    }
}
