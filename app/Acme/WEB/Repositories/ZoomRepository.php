<?php

namespace Acme\WEB\Repositories;

use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\ZoomAccountSettingModel;
use App\Models\ZoomMeetingLogModel;
use App\Models\ZoomTrainingDataModel;
use GuzzleHttp\Client;
use http\Env\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ZoomRepository
{


    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository)
    {
        $this->trainingRepository = $trainingRepository;
    }

    public function makeMeetingHadler($meeting_id, $next_page = "")
    {
        $setting = App::make("Acme\WEB\Repositories\SettingRepository");
        $training_category = App::make("Acme\WEB\Repositories\TrainingCategoryRepository");

        $training = $this->trainingRepository->getTrainingByMeetingID($meeting_id);
        $training_category = $training_category->getTrainingCategoryById($training->category_id);
        $zoom_setting = $setting->getSettingByEmail($training_category->zoom_host_email);


        if (!$training) {
            Log::info("Training not found 1");
            Log::info("Meeting " . $meeting_id);
            return;
        }


        $meeting_attend_data = $this->lastMeetingAttendData($meeting_id, $next_page, $zoom_setting);

        if ($training) {
            $this->meetingAttendTimeHandler($meeting_attend_data['duration_array'], $training);
            $this->meetingJoinLeaveDataHandler($meeting_attend_data['in_out_array'], $training);


            if ($meeting_attend_data['next_page_token'] != "")
                $this->makeMeetingHadler($meeting_id, $meeting_attend_data['next_page_token']);

        } else {
            Log::info("Lection not found");
        }

    }

    public function lastMeetingAttendData($meeting_id, $next_page_token = "", $zoom_setting)
    {

        $duration_array = [];
        $in_out_array = [];


        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

//        $response = $client->request('GET', '/v2/past_meetings/82358994661/instances', [
        $response = $client->request('GET', '/v2/metrics/meetings/' . $meeting_id . '/participants', [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken($zoom_setting),

            ],
            'query' => [
                'page_size' => 300,
                'type' => 'past',
                'next_page_token' => $next_page_token,

            ],
        ]);


        $data = json_decode($response->getBody());
        ZoomMeetingLogModel::create([
            "meeting_id" => $meeting_id,
            "data" => $response->getBody(),
        ]);

        foreach ($data->participants as $item) {

            if (!isset($item->email))
                continue;

            $leave_time = isset($item->leave_time) ? $item->leave_time : date("Y-m-d H:i:s", time());


            if (!isset($item->join_time))
                continue;

            $join_time = $item->join_time;

            // inspect is lection and zoom participant attend time matching. If not then lection not for this zoom data
//            $norm_join_time = date("Y-m-d H:i:s", strtotime($join_time));
//            if ($lection->begin_at > $norm_join_time || $lection->end_at < $norm_join_time) {
//                Log::info("Zoom data not for current lection. Late firing");
//                Log::info($norm_join_time);
//                break;
//            }


            $duration = strtotime($leave_time) - strtotime($join_time);

            if (!isset($duration_array[$item->email]))
                $duration_array[$item->email] = 0;


            $duration_array[$item->email] += $duration;

            $in_out_array[] = [   // Add zoom user lection data for know when and on which group user was attend
                "email" => $item->email,
                "join_time" => $join_time,
                "leave_time" => $leave_time,
                "duration" => $duration,
            ];
        }


        Log::info(json_encode($duration_array));
        return [
            "duration_array" => $duration_array,
            "in_out_array" => $in_out_array,
            "next_page_token" => $data->next_page_token
        ];
    }

    public function getZoomAccessToken($zoom_setting)
    {
        $client_id = $zoom_setting->zoom_client_id;
        $client_secret = $zoom_setting->zoom_client_secret;


        $client = new \GuzzleHttp\Client([
            'form_params' => [
                'account_id' => $zoom_setting->zoom_account_id,
                'grant_type' => "account_credentials",

            ],

        ]);

//        $response = $client->request('GET', '/v2/past_meetings/213123123/participants', [
        $response = $client->request('POST', 'https://zoom.us/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($client_id . ':' . $client_secret),
            ]
        ]);

        $data = json_decode($response->getBody());

        return $data->access_token;
    }


    private function meetingAttendTimeHandler(array $meeting_attend_data, $training)
    {

        foreach ($meeting_attend_data as $email => $duration) {
            $pattern = '/(\d+)@/';

            if (preg_match($pattern, $email, $matches1)) {
                $user_id = $matches1[1];
                $user = UserModel::find($user_id);
            } else {
                $user = null;
            }


            if (!$user)
                continue;

            $trainin_user = $this->trainingRepository->getOrCreateTrainingUserByTrainingId($training->id, $user->id);
            $trainin_user->attend_duration = $trainin_user->attend_duration < $duration ? $duration : $trainin_user->attend_duration;

//            if ($duration >= (30 * 60))
//                $trainin_user->is_open = 1;

            $trainin_user->save();
        }


    }

    private function meetingJoinLeaveDataHandler(array $join_leave_data, $training)
    {
        if (count($join_leave_data) == 0)
            return;

//        $lection_group = $this->getLectionGroup($join_leave_data, $lection);

        foreach ($join_leave_data as $join_leave_item) {

            $pattern = '/(\d+)@/';
            $email = $join_leave_item["email"];

            if (preg_match($pattern, $email, $matches1)) {
                $user_id = $matches1[1];
                $user = UserModel::find($user_id);
            } else {
                $user = null;
            }

//            $user = UserModel::where("zoom_email", $email)->where("admin_level", 50)->first();
            if (!$user)
                continue;

            $norm_join_time = date("Y-m-d H:i:s", strtotime($join_leave_item["join_time"]));
            $norm_leave_time = date("Y-m-d H:i:s", strtotime($join_leave_item["leave_time"]));

            $zoom_data = ZoomTrainingDataModel::where("user_id", $user->id)
                ->where("training_id", $training->id)
                ->where("join_time", $norm_join_time)->first();

            if ($zoom_data)
                continue;

            ZoomTrainingDataModel::create([
                "user_id" => $user->id,
                "training_id" => $training->id,
                "join_time" => $norm_join_time,
                "leave_time" => $norm_leave_time,
                "duration" => $join_leave_item["duration"],
            ]);
        }
    }

//    private function getLectionGroup(array $join_leave_data, $lection)
//    {
//        $join_time = date("Hi", strtotime($join_leave_data[0]["join_time"]));
//
//        $lection_group = LectionStartEndTimeModel::where("course_id", $lection->course_id)
//            ->where("start_at", "<=", $join_time)
//            ->where("end_at", ">=", $join_time)
//            ->first();
//
//        if ($lection_group)
//            return $lection_group->group_number;
//
//        return 0;
//    }

    public function createMeeting($training, $zoom_setting)
    {
        $accessToken = $this->getZoomAccessToken($zoom_setting);
        $traning_date_time = $training->start_at . " " . $training->start_at_time;
        $date_time = new \DateTime($traning_date_time);
        $start_time = $date_time->format('Y-m-d\TH:i:s\Z');

        $apiEndpoint = 'https://api.zoom.us/v2/users/me/meetings'; // Replace with your user ID or 'me' for the authenticated user

        $meetingData = [
            'topic' => $training->name,
            'type' => 2, // 2 for scheduled meeting
            'start_time' => $start_time, // Replace with your desired start time
            'duration' => 120, // Meeting duration in minutes
            'password' => "19840314",
            "recurrence" => [
                "type" => 2,  # 2 indicates a weekly recurrence
                "repeat_interval" => 1,  # Recurs every week
                "weekly_days" => "1",  # 1 indicates Monday; You can specify multiple days like "1,3,5" for Monday, Wednesday, and Friday
                "end_times" => 1  # Specify the number of occurrences
            ],
            'settings' => [
                'join_before_host' => false,
                'registration_type' => 2, // 2 for registration required
                'approval_type' => 1, // 2 for automatic approval
                'meeting_authentication' => false,
                'host_video' => false,
                'waiting_room' => true
            ],
        ];

        $client = new Client();

        $response = $client->request('POST', $apiEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $meetingData,
        ]);

        $meetingInfo = json_decode($response->getBody(), true);

        return $meetingInfo;
    }


    public function updateMeeting($training, $zoom_setting)
    {
        $accessToken = $this->getZoomAccessToken($zoom_setting);

        $meetingId = $training->zoom_conference_id;
        $apiEndpoint = "https://api.zoom.us/v2/meetings/$meetingId";

        $meetingData = [
            'topic' => \Illuminate\Support\Facades\Request::get("name"), // Replace with the new meeting name
        ];

        $client = new Client();

        $response = $client->request('PATCH', $apiEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $meetingData,
        ]);


        $updatedMeetingInfo = json_decode($response->getBody(), true);

        print_r($updatedMeetingInfo);
    }


    public function deleteMeeting($training, $zoom_setting)
    {
        $accessToken = $this->getZoomAccessToken($zoom_setting);

        $meetingId = $training->zoom_conference_id;
        $apiEndpoint = "https://api.zoom.us/v2/meetings/$meetingId";

        $client = new Client();

        $response = $client->request('DELETE', $apiEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

// Check the response status code to confirm deletion
        if ($response->getStatusCode() === 204) {
            echo 'Meeting deleted successfully.';
        } else {
            echo 'Failed to delete the meeting.';
        }
    }

    public function makeTrainingJoinZoomLink($training, $zoom_setting)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us',
        ]);

        $id_number = Auth::user()->id_number;
        $id_number = substr($id_number, 2);

        $response = $client->request('POST', '/v2/meetings/' . $training->zoom_conference_id . '/registrants', [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken($zoom_setting),
            ],


            'json' => [
                'first_name' => $id_number,
                'last_name' => Auth::user()->name,
                'email ' => Auth::user()->id . "@gmail.com",
                'auto_approve ' => true,

            ]
        ]);

        $data = json_decode($response->getBody());
        $meeting_url = isset($data->join_url) ? $data->join_url : '';
        return $meeting_url;
    }

}
