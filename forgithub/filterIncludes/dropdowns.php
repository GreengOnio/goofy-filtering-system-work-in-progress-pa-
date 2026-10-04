<?php
    // display building, room type, time range selections in dropdown
    $sqlBuildings = "SELECT building_id, building_name FROM building_tbl ORDER BY building_id";
    $sqlRoomtype = "SELECT roomtype_id, room_type FROM roomtype_tbl ORDER BY roomtype_id";
    $sqlTime = "SELECT start_end FROM classhour_tbl ORDER BY classhour_id";

    // sql queries to display all selections
    $buildingResult = $conn->query($sqlBuildings);
    $roomtypeResult = $conn->query($sqlRoomtype);

    // sql query for time
    $timeOptions = [];
    $timeResult = $conn->query($sqlTime);

    while ($row = $timeResult->fetch_assoc()) {
        $parts = explode("-", $row["start_end"]);
        if (count($parts) != 2) {
            continue;
        }
        $start = new DateTime(trim($parts[0]));
        $end = new DateTime(trim($parts[1]));
        $timeOptions[$start->format("H:i")] = $start->format("g:i A");
        $timeOptions[$end->format("H:i")] = $end->format("g:i A");
    }

    // sort the time ranges in ascending roder
    ksort($timeOptions);
?>
