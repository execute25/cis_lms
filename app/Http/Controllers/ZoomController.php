<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\LectionRepository;
use Acme\WEB\Repositories\NoticeRepository;
use Acme\WEB\Repositories\ZoomRepository;
use App\DataTables\NoticeDataTable;
use App\Helpers\EaseDataTable;
use App\Models\CourseModel;
use App\Models\LectionModel;
use App\Models\LectionStartEndTimeModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\ZoomLectionDataModel;
use App\Models\ZoomMeetingLogModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class ZoomController extends Controller
{


    /**
     * @var ZoomRepository
     */
    private $zoomRepository;

    public function __construct(ZoomRepository $zoomRepository)
    {
        $this->zoomRepository = $zoomRepository;
    }

    public function redirect_zoom()
    {
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);

            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode($this->CLIENT_ID . ':' . $this->CLIENT_SECRET)
                ],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $_GET['code'],
                    "redirect_uri" => $this->REDIRECT_URI
                ],
            ]);

            $token = json_decode($response->getBody()->getContents(), true);
            echo $token;
//            $db = new DB();
//            $db->update_access_token(json_encode($token));
            echo "Access token inserted successfully.";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    public function zoom_webhook()
    {

        $payload = Request::get("payload");

//Log::info(json_encode([
//"plainToken" => $payload["plainToken"],
//"encryptedToken" => hash_hmac("sha256", $payload["plainToken"], "DG2y4CttRsOO4IwZhyikaQ")]));


        return Response::json([
            "plainToken" => $payload["plainToken"],
            "encryptedToken" => hash_hmac("sha256", $payload["plainToken"], "iaCdUEVOSvWOzKY07GrnpQ")
        ]);


        $event = Request::get("event");


        Log::info("Start");
        Log::info($event);
        Log::info(json_encode(Request::all()));

        if ($event == "meeting.ended") {
            $payload = Request::get("payload");

//            if (isset($payload->object->id)) {
            if (isset($payload['object']['id'])) {
                $allow_meeting_ids = CourseModel::where("zoom_link", "!=", "")->pluck("zoom_link")->toArray();
                $meeting_id = $payload['object']['id'];
                if (!in_array($meeting_id, $allow_meeting_ids)) {
                    Log::info("Meeting id not allow " . $meeting_id);
                    return;
                }
                $meeting_id = $payload['object']['id'];
                $this->zoomRepository->makeMeetingHadler($meeting_id);


            } else {
                Log::info("Meeting id not exist");
            }

        }


        return "";
    }



}
