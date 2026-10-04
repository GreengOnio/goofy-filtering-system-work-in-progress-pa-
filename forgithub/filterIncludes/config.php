<?php

$SERVER_NAME = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$DB_NAME = "projecttest";

$conn = new mysqli($SERVER_NAME, $USERNAME, $PASSWORD, $DB_NAME,);


if ($conn->connect_error){
    die("Connection Failed");
} else {
    // connection success!!
}

// first time the system looks for after user submits the search form, so pls keep this here :3
$days = [
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday"
];

// NOTE: DO NOT DELETE ".HTACCESS" FILE!!!!!
// 
?> 