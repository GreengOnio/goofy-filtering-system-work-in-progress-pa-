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

?> 