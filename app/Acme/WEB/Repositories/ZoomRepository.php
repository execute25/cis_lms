<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\CourseModel;
use App\Models\RegionModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class ZoomRepository
{


    public function __construct()
    {
        $setting = SettingModel::first();
        $this->ACCOUNT_ID = $setting->zoom_account_id;
        $this->CLIENT_ID = $setting->zoom_client_id;
        $this->CLIENT_SECRET = $setting->zoom_client_secret;
        $this->REDIRECT_URI = $setting->redirect_url;
    }

    public function makeMeetingHadler($meeting_id, $next_page = "")
    {
        $lection = $this->lectionRepo->geCurrentLectionByMeetingId($meeting_id);


        if (!$lection) {
            Log::info("Lection not found 1");
            Log::info("Meeting " . $meeting_id);
            return;
        }


        $meeting_attend_data = $this->lastMeetingAttendData($meeting_id, $next_page, $lection);

        if ($lection) {
            $this->meetingAttendTimeHandler($meeting_attend_data['duration_array'], $lection);
            $this->meetingJoinLeaveDataHandler($meeting_attend_data['in_out_array'], $lection);


            if ($meeting_attend_data['next_page_token'] != "")
                $this->makeMeetingHadler($meeting_id, $meeting_attend_data['next_page_token']);

        } else {
            Log::info("Lection not found");
        }

    }

    public function lastMeetingAttendData($meeting_id, $next_page_token = "", $lection)
    {

        $duration_array = [];
        $in_out_array = [];
        $course = CourseModel::where("zoom_link", $meeting_id)->first();


        Log::info(json_encode($course));
        Log::info($meeting_id);
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

//        $response = $client->request('GET', '/v2/past_meetings/82358994661/instances', [
        $response = $client->request('GET', '/v2/metrics/meetings/' . $meeting_id . '/participants', [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken($course),

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
            $norm_join_time = date("Y-m-d H:i:s", strtotime($join_time));
            if ($lection->begin_at > $norm_join_time || $lection->end_at < $norm_join_time) {
                Log::info("Zoom data not for current lection. Late firing");
                Log::info($norm_join_time);
                break;
            }


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

    public function getZoomAccessToken()
    {
        $client_id = $this->CLIENT_ID;
        $client_secret = $this->CLIENT_SECRET;


        $client = new \GuzzleHttp\Client([
            'form_params' => [
                'account_id' => $this->ACCOUNT_ID,
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

    public function getMeetingJoinLink($meeting_id, $user, $course)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us',
        ]);


        $response = $client->request('POST', '/v2/meetings/' . $meeting_id . '/registrants', [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken($course),
//                "Content-Type" => "multipart/form-data",

            ],

            'json' => [
                'first_name' => $user->admin_level == 20 ? $user->korean_name : $user->name,
                'last_name' => $user->student_number != "" ? "(" . $user->student_number . ")" : '.',
                'email ' => $user->zoom_email,
                'auto_approve ' => true,

            ]
        ]);

        $data = json_decode($response->getBody());
        $meeting_url = isset($data->join_url) ? $data->join_url : '';
        return $meeting_url;
    }


    private function meetingAttendTimeHandler(array $meeting_attend_data, $lection)
    {

        foreach ($meeting_attend_data as $email => $duration) {
            $user = UserModel::where("zoom_email", $email)->where("admin_level", 50)->first();
            if (!$user)
                continue;

            $user_lection = $this->lectionRepo->getOrCreateLectionUserByLectionId($lection, $user->id);
            $user_lection->attend_duration = $user_lection->attend_duration < $duration ? $duration : $user_lection->attend_duration;

            if ($duration >= (30 * 60))
                $user_lection->is_open = 1;

            $user_lection->save();
        }


    }

    private function meetingJoinLeaveDataHandler(array $join_leave_data, $lection)
    {
        if (count($join_leave_data) == 0)
            return;

        $lection_group = $this->getLectionGroup($join_leave_data, $lection);

        foreach ($join_leave_data as $join_leave_item) {
            $email = $join_leave_item["email"];
            $user = UserModel::where("zoom_email", $email)->where("admin_level", 50)->first();
            if (!$user)
                continue;

            $norm_join_time = date("Y-m-d H:i:s", strtotime($join_leave_item["join_time"]));
            $norm_leave_time = date("Y-m-d H:i:s", strtotime($join_leave_item["leave_time"]));

            $zoom_data = ZoomLectionDataModel::where("user_id", $user->id)
                ->where("lection_id", $lection->id)
                ->where("join_time", $norm_join_time)->first();

            if ($zoom_data)
                continue;

            ZoomLectionDataModel::create([
                "user_id" => $user->id,
                "lection_id" => $lection->id,
                "join_time" => $norm_join_time,
                "leave_time" => $norm_leave_time,
                "group_number" => $lection_group,
                "duration" => $join_leave_item["duration"],
            ]);
        }
    }

    private function getLectionGroup(array $join_leave_data, $lection)
    {
        $join_time = date("Hi", strtotime($join_leave_data[0]["join_time"]));

        $lection_group = LectionStartEndTimeModel::where("course_id", $lection->course_id)
            ->where("start_at", "<=", $join_time)
            ->where("end_at", ">=", $join_time)
            ->first();

        if ($lection_group)
            return $lection_group->group_number;

        return 0;
    }

    public function createMeeting($training)
    {
        $accessToken = $this->getZoomAccessToken();
        $traning_date_time = $training->start_at . " " . $training->start_at_time;
        $date_time = new \DateTime($traning_date_time);
        $start_time = $date_time->format('Y-m-d\TH:i:s\Z');

        $apiEndpoint = 'https://api.zoom.us/v2/users/me/meetings'; // Replace with your user ID or 'me' for the authenticated user

        $meetingData = [
            'topic' => $training->name,
            'type' => 2, // 2 for scheduled meeting
            'start_time' => $start_time, // Replace with your desired start time
            'duration' => 120, // Meeting duration in minutes
            'settings' => [
                'join_before_host' => false,
                'registration_type' => 2, // 2 for registration required
                'approval_type' => 1, // 2 for automatic approval
//                'waiting_room' => false
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


    public function updateMeeging($training)
    {
        $accessToken = $this->getZoomAccessToken();

        $meetingId = $training->zoom_conference_id;
        $apiEndpoint = "https://api.zoom.us/v2/meetings/$meetingId";

        $meetingData = [
            'topic' => $training->name, // Replace with the new meeting name
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


    public function deleteMeeting($training)
    {
        $accessToken = $this->getZoomAccessToken();

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
}
