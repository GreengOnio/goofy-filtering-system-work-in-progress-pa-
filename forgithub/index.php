<?php
    include("filterIncludes/config.php");
    include_once("webtemplate/header.php");
    include("filterIncludes/filtersystem.php");
    include("filterIncludes/dropdowns.php");
?>

<div class="mainContainer">
    <!-- MAIN CONTAINER -->
    <div class="container mx-auto" id="maintableCard">
        <!-- PAGE HEADER -->
        <div id="header">
            <h1> Classroom Schedule Masterlist </h1>
        </div>

        <!-- FILTER SECTION -->
        <div class="filterSection">
            <!-- FORM FOR THE USER TO SELECT AND SUBMIT FITLER OPTIONS -->
            <form class="form mx-5" method="GET" action="schedules.php">

                <!-- BUILDING TYPE FILTER -->
                <div class="filterOption mt-3">
                    <label class="form-label"> <strong> Building </strong> </label><br>

                    <div class="segmented">
                        <input type="radio" name="building" id="building_all" value=""
                            <?= ($building === "") ? "checked" : "" ?> >
                        <label for="building_all">All Buildings</label>

                        <?php while ($row = $buildingResult->fetch_assoc()) { ?>
                            <input type="radio" name="building" id="building_<?= $row["building_id"] ?>" value="<?= $row["building_id"] ?>" <?= ($building == $row["building_id"]) ? "checked" : "" ?> >
                            <label for="building_<?= $row["building_id"] ?>">
                                <?= htmlspecialchars($row["building_name"]) ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <!-- ROOM TYPE FILTER -->
                <div class="filterOption mt-3">
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
                
                <!-- SEARCH BAR FOR SPECIFIC ROOM NUMBER -->
                <div class="filterOption col-md-2 mt-3">
                    <label class="form-label"> <strong> Search Room Number </strong> </label>

                    <input type="text" name="room" class="form-control" placeholder="(e.g. 1106)" 
                    value="<?= htmlspecialchars($room) ?>" >
                </div>

                <!-- BUTTON SECTION -->
                <div class="buttonSection">
                    <!-- SEARCH BUTTON / APPLY FILTER BUTTON -->
                    <button type="submit" class="btn btn-success mt-3 mb-2"> Search </button>

                    <!-- RESET TO DEAULT BUTTON -->
                    <a href="index.php" class="btn btn-secondary mt-3 mb-2"> Reset </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once("webtemplate/footer.php"); ?>
