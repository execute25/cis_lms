<?php

namespace App\DataTables;

use App\Models\MemberGroupModel;
use App\Models\Training;
use App\Models\TrainingCategoryModel;
use App\Models\TrainingModel;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TrainingDataTable extends DataTable
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
            ->editColumn('start_at', function ($data) {

                return $data->start_at . " " . $data->start_at_time;
            })
            ->editColumn('include_groups', function ($data) {
                if ($data->include_groups == 0) {
                    return "All";
                } else {
                    $membergroup_id = explode(",", $data->include_groups);
                    $membergroup = MemberGroupModel::whereIn("id", $membergroup_id)->pluck("name");
                    return !empty($membergroup) ? implode(", ", $membergroup->toArray()) : "All";
                }

            })
            ->editColumn('category_id', function ($data) {
                if ($data->category_id == 0) {
                    return "";
                } else {
                    $category = TrainingCategoryModel::find($data->category_id);
                    return $category ? $category->title : "";
                }

            })
            ->editColumn('is_use_zoom', function ($data) {
                if ($data->is_use_zoom == 1)
                    return "Yes";

                return "No";

            })
            ->addColumn('action', function ($data) {
                $html = '';

                $html .= '<a href="/admin/training/' . $data->id . '/edit" class="btn btn-xs btn-success" title="Update"><i class="icon-edit"   ></i> Edit</a>
                                ';
                $html .= '<a data-action="destroy" data-id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon-remove" ></i> Delete</a>
                                ';


                return $html;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TrainingModel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TrainingModel $model)
    {
        if (Request::filled("category_id"))
            $model = $model->where("category_id", Request::get("category_id"));
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
            ->setTableId('training-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->buttons(
                Button::make('create')->action("window.location = '/admin/training/create?category_id=" . $this->category_id . "';"),
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
            Column::make('start_at')
                ->title("Start at"),
//            Column::make('category_id')
//                ->title("Category Title"),
            Column::make('bunny_id')
                ->title("Bunny video ID"),
            Column::make('include_groups')
                ->title("Member Group"),
            Column::make('is_use_zoom')
                ->title("Is Use Zoom Meeting"),
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
        return 'Training_' . date('YmdHis');
    }
}
