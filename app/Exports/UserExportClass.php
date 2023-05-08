<?php

namespace App\Exports;

use App\Models\UserModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExportClass implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UserModel::all();
    }


}
