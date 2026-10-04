<?php
    include("filterIncludes/config.php");
    include("filterIncludes/dropdowns.php");
    include("filterIncludes/routing.php");
    include("filterIncludes/filtersystem.php");
    include_once("webtemplate/header.php");
    include("filterIncludes/schedulequeries.php");
?>
    <!-- MAIN CONTAINER -->
    <div class="container mx-auto" id="maintableCard">

        <!-- FILTERING SECTION LINK -->
        <?php include("filterIncludes/filterbar.php"); ?>

        <!-- PREVIOUS / NEXT PAGE BUTTONS -->
        <?php renderPagination($page, $totalPages); ?>

        <!-- SCHEDULE CARDS SECTION -->
        <div class="container-sm mt-2 mb-2">
            <?php if (!empty($rooms)) { ?>
                <?php foreach ($rooms as $roomName => $roomData) { ?>

                    <!-- CARD CONTAINER -->
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
        <div class="pagerRow">
            <?php renderPagination($page, $totalPages); ?>
            <button type="button" class="backTop"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })">
                Back to top
            </button>
        </div>
    </div>

<?php include_once("webtemplate/footer.php"); ?>