<?php

include('config.php');
include_once('header.php');

$selectedDay = $_GET['day'] ?? [];

$sql = "SELECT 
            schedule_tbl.schedule_id,
            room_tbl.room_name,
            room_tbl.room_id,
            room_tbl.building_id,
            building_tbl.building_name,
            room_tbl.floor_id,
            classday_tbl.day,
            classday_tbl.classday_id,
            classhour_tbl.start_end,
            availabilitystatus_tbl.availability_status

        FROM schedule_tbl

        JOIN room_tbl
            ON schedule_tbl.room_id = room_tbl.room_id

        JOIN building_tbl
            ON room_tbl.building_id = building_tbl.building_id

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

    $dayList = implode(',', $selectedDay);

    $sql .= " WHERE schedule_tbl.classday_id IN ($dayList)";
}


$sql .= " ORDER BY
            room_tbl.room_id,
            classday_tbl.classday_id,
            classhour_tbl.classhour_id

          LIMIT 90";


$output = $conn->query($sql);

if (!$output) {
    die("SQL Error: " . $conn->error);
}


/* GROUP BY ROOM */

$rooms = [];

while ($row = $output->fetch_assoc()) {

    $rooms[$row['room_name']][] = $row;
}


/* DAYS */

$days = [
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday'
];

?>

<body>
    <div class="container" id="verymainCard">
        <!-- MAIN CONTAINER -->
        <div class="container mx-auto" id="maintableCard">

            <!-- HEADER -->
            <div id="header">
                <h2>Classroom Schedule Masterlist</h2>
            </div>

            <!-- FILTER OPTION -->
            <div class="filterSection">
                <div class="filterDay">
                    <form method="GET" action="index.php">
                        <div class="row">
                            <!-- MONDAY -->
                            <div class="col-2">
                                <label>
                                    <input type="checkbox"name="day[]" value="1" <?php if (in_array("1", $selectedDay)) echo "checked"; ?>>
                                    Monday
                                </label>
                            </div>

                            <!-- TUESDAY -->
                            <div class="col-2">
                                <label>
                                    <input type="checkbox"name="day[]"value="2" <?php if (in_array("2", $selectedDay)) echo "checked"; ?>>
                                    Tuesday
                                </label>
                            </div>

                            <!-- WEDNESDAY -->
                            <div class="col-2">
                                <label>
                                    <input type="checkbox" name="day[]" value="3" <?php if (in_array("3", $selectedDay)) echo "checked"; ?>>
                                    Wednesday
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <!-- THURSDAY -->
                            <div class="col-2">
                                <label>
                                    <input type="checkbox" name="day[]" value="4" <?php if (in_array("4", $selectedDay)) echo "checked"; ?>>
                                    Thursday
                                </label>
                            </div>

                            <!-- FRIDAY -->
                            <div class="col-2">
                                <label>
                                    <input type="checkbox" name="day[]" value="5" <?php if (in_array("5", $selectedDay)) echo "checked"; ?>>
                                    Friday
                                </label>
                            </div>


                            <!-- SATURDAY -->
                            <div class="col-2">
                                <label>
                                    <input type="checkbox" name="day[]" value="6" <?php if (in_array("6", $selectedDay)) echo "checked"; ?>>
                                    Saturday
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-success mt-2 mb-2" type="submit"> Search </button>
                    </form>
                </div>
            </div>

            <!-- CLASSROOM CARDS LAYOUT -->
            <div class="container mt-2 mb-2">
                <?php foreach ($rooms as $roomNumber => $roomData): ?>
                    <div class="card shadow-sm mb-4">
                        <!-- ROOM HEADER : SHOWS THE ROOM NUMBER OF EACH CARD -->
                        <div class="card-header bg-dark text-white">
                            <h4> Room <?php echo $roomNumber; ?> </h4>

                            <small>
                                <?php echo $roomData[0]['building_name']; ?>
                                • Floor <?php echo $roomData[0]['floor_id']; ?>
                            </small>

                        </div>


                        <!-- SCHEDULE -->

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-center">

                                    <thead class="table-light">

                                        <tr>

                                            <th>Time</th>

                                            <?php foreach ($days as $dayID => $dayName): ?>

                                                <?php
                                                if (
                                                    !empty($selectedDay) &&
                                                    !in_array(
                                                        (string)$dayID,
                                                        $selectedDay
                                                    )
                                                ) continue;
                                                ?>

                                                <th>
                                                    <?php echo $dayName; ?>
                                                </th>

                                            <?php endforeach; ?>

                                        </tr>

                                    </thead>


                                    <tbody>

                                    <?php
                                    // Create: [day][time] = status

                                    $schedule = [];

                                    foreach ($roomData as $row) {
                                        $schedule[
                                            $row['classday_id']
                                        ][$row['start_end']]
                                            = $row['availability_status'];
                                    }

                                    //Get time
                                    $times = [];

                                    foreach ($roomData as $row) {
                                        if (!in_array($row['start_end'], $times)) {
                                            $times[] = $row['start_end'];
                                        }
                                    }
                                    ?>

                                    <?php foreach ($times as $time): ?>
                                        <tr>
                                            <th class="table-light">
                                                <?php echo $time; ?>
                                            </th>
                                            
                                            <?php foreach ($days as $dayID => $dayName): ?>

                                                <?php

                                                if (
                                                    !empty($selectedDay) &&
                                                    !in_array(
                                                        (string)$dayID,
                                                        $selectedDay
                                                    )
                                                ) continue;

                                                $status =
                                                    $schedule[$dayID][$time]
                                                    ?? '—';

                                                $class = '';

                                                if ($status == 'Available') {
                                                    $class = 'schedule-available';
                                                }

                                                if ($status == 'Occupied') {
                                                    $class = 'schedule-occupied';
                                                }

                                                ?>

                                                <td class="<?php echo $class; ?>">

                                                    <?php echo $status; ?>

                                                </td>

                                            <?php endforeach; ?>

                                        </tr>

                                    <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>


<script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>