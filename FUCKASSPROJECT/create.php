<?php
    include('config.php');
    include_once('header.php');

    $invalidMessage = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            // USERNAME
            $givenname    = $_POST["givenname"] ?? "";
            $surname     = $_POST["surname"] ?? "";

            // BIRTHDAY
            $birthday        = $_POST["birthday"] ?? "";

            // GENDER
            $gender       = $_POST["gender"] ?? "";

            // EMAIL
            $email        = $_POST["email"] ?? "";
                
            //ERROR MESSAGE POP UP WHEN THERE ARE MISSING INPUTS
            if(
                empty($_POST["givenname"]) || empty($_POST["surname"]) || empty($_POST["birthday"]) || empty($_POST["gender"]) || empty($_POST["email"]) 
            ){
                $invalidMessage = '<div class="alert alert-danger"> Please fill up all fields! </div>';
            }

            //SUCCESS MESSAGE POPS UP + RESULT DIV SHOWS UP CONTAINING ALL INPUTS FROM USER
            else {
                $invalidMessage = '<div class="alert alert-success"> Your data has been submitted! </div>';

                $sql = "INSERT INTO student_tbl (Given_name, Surname, Birthday, Gender, Email) VALUES ('$givenname', '$surname', '$birthday', '$gender', '$email')";

                if ($conn->query($sql) == TRUE){
                    header("Location: index.php");
                }

            }
        }
    }    
    ?>

<body>
    
<div class="container" id="verymainCard">

    <!-- MAIN CONTINER -->
    <div class="container mx-auto" id="mainCard">
        <!-- "CREATE A NEW ACCOUNT -->
        <div id="header">
            <h4>Create a new account</h4>
        </div>

            <?php echo $invalidMessage ?>

        <!-- MAIN FORM CONTAINER -->
        <div id="bodyCard">
            <form class="form-control" id="formCard" method="POST">

                <!-- USERNAME ROW / FIELDS -->
                <label class="fieldLables" for="fullnameRow">Username</label>
                <div class="row" id="fullnameRow">
                    <div class="col">
                        <input class="form-control" type="text" placeholder="Enter first name" name="givenname">
                    </div>
                    <div class="col">
                        <input class="form-control" type="text" placeholder="Enter last name" name="surname">
                    </div>
                </div>

                <!-- BIRTHDAY ROW / FIELDS -->
                <label class="fieldLables" for="birthdayRow">Birthday</label>
                <input type="date" name="birthday" class="form-select">

                <!-- GENDER FIELDS / ROW -->
                <label class="fieldLables" for="genderRow">Gender</label>
                <div class="row" id="genderRow">

                    <!-- MALE OPTION -->
                    <div class="col" id="genderRadios">
                        <div class="form-check form-check-reverse">
                            <label class="form-check-label" for="maleOption">Male</label>
                            <input class="form-check-input" type="radio" name="gender" id="maleOption" value="male">
                        </div>
                    </div>

                    <!-- FEMALE OPTION -->
                    <div class="col" id="genderRadios">
                        <div class="form-check form-check-reverse">
                            <label class="form-check-label" for="femaleOption">Female</label>
                            <input class="form-check-input" type="radio" name="gender" id="femaleOption" value="female">
                        </div>
                    </div>

                    <!-- OTHER OPTION -->
                    <div class="col" id="genderRadios">
                        <div class="form-check form-check-reverse">
                            <label class="form-check-label" for="otherOption">Other</label>
                            <input class="form-check-input" type="radio" name="gender" id="genderOption" value="other">
                        </div>
                    </div>
                </div>
                
                <!-- EMAIL ROW / FIELD -->
                <label class="fieldLables" for="emailRow">Email</label>
                <div class="row" id="mobilenumberRow">
                    <div class="col">
                        <input class="form-control" type="email" name="email" placeholder="Enter email">
                    </div>
                </div>

                <!-- CREATE ACCOUNT BUTTON -->
                <div class="col" id="buttonsRow" style="display: flex; justify-content: center; padding: 25px;">
                    <button class="btn" type="submit" style="border: solid 1px #fff8dc; color: white; background-color: rgba(255, 255, 255, 0.149);"> Create Account </button>
                </div>
            </form>
        </div>
    </div>

</div>

    <script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>
