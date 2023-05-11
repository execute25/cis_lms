<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\ClassRepository;
use Acme\WEB\Repositories\CourseRepository;
use Acme\WEB\Repositories\UserRepository;
use App\DataTables\UserDataTable;
use App\DataTables\UsersDataTable;
use App\Helpers\EaseEncrypt;
use App\Http\Requests\User\WEB\UserStore;
use App\Http\Requests\User\WEB\UserUpdate;
use App\Models\LoveCardModel;
use App\Models\UserClassModel;
use App\Models\UserLectionModel;
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
            ->with("admin_level_type", Request::get("admin_level_type", 1))
            ->render('admin.user.index', [
                "admin_level_type" => Request::get("admin_level_type", 1),
            ]);
    }

    public function create()
    {

        if (Request::get("admin_level_type") == 1) {
            $current_admin_level = [50];
        } elseif (Request::get("admin_level_type") == 2) {
            $current_admin_level = [51];
        } elseif (Request::get("admin_level_type") == 4) {
            $current_admin_level = [30, 25];
        } else {
            $current_admin_level = [0, 10, 20];
        }


        $classes = $this->classRepo->getOpenClasses();
        $this->layout->content = View::make('admin.user.create', [
            "admin_level_type" => Request::get("admin_level_type", 1),
            "current_admin_level" => $current_admin_level,
            "classes" => $classes,
        ]);
    }

    public function store(UserStore $request)
    {

        $user_inspect = $this->userRepo->getUserByEmail(Request::get("email"));

        if ($user_inspect)
            return Response::make('User Already Exist', 410);

        $user = $this->userRepo->createNewUser();

        $this->userRepo->uploadUserImage($user, Request::file());

        if (Request::has('password') && Request::get('password') != '')
            $user->password = $this->userRepo->createPassword(Request::get('password'));

        if (Request::has('class_id') && Request::get('class_id') != '' && $user->class_id != Request::get("class_id")) {
            $user->password = $this->userRepo->attachClassToUser($user, Request::get('class_id'));
        }


        $user_id_enc = EaseEncrypt::alphaID($user->id);

        $student_name_for_link = str_replace(" ", '_', $user->name);
        $user->material_link = url("/") . "/web/web_main?id=" . $user_id_enc . "&name=" . $student_name_for_link;
        $user->zoom_email = $user->id . "_user@gmail.com";

        $user->save();

        return Response::json($user);
    }

    public function edit($id)
    {
        $user = $this->userRepo->getUserById($id);


        if (Request::get("admin_level_type") == UserModel::USER_LEVEL_TYPE_STAFF) {
            $classes = $this->classRepo->getClassList();
        } else {
            $classes = [];
        }

        if (Request::get("admin_level_type") == UserModel::USER_LEVEL_TYPE_BB
            || Request::get("admin_level_type") == UserModel::USER_LEVEL_TYPE_CENTER) {
            $leaders = $this->userRepo->getLeaderList();
        } else {
            $leaders = [];
        }


        $this->layout->content = View::make('admin.user.edit')
            ->with('user', $user)
            ->with('classes', $classes)
            ->with('leaders', $leaders)
            ->with('admin_level_type', Request::get("admin_level_type", 1));
    }

    public function update(UserUpdate $request, $id)
    {

        $user_before_update = $this->userRepo->getUserById($id);

        if (Request::has('leader_id') && Request::get('leader_id') != '' && $user_before_update->leader_id != Request::get("leader_id")) {
            if (!Request::has("bb_end_reason") && $user_before_update->leader_id != 0)
                return Response::make('bb_end_reason not sent', 415);

            $leader = $this->userRepo->getUserById(Request::get("leader_id"));
            $this->userRepo->attachLeaderToUser($user_before_update, $leader);
        }

        $user = $this->userRepo->updateUser($id);
        $this->userRepo->uploadUserImage($user, Request::file());

        if (Request::has('password') && Request::get('password') != '' && Request::get('password-confirm') != "")
            $user->password = $this->userRepo->createPassword(Request::get('password'));


        if (Request::has('admin_level') && Auth::user()->admin_level <= Request::get('admin_level'))
            $user->admin_level = Request::get("admin_level");


        if (Request::has('class_id') && Request::get('class_id') != '' && $user_before_update->class_id != Request::get("class_id")) {
            $class = $this->classRepo->getClassById(Request::get("class_id"));
            $this->userRepo->addClassToLeaderHistory($user, $class);
        }

        $user_id_enc = EaseEncrypt::alphaID($user->id);

        $student_name_for_link = str_replace(" ", '_', $user->name);
        $user->material_link = url("/") . "/web/web_main?id=" . $user_id_enc . "&name=" . $student_name_for_link;

        $user->save();


//        if (Request::has('interests'))
//            $this->userRepo->attachInterestToUser($user, Request::get('interests'));


        return Response::json($user);
    }

    public function update_receiver_data()
    {
        $this->userRepo->updateReceiverData();

        return Response::make('', 200);
    }

    public function destroy($id)
    {

        $user = UserModel::find($id);


        if (Auth::user()->cannot('delete', $user))
            abort(401);

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


            $student_name = trim(mb_convert_encoding($data[3], 'UTF-8', 'UTF-8'));
            $email = trim(mb_convert_encoding($data[10], 'UTF-8', 'UTF-8'));


            if (!isset($student_name) || $student_name == "")
                continue;


            $users_inspect = UserModel::
            where('name', '=', trim($student_name))
                ->where('email', '=', trim($email))
                ->first();

            if ($users_inspect) { // If already created usser with current app_id and email just update
                $user = $users_inspect;
            } else {
                $user = new UserModel;
            }

            $user->fill(array(
                'department' => isset($data[1]) ? mb_convert_encoding($data[1], 'UTF-8', 'UTF-8') : '',
                'contacter_name' => isset($data[2]) ? mb_convert_encoding($data[2], 'UTF-8', 'UTF-8') : '',
                'name' => trim($student_name),
                'korean_name' => isset($data[4]) ? mb_convert_encoding($data[4], 'UTF-8', 'UTF-8') : '',
                'birth_date' => isset($data[5]) ? mb_convert_encoding($data[5], 'UTF-8', 'UTF-8') : '',
                'country' => isset($data[6]) ? mb_convert_encoding($data[6], 'UTF-8', 'UTF-8') : '',
                'available_lang' => isset($data[7]) ? mb_convert_encoding($data[7], 'UTF-8', 'UTF-8') : '',
                'available_lang_level' => isset($data[8]) ? mb_convert_encoding($data[8], 'UTF-8', 'UTF-8') : '',
                'phone' => isset($data[9]) ? mb_convert_encoding($data[9], 'UTF-8', 'UTF-8') : '',
                'email' => isset($data[10]) ? trim(mb_convert_encoding($data[10], 'UTF-8', 'UTF-8')) : '',
                'address' => isset($data[11]) ? mb_convert_encoding($data[11], 'UTF-8', 'UTF-8') : '',
                'job' => isset($data[12]) ? mb_convert_encoding($data[12], 'UTF-8', 'UTF-8') : '',
                'religion' => isset($data[13]) ? mb_convert_encoding($data[13], 'UTF-8', 'UTF-8') : '',
                'time_in_religion' => isset($data[14]) ? mb_convert_encoding($data[14], 'UTF-8', 'UTF-8') : '',
                'bb_lesson_count' => isset($data[15]) ? mb_convert_encoding($data[15], 'UTF-8', 'UTF-8') : '',
                'indo_name' => isset($data[16]) ? mb_convert_encoding($data[16], 'UTF-8', 'UTF-8') : '',
                'indo_phone' => isset($data[17]) ? mb_convert_encoding($data[17], 'UTF-8', 'UTF-8') : '',
                'teacher_name' => isset($data[18]) ? mb_convert_encoding($data[18], 'UTF-8', 'UTF-8') : '',
                'teacher_phone' => isset($data[19]) ? mb_convert_encoding($data[19], 'UTF-8', 'UTF-8') : '',
                'gender' => isset($data[20]) ? mb_convert_encoding($data[20], 'UTF-8', 'UTF-8') : '',
                'is_pastor' => isset($data[21]) ? mb_convert_encoding($data[21], 'UTF-8', 'UTF-8') : '',
                'leader_id' => isset($data[22]) ? mb_convert_encoding($data[22], 'UTF-8', 'UTF-8') : '',
                'student_number' => isset($data[23]) ? mb_convert_encoding($data[23], 'UTF-8', 'UTF-8') : '',
                'admin_level' => 50,
            ));
            $user->save();


            $user->admin_level = 50;
            $user->zoom_email = $user->id . "_user@gmail.com";
            $user_id_enc = EaseEncrypt::alphaID($user->id);

            $student_name_for_link = str_replace(" ", '_', $student_name);
            $user->material_link = url("/") . "/web/web_main?id=" . $user_id_enc . "&name=" . $student_name_for_link;

            $user->save();


            $class_user = UserClassModel::where("user_id", $user->id)
                ->where("class_id", mb_convert_encoding($data[0], 'UTF-8', 'UTF-8'))
                ->first();

            if (!$class_user)
                UserClassModel::create([
                    "user_id" => $user->id,
                    "class_id" => mb_convert_encoding($data[0], 'UTF-8', 'UTF-8'),
                    "in_at" => date("Y-m-d H:i:s"),
                ]);


            $lovecard = LoveCardModel::where("student_real_name", "like", $user->name . "%")->first();

            if ($lovecard) {
                $user->love_card_id = $lovecard->id;
                $user->save();
            }


        }

        fclose($file);

        return;

    }

    public function change_student_status()
    {
        $user = UserModel::find(Request::get("user_id"));
        if (!$user)
            return Response::make("", 411);

        $user->student_status = Request::get("student_status");
        $user->save();

        return $user;

    }


//    public function batch(\Acme\WEB\Repositories\UserRepository $userRepo)
//    {
//        if (!Request::hasFile('batch-file'))
//            return Response::make('', 400);
//        $file = Request::file('batch-file');
//        $csvData = file_get_contents($file);
//        $lines = explode(PHP_EOL, $csvData);
//
//
//        $list = array();
//        $i = 0;
//        foreach ($lines as $line) {
//            if ($i != 0 && $line != "")
//                $list[] = str_getcsv($line);
//            $i++;
//        }
//
//
//        foreach ($list as $data) {
//
//            if (!isset($data[0]))
//                continue;
//
//            try {
//                $users_inspect = UserModel::
//                where('email', '=', trim($data[4]))
//                    ->first();
//
//                if ($users_inspect) { // If already created usser with current app_id and email just update
//                    $user = $users_inspect;
//                } else {
//                    $user = new UserModel;
//                }
//
//                $user->fill(array(
//                    'name' => $data[1],
//                    'last_name' => $data[2],
//                    'korean_name' => $data[3],
//                    'email' => iconv('EUC-KR', 'UTF-8', $data[4]),
//                ));
//
//                $user->admin_level = $data[0];
//                $user->password = Hash::make(144000);
//                $user->save();
//
//
////                $user_class = $this->classRepo->getUserAttendClass($user->id);
////                if ($user_class) {
////                    $class = $this->classRepo->getClassById($user_class->class_id);
////                }
//
//                $class = $this->classRepo->getClassById($data[5]);
//
//
//                $this->classRepo->addUserToClass($class, $user->id);
//            } catch (Exception $e) {
//
//            }
//        }
//    }

}
