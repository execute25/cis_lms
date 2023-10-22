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
use App\Models\SettingModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
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

    public function update( $id)
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


        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) { // upload users

            $number++;

            if ($number == 1 || !isset($data[1]))
                continue;


            $cust = trim(mb_convert_encoding($data[1], 'UTF-8', 'UTF-8'));
            $cust = str_replace("중복_", "", $cust);

            if (preg_match('/([A-Z]+)/', $cust, $matches)) {
                $cust_prefix = $matches[0];
            } else {
                $cust_prefix = "";
            }

            if (preg_match('/\d+/', $cust, $matches)) {
                $cust_number = $matches[0];
            } else {
                $cust_number = "";
            }

            $region = isset($data[3]) ? trim(mb_convert_encoding($data[3], 'UTF-8', 'UTF-8')) : ''; // 지역
            $department = isset($data[10]) ? trim(mb_convert_encoding($data[10], 'UTF-8', 'UTF-8')) : ''; // 부서
            $korean_name = isset($data[10]) ? trim(mb_convert_encoding($data[11], 'UTF-8', 'UTF-8')) : ''; // 이름(KR)
            $name = isset($data[12]) ? trim(mb_convert_encoding($data[12], 'UTF-8', 'UTF-8')) : ''; // 이름(RU)
            $gender = isset($data[13]) ? trim(mb_convert_encoding($data[13], 'UTF-8', 'UTF-8')) : ''; // gender
            $birth = isset($data[14]) ? trim(mb_convert_encoding($data[14], 'UTF-8', 'UTF-8')) : ''; // 생년월일
            $id_number = isset($data[16]) ? trim(mb_convert_encoding($data[16], 'UTF-8', 'UTF-8')) : ''; // 고유번호
            $phone = isset($data[17]) ? trim(mb_convert_encoding($data[17], 'UTF-8', 'UTF-8')) : ''; // 전화번호
            $member_type = isset($data[18]) ? trim(mb_convert_encoding($data[18], 'UTF-8', 'UTF-8')) : ''; // 직분
            $register_type = isset($data[19]) ? trim(mb_convert_encoding($data[19], 'UTF-8', 'UTF-8')) : ''; // 등록상태
            $nationality = isset($data[20]) ? trim(mb_convert_encoding($data[20], 'UTF-8', 'UTF-8')) : ''; // 국적
            $country = isset($data[21]) ? trim(mb_convert_encoding($data[21], 'UTF-8', 'UTF-8')) : ''; // 국가
            $city = isset($data[22]) ? trim(mb_convert_encoding($data[22], 'UTF-8', 'UTF-8')) : ''; // 도시명
            $is_free_report = isset($data[23]) ? trim(mb_convert_encoding($data[23], 'UTF-8', 'UTF-8')) : ''; // 출결여부
            $is_free_report = $is_free_report != "" && $is_free_report != "중복" ? 1 : 0;
            $free_report_reason = isset($data[24]) ? trim(mb_convert_encoding($data[24], 'UTF-8', 'UTF-8')) : ''; // 사유/기간


            if (!isset($cust_number) || $cust_number == "")
                continue;

            $users_inspect = UserModel::
            where('cust_number', '=', $cust_number)
                ->first();

            if ($users_inspect) {
                $user = $users_inspect;
            } else {
                $user = new UserModel;

                if ($cust_number != "")
                    $user->cust_number = $cust_number;

                if ($cust_number != "")
                    $user->email = $cust_number;
            }


            if ($region != "")
                $user->cust_prefix = $cust_prefix;

            if ($region != "")
                $user->region = $region;

            if ($department != "")
                $user->department = $department;

            if ($korean_name != "")
                $user->korean_name = $korean_name;

            if ($name != "")
                $user->name = $name;

            if ($gender != "")
                $user->gender = $gender;

            if ($birth != "")
                $user->birth = $birth;

            if ($id_number != "")
                $user->id_number = $id_number;

            if ($phone != "")
                $user->phone = $phone;

            if ($member_type != "")
                $user->member_type = $member_type;

            if ($register_type != "")
                $user->register_type = $register_type;

            if ($nationality != "")
                $user->nationality = $nationality;

            if ($country != "")
                $user->country = $country;

            if ($city != "")
                $user->city = $city;

            if ($is_free_report != "")
                $user->is_free_report = $is_free_report;

            if ($free_report_reason != "")
                $user->free_report_reason = $free_report_reason;

            $user->save();

            $this->userRepo->attachRoleToUser($user, 'normal');
            $this->userRepo->attachToCell($user, $data);
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


    public function update_timezone()
    {
        $user = UserModel::find(Auth::user()->id);
        $user->timezone = Request::get("timezone");
        $user->save();
    }

    public function logout()
    {
        Auth::logout();

        return Redirect::to('/login');
    }


    public function login()
    {
        $find_admin_user = $this->userRepo->findAdminUserForLogin();
        if ($find_admin_user) {
            if (Hash::check(Request::get('password'), $find_admin_user->password)) {
                Auth::loginUsingId($find_admin_user->id);
                return redirect('/admin/user');
            } else {
                return back()->withInput()->withErrors(['email' => Lang::get("Invalid login credentials.")]);
            }
        }


        $setting = SettingModel::first();

        $find_normal_user = $this->userRepo->findNormalUserForLogin();
        if ($find_normal_user) {
            if ($setting->common_password == Request::get("password")) {
                Auth::loginUsingId($find_normal_user->id);

                $this->userRepo->emptyTimezoneHandler();
                return redirect('/web/training/upcoming_trainings');
            } else {
                return back()->withInput()->withErrors(['email' => Lang::get("Invalid login credentials")]);
            }
        }


        // Authentication failed, redirect back with error
        return back()->withInput()->withErrors(['email' => Lang::get('User not found')]);
    }
}
