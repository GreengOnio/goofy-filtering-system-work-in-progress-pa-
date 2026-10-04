<?php
    // BUILDING FITER
    $conditions = [];

    if ($building != "") {
        $conditions[] = "room_tbl.building_id = " . (int)$building;
    }

    if ($roomtype != "") {
        $conditions[] = "room_tbl.roomtype_id = " . (int)$roomtype;
    }

    $dayList = implode(",", $selectedDays);
    if (!empty($selectedDays)) {
        $conditions[] = "schedule_tbl.classday_id IN ($dayList)";
    }

    if ($room != "") {
        $safeRoom = $conn->real_escape_string($room);
        $conditions[] = "room_tbl.room_number LIKE '%$safeRoom%'";
    }

    $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

    // PAGINATION
    $roomsPerPage = 5;
    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
    if ($page < 1) {
        $page = 1;
    }

    $sqlTotalrooms = "SELECT COUNT(DISTINCT schedule_tbl.room_id) AS total
        FROM schedule_tbl JOIN room_tbl ON schedule_tbl.room_id = room_tbl.room_id $where";
    $totalRooms = $conn->query($sqlTotalrooms)->fetch_assoc()["total"];

    $totalPages = max(1, ceil($totalRooms / $roomsPerPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    $offset = ($page - 1) * $roomsPerPage;

    // GET ROOM IDS
    $sqlRoomID = "SELECT DISTINCT schedule_tbl.room_id 
    FROM schedule_tbl
    JOIN room_tbl ON schedule_tbl.room_id = room_tbl.room_id $where
    ORDER BY schedule_tbl.room_id 
    LIMIT $roomsPerPage OFFSET $offset";

    $roomResult = $conn->query($sqlRoomID);
    $roomIDs = [];
    while ($row = $roomResult->fetch_assoc()) {
        $roomIDs[] = $row["room_id"];
    }

    // GET SCHEUDLES
    $rooms = [];

    if (!empty($roomIDs)) {
        $roomIDList = implode(",", $roomIDs);
        $sqlDisplay = "
            SELECT
                schedule_tbl.schedule_id,
                room_tbl.room_number,
                room_tbl.room_id,
                room_tbl.floor_id,
                building_tbl.building_name,
                roomtype_tbl.room_type,
                classday_tbl.day,
                classday_tbl.classday_id,
                classhour_tbl.start_end,
                availabilitystatus_tbl.availability_status
            FROM schedule_tbl
            JOIN room_tbl ON schedule_tbl.room_id = room_tbl.room_id
            JOIN building_tbl ON room_tbl.building_id = building_tbl.building_id
            JOIN roomtype_tbl ON room_tbl.roomtype_id = roomtype_tbl.roomtype_id
            JOIN classday_tbl ON schedule_tbl.classday_id = classday_tbl.classday_id
            JOIN classhour_tbl ON schedule_tbl.classhour_id = classhour_tbl.classhour_id
            JOIN classtimeofday_tbl ON classhour_tbl.timeofday_id = classtimeofday_tbl.timeofday_id
            JOIN availabilitystatus_tbl
                ON schedule_tbl.availabilitystatus_id = availabilitystatus_tbl.availabilitystatus_id
            WHERE schedule_tbl.room_id IN ($roomIDList)
        ";

        if (!empty($selectedDays)) {
            $sqlDisplay .= " AND schedule_tbl.classday_id IN ($dayList)";
        }
        if ($building != "") {
            $sqlDisplay .= " AND room_tbl.building_id = " . (int)$building;
        }
        if ($roomtype != "") {
            $sqlDisplay .= " AND room_tbl.roomtype_id = " . (int)$roomtype;
        }

        $sqlDisplay .= " ORDER BY room_tbl.room_id, classday_tbl.classday_id, classhour_tbl.classhour_id";

        $result = $conn->query($sqlDisplay);

        while ($row = $result->fetch_assoc()) {
            if ($startMinutes !== null && $endMinutes !== null) {
                $parts = explode("-", $row["start_end"]);
                if (count($parts) != 2) {
                    continue;
                }
                $scheduleStart = timeToMinutes(trim($parts[0]));
                $scheduleEnd = timeToMinutes(trim($parts[1]));
                if ($scheduleStart < $startMinutes || $scheduleEnd > $endMinutes) {
                    continue;
                }
            }
            $rooms[$row["room_number"]][] = $row;
        }
    }

    // PAGE TRAVERSING
    function pageLink($page)
    {
        global $building, $roomtype, $selectedDays, $startTime, $endTime, $room;
        return buildUrl($building, $roomtype, $selectedDays, $startTime, $endTime, $room, $page);
    }

    function renderPagination($page, $totalPages)
    {
        echo '<!-- pager v2 -->';
        // Always 8 items when there are more than 8 pages: numbers and "dots"
        if ($totalPages <= 8) {
            $items = range(1, $totalPages);
        } elseif ($page <= 4) {
            $items = array_merge(range(1, 6), ["dots", $totalPages]);
        } elseif ($page >= $totalPages - 3) {
            $items = array_merge([1, "dots"], range($totalPages - 5, $totalPages));
        } else {
            $items = array_merge([1, "dots"], range($page - 1, $page + 2), ["dots", $totalPages]);
        }

        echo '<div class="pager">';

        echo ($page > 1)
            ? '<a class="nav" href="' . pageLink($page - 1) . '"> Prev</a>'
            : '<span class="nav disabled"> Prev</span>';

        foreach ($items as $item) {
            if ($item === "dots") {
                echo '<span class="dots">…</span>';
            } elseif ($item == $page) {
                echo '<span class="current">' . $item . '</span>';
            } else {
                echo '<a href="' . pageLink($item) . '">' . $item . '</a>';
            }
        }

        echo ($page < $totalPages)
            ? '<a class="nav" href="' . pageLink($page + 1) . '">Next </a>'
            : '<span class="nav disabled"> Next </span>';

        echo '</div>';
    }
?>