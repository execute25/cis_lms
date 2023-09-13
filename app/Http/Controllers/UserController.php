<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\UserRepository;
use App\DataTables\UserDataTable;
use App\Helpers\EaseEncrypt;
use App\Http\Requests\User\CellStore;
use App\Http\Requests\User\CellUpdate;
use App\Http\Requests\User\UserStore;
use App\Models\CellUserModel;
use App\Models\LoveCardModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;
use function redirect;

class UserController extends BaseController
{

    /**
     * @var UserRepository
     */
    private $userRepo;
    protected $layout = 'layouts.master';

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable
            ->render('admin.user.index', [
            ]);
    }

    public function create()
    {
        $this->layout->content = View::make('admin.user.create', [
        ]);
    }

    public function store(UserStore $request)
    {
        $user = $this->userRepo->createNewUser();
        $this->userRepo->uploadUserImage($user, Request::file());

        if (Request::has('password') && Request::get('password') != '')
            $user->password = $this->userRepo->createPassword(Request::get('password'));
        $user->save();

        if (Request::filled('role'))
            $this->userRepo->attachRoleToUser($user);


        return Response::json($user);
    }

    public function edit($id)
    {
        $user = $this->userRepo->getUserById($id);

        $this->layout->content = View::make('admin.user.edit')
            ->with('user', $user)
            ->with('current_role', $user->getRoleNames()->toArray()[0])
            ->with('admin_level_type', Request::get("admin_level_type", 1));
    }

    public function update(CellUpdate $request, $id)
    {
        $user = $this->userRepo->updateUser($id);
        $this->userRepo->uploadUserImage($user, Request::file());

        if (Request::has('password') && Request::get('password') != '' && Request::get('password-confirm') != "")
            $user->password = $this->userRepo->createPassword(Request::get('password'));
        $user->save();

        if (Request::filled('role'))
            $this->userRepo->attachRoleToUser($user);

        return Response::json($user);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        UserModel::destroy($id);

        return Response::make('', 200);
    }

    public function web_main(CourseRepository $courseRepo)
    {

        if (Request::filled("id")) {
            $user_id = EaseEncrypt::alphaID(Request::get("id"), "d");

            $user = UserModel::find($user_id);

            Auth::login($user);
        }

        if (Auth::check() && Auth::user()->admin_level == 20)
            return redirect('/web/leader/lection_list');

        if (!Auth::check()) {
            return redirect('/login');
        }

        $class = $this->classRepo->getClassOfUser(Auth::user());
        $courses = $this->classRepo->getCourseWithLecions($class);

        $openVideoLectionCount = UserLectionModel::where('user_id', $user_id)->where("is_video_open", 1)->count();

        if (isset($courses[0])) {
            if ($courses[0]->zoom_link != "")
                $join_url = $this->getJoinUrl($courses[0]);
        }


        $this->layout = View::make('web.master');
        $this->layout->content = View::make('web.web_main', [
            'courses' => $courses,
            'class' => $class,
            'openVideoLectionCount' => $openVideoLectionCount,
            'join_url' => isset($join_url) ? $join_url : '',
            'inc_id' => Request::get("id", ""),
        ]);
    }

    public function getJoinUrl($course, $user = false)
    {
        $user = $user ? $user : Auth::user();

        if ($user->join_url != "")
            return Auth::user()->join_url;


        $zoomRepo = App::make('App\Http\Controllers\ZoomController');

        $join_url = $zoomRepo->getMeetingJoinLink($course->zoom_link, $user, $course);
        $user = UserModel::find($user->id);
        $user->join_url = $join_url;
        $user->save();
        return $join_url;

    }


    public
    function batch()
    {
        if (!Request::hasFile('batch-file'))
            return Response::make('', 400);
        $file = Request::file('batch-file');


        $file = fopen($file, "r");

        $number = 0;


        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {

            $number++;

            if ($number == 1 || !isset($data[3]))
                continue;


            $name = trim(mb_convert_encoding($data[0], 'UTF-8', 'UTF-8'));
            $email = trim(mb_convert_encoding($data[2], 'UTF-8', 'UTF-8'));


            if (!isset($name) || $name == "")
                continue;

            $users_inspect = UserModel::
            where('name', '=', trim($name))
                ->where('email', '=', trim($email))
                ->first();

            if ($users_inspect) {
                $user = $users_inspect;
            } else {
                $user = new UserModel;
            }

            $user->fill(array(
                'name' => isset($data[0]) ? mb_convert_encoding($data[0], 'UTF-8', 'UTF-8') : '',
                'korean_name' => isset($data[1]) ? mb_convert_encoding($data[1], 'UTF-8', 'UTF-8') : '',
                'email' => isset($data[2]) ? mb_convert_encoding($data[2], 'UTF-8', 'UTF-8') : '',
                'department' => isset($data[3]) ? mb_convert_encoding($data[3], 'UTF-8', 'UTF-8') : '',
            ));
            $user->save();

            $this->userRepo->attachRoleToUser($user, 'normal');
        }

        fclose($file);

        return;

    }


    public function get_user_list()
    {
        $users = UserModel::query();
        $users = $users
            ->where(function ($q) {
                $q->where("users.name", "like", "%" . urldecode(Request::get("q")) . "%")
                    ->orWhere("users.korean_name", "like", "%" . urldecode(Request::get("q")) . "%");

            })
            ->select("users.id", DB::raw("CONCAT(users.name,' (', users.korean_name , ')') as text")
            )
            ->get();

        return [
            "results" => $users,
            "pagination" => ["more" => false]
        ];


    }

    public function search_users()
    {
        $selected_members = CellUserModel::where("cell_id", Request::get("cell_id"))->pluck("user_id");

        $members = UserModel::query();
        $members = $members->where(function ($q) {
            $q->where("name", "like", "%" . urldecode(Request::get("search")) . "%")
                ->where("korean_name", "like", "%" . urldecode(Request::get("search")) . "%");
        })
            ->whereNotIn("users.id", $selected_members)
            ->get();

        return $members;


    }

}
