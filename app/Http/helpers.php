<?php

function isLessonNotStartYet($training): bool
{
    if (($training->start_at . " " . $training->start_at_time) > date("Y-m-d H:i", time()))
        return true;

    return false;
}

function isLessonInProgress($training): bool
{
    if (($training->end_at . " " . $training->end_at_time) >= date("Y-m-d H:i", time()) && (date("Y-m-d H:i", strtotime('-30 minutes', strtotime($training->start_at . " " . $training->start_at_time)))) <= date("Y-m-d H:i", time()))
        return true;

    return false;
}

function normalizeDate($date): string
{
    $days = array(
        'Sun', 'Mon', 'Tue', 'Wed',
        'Thu', 'Fri', 'Sat'
    );

    $norm_date = $days[(date('w', strtotime($date)))];
    $day = Lang::get($norm_date);

    return date('Y-m-d', strtotime($date)) . '(' . $day . ') ' . date('H:i', strtotime($date));
}

function getRemindTime($date, $type = 0)
{
    $desiredDate = \Carbon\Carbon::parse($date);
    $currentDate = \Carbon\Carbon::now();
    $timeRemaining = $currentDate->diffForHumans($desiredDate);

    if ($type == 0) {
        return Lang::get("Remind:") . " $timeRemaining  " . Lang::get("the start of the lecture");
    } else {
        return Lang::get("Remind:") . " $timeRemaining  " . Lang::get("replay opening");
    }
}

function dateTolocal($date, $format = "Y-m-d H:i:s")
{
    $userTimezone = Auth::user()->timezone;
    $timestamp = \Carbon\Carbon::parse($date, 'Asia/Seoul'); // Adjust the timestamp and timezone accordingly
    $timestamp->setTimezone($userTimezone);

    return $timestamp->format($format);
}

function isActiveSubTab($type)
{

    if ($type == "admin" && request()->route()->getPrefix() === '/admin') {
        return true;
    } else if ($type == "edu" && \Illuminate\Support\Facades\Route::is('upcoming_trainings')) {
        return true;
    } else {
        return false;
    }

}


function countTrainingMaterials($training)
{
    $count_materials = 0;
    for ($i = 1; $i <= 6; $i++) {
        if ($training->{"file_$i"} != "")
            $count_materials++;
    }

    return $count_materials;
}

function getFirstMaterial($training)
{
    $count_materials = 0;
    for ($i = 1; $i <= 6; $i++) {
        if ($training->{"file_$i"} != "")
            return $training->{"file_$i"};
    }

    return "";
}
