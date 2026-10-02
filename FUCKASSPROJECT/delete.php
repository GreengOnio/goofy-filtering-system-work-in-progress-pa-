<?php
    include('config.php');

    $invalidMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET"){

        $id = &_POST["id"];

        $sql = "DELETE FROM student_tbl WHERE Student_ID = $id";

        if ($conn->query($sql) == TRUE){
            header("Location: index.php");
        } else {
            $invalidMessage = '<div class="alert alert-danger"> Failed to delete data. </div>';
        }
    }
?>