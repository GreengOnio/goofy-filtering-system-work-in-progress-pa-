<?php
// PHP FILE FOR URL ORGANIZATION!!!!
// PLEASE DO NOT DELETE THIS FILE AND ".HTACCESS" FILES PLSP PLS PLS
// So instead of the website showing: localhost/github/schedules.php?building=1&roomtype=1&day%5B%5D=2
// The url will instead show: localhost/forgithub/building/main-building-1/room-type/lecture-room/day/monday-saturday
// SO PLS PLS PLS DO NOT THIS FILE AND ".HTACCESS"

$buildings = $buildingResult->fetch_all(MYSQLI_ASSOC);
$roomtypes = $roomtypeResult->fetch_all(MYSQLI_ASSOC);

function slugify($s) {
    return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($s)), '-');
}

/* Build the pretty URL from the current filter values */
function buildUrl($buildingId, $roomtypeId, array $dayIds, $start, $end, $room = "", $page = 1) {
    global $buildings, $roomtypes, $days;
    $path = "";

    foreach ($buildings as $x) {
        if ($buildingId !== "" && $x["building_id"] == $buildingId) {
            $path .= "building/" . slugify($x["building_name"]) . "/";
        }
    }
    foreach ($roomtypes as $x) {
        if ($roomtypeId !== "" && $x["roomtype_id"] == $roomtypeId) {
            $path .= "room-type/" . slugify($x["room_type"]) . "/";
        }
    }

    // days: leave out when none or all are selected
    if (count($dayIds) > 0 && count($dayIds) < count($days)) {
        $names = [];
        foreach ($days as $id => $name) {
            if (in_array($id, $dayIds)) $names[] = strtolower($name);
        }
        $path .= "day/" . implode("-", $names) . "/";
    }

    // time
    if ($start !== "") $path .= "start-time/$start/";
    if ($end !== "")   $path .= "end-time/$end/";

    $query = [];
    if ($room !== "") $query["room"] = $room;
    if ($page > 1)    $query["page"] = $page;
    $q = http_build_query($query);

    $path = rtrim($path, "/");
    if ($path === "") return "/forgithub/schedules.php" . ($q ? "?$q" : "");
    return "/forgithub/" . $path . ($q ? "?$q" : "");
}

/* Form submitted (plain ?building=..&day[]=..) -> redirect to the pretty URL */
$submitted = false;
foreach (["building", "roomtype", "day", "start_time", "end_time"] as $k) {
    if (isset($_GET[$k])) $submitted = true;
}

if (!isset($_GET["route"]) && $submitted) {
    $d = $_GET["day"] ?? [];
    if (!is_array($d)) $d = explode(",", $d);

    header("Location: " . buildUrl(
        $_GET["building"] ?? "",
        $_GET["roomtype"] ?? "",
        array_map("intval", $d),
        $_GET["start_time"] ?? "",
        $_GET["end_time"] ?? "",
        trim($_GET["room"] ?? "")
    ));
    exit;
}

/* Pretty URL visited -> fill $_GET so filtersystem.php works unchanged */
if (isset($_GET["route"])) {
    $seg = array_values(array_filter(explode("/", $_GET["route"]), "strlen"));

    for ($i = 0; $i < count($seg); $i++) {
        $key = $seg[$i];
        $val = $seg[$i + 1] ?? "";

        if ($key === "building") {
            foreach ($buildings as $x) {
                if (slugify($x["building_name"]) === $val) $_GET["building"] = $x["building_id"];
            }
            $i++;
        } elseif ($key === "room-type") {
            foreach ($roomtypes as $x) {
                if (slugify($x["room_type"]) === $val) $_GET["roomtype"] = $x["roomtype_id"];
            }
            $i++;
        } elseif ($key === "day") {
            $names = explode("-", $val);
            $ids = [];
            foreach ($days as $id => $name) {
                if (in_array(strtolower($name), $names)) $ids[] = $id;
            }
            if ($ids) $_GET["day"] = $ids;
            $i++;
        } elseif ($key === "start-time") {
            if (preg_match('/^\d{2}:\d{2}$/', $val)) $_GET["start_time"] = $val;
            $i++;
        } elseif ($key === "end-time") {
            if (preg_match('/^\d{2}:\d{2}$/', $val)) $_GET["end_time"] = $val;
            $i++;
        }
    }
}
?>