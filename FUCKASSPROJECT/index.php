<?php
    include("config.php");
    include_once("header.php");
    
    /* DAYS */
    $days = [
        1 => "Monday",
        2 => "Tuesday",
        3 => "Wednesday",
        4 => "Thursday",
        5 => "Friday",
        6 => "Saturday"
    ];

    /* GET FILTER VALUES */
    $building = $_GET["building"] ?? "";
    $roomtype = $_GET["roomtype"] ?? "";
    $room = trim($_GET["room"] ?? "");
    $startTime = $_GET["start_time"] ?? "";
    $endTime = $_GET["end_time"] ?? "";
    $selectedDays = $_GET["day"] ?? array_keys($days);

    if (!is_array($selectedDays)) {
        $selectedDays = [$selectedDays];
    }

    $selectedDays = array_map("intval", $selectedDays);

    /* TIME FUNCTION */
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

    /* BUILD FILTER */
    $conditions = [];

    if ($building != "") {
        $conditions[] = "room_tbl.building_id = " . (int)$building;
    }

    if ($roomtype != "") {
        $conditions[] = "room_tbl.roomtype_id = " . (int)$roomtype;
    }

    if (!empty($selectedDays)) {
        $dayList = implode(",", $selectedDays);
        $conditions[] = "schedule_tbl.classday_id IN ($dayList)";
    }

    if ($room != "") {
        $safeRoom = $conn->real_escape_string($room);
        $conditions[] = "room_tbl.room_number LIKE '%$safeRoom%'";
    }

    $where = "";

    if (!empty($conditions)) {
        $where = "WHERE " . implode(" AND ", $conditions);
    }

    /* DROPDOWN DATA */
    $sqlBuildings = "SELECT building_id, building_name FROM building_tbl ORDER BY building_id";
    $sqlRoomtype = "SELECT roomtype_id, room_type FROM roomtype_tbl ORDER BY roomtype_id";
    $sqlTime = "SELECT start_end FROM classhour_tbl ORDER BY classhour_id";

    $buildingResult = $conn->query($sqlBuildings);

    $roomtypeResult = $conn->query($sqlRoomtype);

    $timeOptions = [];

    $timeResult = $conn->query($sqlTime);

    while ($row = $timeResult->fetch_assoc()) {
        $parts = explode("-", $row["start_end"]);
        if (count($parts) != 2) {
            continue;
        }
        $start = new DateTime(trim($parts[0]));
        $end = new DateTime(trim($parts[1]));
        $startValue = $start->format("H:i");
        $endValue = $end->format("H:i");
        $timeOptions[$startValue] = $start->format("g:i A");
        $timeOptions[$endValue] = $end->format("g:i A");
    }

    ksort($timeOptions);

    /* PAGINATION */
    $roomsPerPage = 5;

    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

    if ($page < 1) {
        $page = 1;
    }

    $sqlTotalrooms = "SELECT COUNT(DISTINCT schedule_tbl.room_id) AS total 
    FROM schedule_tbl JOIN room_tbl ON schedule_tbl.room_id = room_tbl.room_id $where";

    $countResult = $conn->query($sqlTotalrooms);

    $totalRooms = $countResult->fetch_assoc()["total"];

    $totalPages = max(1, ceil($totalRooms / $roomsPerPage));

    if ($page > $totalPages) {
        $page = $totalPages;
    }

    $offset = ($page - 1) * $roomsPerPage;

    /* GET ROOM IDS */
    $sqlRoomID = "SELECT DISTINCT schedule_tbl.room_id FROM schedule_tbl JOIN room_tbl 
    ON schedule_tbl.room_id = room_tbl.room_id $where ORDER BY schedule_tbl.room_id LIMIT $roomsPerPage OFFSET $offset";

    $roomResult = $conn->query($sqlRoomID);

    $roomIDs = [];
    while ($row = $roomResult->fetch_assoc()) {
        $roomIDs[] = $row["room_id"];
    }

    /* GET SCHEDULES */
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
            JOIN room_tbl
                ON schedule_tbl.room_id = room_tbl.room_id
            JOIN building_tbl
                ON room_tbl.building_id = building_tbl.building_id
            JOIN roomtype_tbl
                ON room_tbl.roomtype_id = roomtype_tbl.roomtype_id
            JOIN classday_tbl
                ON schedule_tbl.classday_id = classday_tbl.classday_id
            JOIN classhour_tbl
                ON schedule_tbl.classhour_id = classhour_tbl.classhour_id
            JOIN classtimeofday_tbl
                ON classhour_tbl.timeofday_id = classtimeofday_tbl.timeofday_id
            JOIN availabilitystatus_tbl
                ON schedule_tbl.availabilitystatus_id =
                availabilitystatus_tbl.availabilitystatus_id
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

        $sqlDisplay .= "
            ORDER BY
                room_tbl.room_id,
                classday_tbl.classday_id,
                classhour_tbl.classhour_id
        ";

        $result = $conn->query($sqlDisplay);

        while ($row = $result->fetch_assoc()) {
            if ($startMinutes !== null && $endMinutes !== null) {
                $parts = explode("-", $row["start_end"]);
                if (count($parts) != 2) {
                    continue;
                }
                $scheduleStart = timeToMinutes(trim($parts[0]));
                $scheduleEnd = timeToMinutes(trim($parts[1]));
                if (
                    $scheduleStart < $startMinutes ||
                    $scheduleEnd > $endMinutes
                ) {
                    continue;
                }
            }
            $rooms[$row["room_number"]][] = $row;
        }
    }

    /* PAGINATION LINK */
    function pageLink($page)
    {
        $params = $_GET;
        $params["page"] = $page;
        return "index.php?" . http_build_query($params);
    }
?>

<body>
    <!-- MAIN CONTAINER -->
    <div class="container mx-auto" id="maintableCard">
        <!-- PAGE HEADER -->
        <div id="header">
            <h1> Classroom Schedule Masterlist </h1>
        </div>

        <!-- FILTER SECTION -->
        <div class="filterSection">
            <!-- FORM FOR THE USER TO SELECT AND SUBMIT FITLER OPTIONS -->
            <form class="form mx-5" method="GET" action="index.php">

                <!-- BUILDING TYPE FILTER -->
                <div class="mb-3">
                    <label class="form-label"> <strong> Building </strong> </label>

                    <select name="building" class="form-select">
                        <option value=""> All Buildings </option>

                        <?php while ($row = $buildingResult->fetch_assoc()) { ?>
                            <option value="<?= $row["building_id"] ?>" <?= ($building == $row["building_id"]) ? "selected" : "" ?> >
                                <?= htmlspecialchars($row["building_name"]) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- ROOM TYPE FILTER -->
                <div class="mb-3">
                    <label class="form-label"> <strong> Room Type </strong> </label>
                    
                    <select name="roomtype" class="form-select">
                        <option value="">All Room Types</option>

                        <?php while ($row = $roomtypeResult->fetch_assoc()) { ?>
                            <option value="<?= $row["roomtype_id"] ?>" <?= ($roomtype == $row["roomtype_id"]) ? "selected" : "" ?> >
                                <?= htmlspecialchars($row["room_type"]) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- DAY FILTER -->
                <div class="filterDay">
                    <label class="form-label"> <strong> Day </strong> </label>

                    <div class="row">
                        <?php foreach ($days as $id => $name) { ?>
                            <div class="col-2">
                                <label>
                                    <input type="checkbox" name="day[]" value="<?= $id ?>" <?= in_array($id, $selectedDays) ? "checked" : "" ?> >
                                    <?= $name ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- CLASS TIME START / END FILTER -->
                    <div class="row mt-3">

                        <!-- CLASS START RANGE -->
                        <div class="col-md-4">
                            <label class="form-label"> <strong> Class Start </strong> </label>

                            <select name="start_time" class="form-select">
                                <option value=""> Starting Time </option>

                                <?php foreach ($timeOptions as $value => $label){ ?>
                                    <option value="<?= $value ?>" <?= ($startTime == $value) ? "selected" : "" ?> >
                                        <?= $label ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- CLASS END RANGE -->
                        <div class="col-md-4">
                            <label class="form-label"> <strong> Class End </strong> </label>

                            <select name="end_time" class="form-select">
                                <option value=""> Ending Time </option>

                                <?php foreach ($timeOptions as $value => $label) { ?>
                                    <option value="<?= $value ?>" <?= ($endTime == $value) ? "selected" : "" ?> >
                                        <?= $label ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- SEARCH BAR FOR SPECIFIC ROOM NUMBER -->
                    <div class="col-md-3 mt-3">
                        <label class="form-label"> <strong> Search Room Number </strong> </label>

                        <input type="text" name="room" class="form-control" placeholder="(e.g. 1106)" 
                        value="<?= htmlspecialchars($room) ?>" >
                    </div>

                    <!-- SEARCH BUTTON / APPLY FILTER BUTTON -->
                    <button type="submit" class="btn btn-success mt-3 mb-2"> Search </button>

                    <!-- RESET TO DEAULT BUTTON -->
                    <a href="index.php" class="btn btn-secondary mt-3 mb-2"> Reset </a>
                </div>
            </form>
        </div>

        <!-- -------------------------------------------------------------------------- -->
        <!-- PREVIOUS / NEXT PAGE BUTTONS -->
        <div class="d-flex justify-content-center gap-2 mt-3 mb-4">
            <!-- PREVIOUS PAGE BUTTON -->
            <?php if ($page > 1) { ?>
                <a href="<?= pageLink($page - 1) ?>" class="btn btn-secondary"> Previous </a>
            <?php } ?>

            <!-- CURRENT PAGE (OUT OF) TOTAL PAGES -->
            <div style="background-color:white; border-radius:8px; padding:8px;">
                Page <?= $page ?> of <?= $totalPages ?>
            </div>
            
            <!-- NEXT PAGE BUTTON -->
            <?php if ($page < $totalPages) { ?>
                <a href="<?= pageLink($page + 1) ?>" class="btn btn-success"> Next </a>
            <?php } ?>
        </div>

        <!-- -------------------------------------------------------------------------- -->
        <!-- CLASSROOM SCHEDULE CARD SECTION -->
        <div class="container-sm mt-2 mb-2">

            <?php if (!empty($rooms)) { ?>
                <?php foreach ($rooms as $roomName => $roomData) { ?>
                    <!-- ROOM SCHEDULE CARD CONTAINER -->
                    <div class="card shadow-sm mb-4">
                        <!-- CARDHEADER -->
                        <div class="container" id="schedcardHeader">
                            <!-- ROOM NUMBER NAME -->
                            <h2>
                                Room <?= htmlspecialchars($roomName) ?>
                            </h2>

                            <!-- ROOM INFORMATION : BUILDING | FLOOR | ROOM TYPE -->
                            <small>
                                <?= htmlspecialchars($roomData[0]["building_name"]) ?>
                                • 
                                Floor
                                <?= htmlspecialchars($roomData[0]["floor_id"]) ?>
                                •
                                <?= htmlspecialchars($roomData[0]["room_type"]) ?>
                            </small>
                        </div>

                        <!-- CARD BODY : CONTAINING RESPECTIVE ROOM'S COMPLETE SCHEDULE-->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">

                                    <!-- TABLE HEADER -->
                                    <thead class="table-light">
                                        <tr>
                                            <!-- TIME FIELD -->
                                            <th>Time</th>

                                            <!-- DAYS FROM MONDAY TO SATURDAY -->
                                            <?php foreach ($days as $dayID => $dayName) { ?>
                                                <?php
                                                if (!in_array($dayID, $selectedDays)) {
                                                    continue;
                                                }
                                                ?>
                                                <th><?= $dayName ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>

                                    <!-- TABLE BODY -->
                                    <tbody>
                                        <?php
                                            $schedule = [];
                                            $times = [];
                                            foreach ($roomData as $row) {
                                                $schedule[$row["classday_id"]]

                                                [$row["start_end"]] = $row["availability_status"];

                                                $times[$row["start_end"]] = true;
                                            }
                                            
                                            $times = array_keys($times);

                                            usort($times, function ($a, $b) {
                                                $aStart = timeToMinutes(
                                                    trim(explode("-", $a)[0])
                                                );
                                                $bStart = timeToMinutes(
                                                    trim(explode("-", $b)[0])
                                                );
                                                return $aStart <=> $bStart;
                                            });
                                        ?>

                                        <?php foreach ($times as $time) { ?>
                                            <tr>
                                                <!-- TIME FROM 7AM TO 10PM (TOTAL OF 15 ROWS) -->
                                                <td class="table-light">
                                                    <strong> <?= htmlspecialchars($time) ?> </strong>
                                                </td>

                                                <?php foreach ($days as $dayID => $dayName) { ?>
                                                    <?php
                                                    if (!in_array($dayID, $selectedDays)) {
                                                        continue;
                                                    }

                                                    $status =  $schedule[$dayID][$time] ?? "—";

                                                    $class = "";

                                                    if ($status == "Available") {
                                                        $class = "schedule-available";
                                                    }
                                                    
                                                    if ($status == "Occupied") {
                                                        $class = "schedule-occupied";
                                                    }
                                                    ?>

                                                    <td class="<?= $class ?>">
                                                        <?= htmlspecialchars($status) ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            <?php } else { ?>

                <div class="alert alert-danger text-center">
                    No classroom schedule found for the selected filters.
                </div>
                
            <?php } ?>
        </div>

        <!-- -------------------------------------------------------------------------- -->
        <!-- PREVIOUS / NEXT PAGE BUTTONS -->
        <div class="d-flex justify-content-center gap-2 mt-3 mb-4">
            <!-- PREVIOUS PAGE BUTTON -->
            <?php if ($page > 1) { ?>
                <a href="<?= pageLink($page - 1) ?>" class="btn btn-secondary"> Previous </a>
            <?php } ?>

            <!-- CURRENT PAGE (OUT OF) TOTAL PAGES -->
            <div style="background-color:white; border-radius:8px; padding:8px;">
                Page <?= $page ?> of <?= $totalPages ?>
            </div>
            
            <!-- NEXT PAGE BUTTON -->
            <?php if ($page < $totalPages) { ?>
                <a href="<?= pageLink($page + 1) ?>" class="btn btn-success"> Next </a>
            <?php } ?>
        </div>

    </div>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
