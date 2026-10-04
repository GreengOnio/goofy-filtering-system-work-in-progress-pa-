<?php

    // GET FILTER VALUES
    $building = $_GET["building"] ?? "";
    $roomtype = $_GET["roomtype"] ?? "";
    $room = trim($_GET["room"] ?? "");
    $startTime = $_GET["start_time"] ?? "";
    $endTime = $_GET["end_time"] ?? "";
    $selectedDays = $_GET["day"] ?? array_keys($days);

    if (!is_array($selectedDays)) {
        $selectedDays = explode(",", $selectedDays);
    }

    $selectedDays = array_values(array_filter(array_map("intval", $selectedDays)));
    if (empty($selectedDays)) $selectedDays = array_keys($days);

    // TIME FUNCTION
    function timeToMinutes($time){
        if ($time == "") {
            return null;
        }
        $date = new DateTime($time);
        return ($date->format("H") * 60) + $date->format("i");
    }

    $startMinutes = timeToMinutes($startTime);
    $endMinutes = timeToMinutes($endTime);

    if ($startMinutes !== null && $endMinutes !== null && $endMinutes <= $startMinutes) {
        $startTime = "";
        $endTime = "";
        $startMinutes = null;
        $endMinutes = null;
    }
?>