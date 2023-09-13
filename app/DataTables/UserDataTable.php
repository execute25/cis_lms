<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\UserModel;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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

                $html .= '<a href="/admin/user/' . $data->id . '/edit" class="btn btn-xs btn-success" title="Update"><i class="icon-edit"   ></i> Edit</a>
                                ';

                $html .= '<a data-action="destroy" data-id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="icon-remove" ></i> Delete</a>
                                ';


                return $html;
            })
            ->addColumn('role', function ($data) {
                $user = UserModel::find($data->id);
                $role = $user->getRoleNames()->toArray();

                if (!empty($role))
                    return UserModel::getRolesList()[$role[0]];

                return "";
            })
            ->editColumn('department', function ($data) {
                return UserModel::getDepartmentList()[$data->department];
            });

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserModel $model)
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
            ->setTableId('user-table')
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
            Column::computed('role')
                ->title("Admin Level"),
            Column::make('name')
                ->title("Name"),
            Column::make('korean_name')
                ->title("Korean Name"),
            Column::make('email')
                ->title("Email"),
            Column::make('department')
                ->title("Department"),
            Column::make('phone')
                ->title("Cell"),
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
        return 'User_' . date('YmdHis');
    }
}
