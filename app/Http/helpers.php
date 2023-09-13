<?php

function isLessonNotStartYet($training): bool
{
    if (($training->start_at . " " . $training->start_at_time) > date("Y-m-d H:i", time()))
        return true;

    return false;
}

function isLessonInProgress($training): bool
{
    if (($training->end_at . " " . $training->end_at_time) > date("Y-m-d H:i", time()) && ($training->start_at . " " . $training->start_at_time) < date("Y-m-d H:i", time()))
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
