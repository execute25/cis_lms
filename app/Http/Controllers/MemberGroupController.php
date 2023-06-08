<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\MemberGroupRepository;
use App\DataTables\MemberGroupDataTable;
use App\Helpers\EaseEncrypt;
use App\Models\CellModel;
use App\Models\MemberGroupModel;
use App\Models\UserModel;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;
use function iconv;
use function redirect;

class MemberGroupController extends BaseController
{

    /**
     * @var MemberGroupRepository
     */
    private $membergroupRepo;
    protected $layout = 'layouts.master';

    public function __construct(MemberGroupRepository $membergroupRepo)
    {
        $this->membergroupRepo = $membergroupRepo;
    }

    public function index(MemberGroupDataTable $dataTable)
    {
        return $dataTable
            ->render('admin.membergroup.index', [
            ]);
    }

    public function create()
    {

        $this->layout->content = View::make('admin.membergroup.create', [
        ]);
    }

    public function store()
    {
        $membergroup = $this->membergroupRepo->createNewMemberGroup();

        return Response::json($membergroup);
    }

    public function edit($id)
    {
        $membergroup = $this->membergroupRepo->getMemberGroupById($id);

        $this->layout->content = View::make('admin.membergroup.edit')
            ->with('membergroup', $membergroup);
    }

    public function update($id)
    {
        $membergroup = $this->membergroupRepo->updateMemberGroup($id);
        return Response::json($membergroup);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        MemberGroupModel::destroy($id);

        return Response::make('', 200);
    }


    public function get_member_list($id)
    {
        $members = MemberGroupModel::find($id)->members()->get();
        return Response::json($members);
    }

    public function attach_member($id)
    {
        $user = UserModel::find(Request::get("member_id"));

        if (!$user->membergroups->contains($id)) {
            $user->membergroups()->attach($id);
        }

        return Response::make();
    }

    public function detach_member($id)
    {

        $user = UserModel::find(Request::get("member_id"));

        if ($user->membergroups->contains($id)) {
            $user->membergroups()->detach($id);
        }

        return Response::make();
    }

}
