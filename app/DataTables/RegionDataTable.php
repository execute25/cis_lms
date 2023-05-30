<?php

namespace App\DataTables;

use App\Models\Region;
use App\Models\RegionModel;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RegionDataTable extends DataTable
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

                $html .= '<a href="/admin/region/' . $data->id . '/edit" class="btn btn-xs btn-success" title="Update"><i class="icon-edit"   ></i> Edit</a>
                                ';


                $html .= '<a data-action="destroy" data-id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon-remove" ></i> Delete</a>
                                ';


                return $html;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\RegionModel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RegionModel $model)
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
            ->setTableId('region-table')
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
            Column::make('korean_name')
                ->title("Korean Name"),
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
        return 'Region_' . date('YmdHis');
    }
}
