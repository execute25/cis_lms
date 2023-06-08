<?php

namespace App\DataTables;

use App\Models\TrainingCategory;
use App\Models\TrainingCategoryModel;
use App\Models\TrainingCategoryUserModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TrainingCategoryDataTable extends DataTable
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

                $html .= '<a href="/admin/training_category/' . $data->id . '/edit" class="btn btn-xs btn-success" title="Update"><i class="icon-edit"   ></i> Edit</a>
                                ';


                $html .= '<a data-action="destroy" data-id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon-remove" ></i> Delete</a>';


                return $html;
            })

            ->editColumn('training_count', function ($data) {
                $html = '';

                $html .= '<a href="/admin/training?category_id=' . $data->id . '" class="btn btn-xs btn-success" title=""><i class="icon-edit"   ></i> ' . $data->training_count . '</a>';


                return $html;
            })
            ->rawColumns(['member_count', 'action', 'training_count'])
//            ->escapeColumns([])
//
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TrainingCategoryModel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $query = TrainingCategoryModel::query();
        $query = $query->select($this->getColumns());
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
            ->setTableId('training_categorie-table')
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
            'training_categories.id',
            'training_categories.title',
            'training_categories.order',
            'training_categories.is_hidden',
            DB::raw("(SELECT COUNT(*) FROM trainings WHERE trainings.category_id = training_categories.id) as training_count")
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
                ->name("training_categories.id")
                ->title("ID"),
            Column::make('title')
                ->name('training_categories.title')
                ->title("Title"),
            Column::make("order")
                ->width(60)
                ->name('training_categories.orders')
                ->title("Order"),
            Column::make("is_hidden")
                ->name('training_categories.is_hidden')
                ->width(60)
                ->title("Is Hidden"),
            Column::make("training_count")
                ->width(60)
                ->title("Trainings"),
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
        return 'TrainingCategory_' . date('YmdHis');
    }
}
