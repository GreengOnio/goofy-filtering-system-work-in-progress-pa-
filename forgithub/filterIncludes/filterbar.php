<?php
function timeSelect($name, $placeholder, $selected, $options) {
    echo "<select name=\"$name\"><option value=\"\">$placeholder</option>";
    foreach ($options as $val => $label) {
        $sel = ($selected == $val) ? "selected" : "";
        echo "<option value=\"$val\" $sel>$label</option>";
    }
    echo "</select>";
}
?>
<!-- MAIN CONTAINER -->
<div class="filterBox mb-4 mx-5">
    <!-- CONTAINER FOR DROPDOWN BUTTONS (MORE FILTERS / CLOSE) -->
    <div class="text-end" id="toggleFilters">
        <button type="button" class="btn btn-success moreLink my-2 collapsed" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false">
            <span class="label-closed">More Filters</span>
            <span class="label-open">Close</span>
        </button>
    </div>

    <form method="GET" action="schedules.php" id="advancedFilters" class="collapse">
        <!-- BUILDING -->
        <div class="fsection my-1">
            <!-- BUILDING LABEL -->
            <button type="button" class="section-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#secBuilding" aria-expanded="false">
                Building
            </button>
            <div id="secBuilding" class="collapse fbody">
                <div class="fbody-inner">
                    <div class="segmented">
                        <input type="radio" name="building" id="b_all" value="" <?= ((string)$building === "") ? "checked" : "" ?>>
                        <label for="b_all">All Buildings</label>

                        <?php foreach ($buildings as $b) { $id = (int)$b["building_id"]; ?>
                            <input type="radio" name="building" id="b_<?= $id ?>" value="<?= $id ?>" <?= ($building == $id) ? "checked" : "" ?>>
                            <label for="b_<?= $id ?>"><?= htmlspecialchars($b["building_name"]) ?></label>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROOM TYPE -->
        <div class="fsection my-1">
            <!-- ROOM TYPE LABEL -->
            <button type="button" class="section-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#secType" aria-expanded="false">
                Room Type
            </button>
            <div id="secType" class="collapse fbody">
                <div class="fbody-inner">
                    <select name="roomtype">
                        <option value="">All Room Types</option>
                        <?php foreach ($roomtypes as $r) { $id = (int)$r["roomtype_id"]; ?>
                            <option value="<?= $id ?>" <?= ($roomtype == $id) ? "selected" : "" ?>>
                                <?= htmlspecialchars($r["room_type"]) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- DAYS -->
        <div class="fsection my-1">
            <!-- DAYS SELECTION LABEL -->
            <button type="button" class="section-toggle collapsed"data-bs-toggle="collapse" data-bs-target="#secDays" aria-expanded="false">
                Days
            </button>
            <div id="secDays" class="collapse fbody">
                <div class="fbody-inner">
                    <div class="segmented">
                        <?php foreach ($days as $dayID => $dayName) { ?>
                            <input type="checkbox" name="day[]" id="d_<?= $dayID ?>" value="<?= $dayID ?>" <?= in_array($dayID, $selectedDays) ? "checked" : "" ?>>
                            <label for="d_<?= $dayID ?>"><?= $dayName ?></label>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- TIME RANGE -->
        <div class="fsection my-1">
            <!-- TIME RANGE LABEL -->
            <button type="button" class="section-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#secTime" aria-expanded="false">
                Time Range
            </button>
            <div id="secTime" class="collapse fbody">
                <div class="fbody-inner">
                    <?php timeSelect("start_time", "Start (any)", $startTime, $timeOptions); ?>
                    <span class="mx-1">to</span>
                    <?php timeSelect("end_time", "End (any)", $endTime, $timeOptions); ?>
                </div>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <input type="text" name="room" class="roomInput my-1" placeholder="room no. (e.g. 1106)" value="<?= htmlspecialchars($room) ?>">

        <div class="mt-3">
            <button type="submit" class="btn btn-success"> Search </button>
            <a href="schedules.php" class="btn btn-secondary"> Reset </a>
        </div>
    </form>
</div>

<script>
(function () {
    const start = document.querySelector('select[name="start_time"]');
    const end   = document.querySelector('select[name="end_time"]');
    if (!start || !end) return;

    function sync() {
        // clear a selection that has become invalid
        if (start.value && end.value && end.value <= start.value) {
            end.value = "";
        }

        const s = start.value, e = end.value;

        for (const o of end.options) {
            o.disabled = o.value !== "" && s !== "" && o.value <= s;
        }
        for (const o of start.options) {
            o.disabled = o.value !== "" && e !== "" && o.value >= e;
        }
    }

    start.addEventListener("change", sync);
    end.addEventListener("change", sync);
    sync();   // also applies the greying on page load
})();
</script>