<?php

include('config.php');

$selectedDay = &_POST['day'] ?? [];

$sql = "SELECT
            schedule_tbl.schedule_id,
            room_tbl.room_number,
            classday_tbl.day,
            classhour_tbl.start_end,
            availabilitystatus_tbl.availability_status

        FROM schedule_tbl

        JOIN room_tbl
            ON schedule_tbl.room_id = room_tbl.room_id

        JOIN classday_tbl
            ON schedule_tbl.classday_id = classday_tbl.classday_id

        JOIN classhour_tbl
            ON schedule_tbl.classhour_id = classhour_tbl.classhour_id

        JOIN classtimeofday_tbl
            ON classhour_tbl.timeofday_id = classtimeofday_tbl.timeofday_id

        JOIN availabilitystatus_tbl
            ON schedule_tbl.availabilitystatus_id =
               availabilitystatus_tbl.availabilitystatus_id";


if (!empty($selectedDay)) {
    $dayList = implode(",", $selectedDay);
    $sql .= " WHERE schedule_tbl.classday_id IN ($dayList)";
}

$sql .= " ORDER BY
            room_tbl.room_id,
            classday_tbl.classday_id,
            classhour_tbl.classhour_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

?>