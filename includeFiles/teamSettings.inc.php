<?php
    if (isset($_SESSION['user_id'])) {
        include_once 'dbh.inc.php';
        require_once 'profileQuery.inc.php';
        require_once 'functions\main.function.php';

        $user_id = $_SESSION['user_id'];

        if(isset($_POST['image'])) {
            $team_id = $_SESSION['team_id'];

            $data = $_POST['image'];

            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);

            $data = base64_decode($image_array_2[1]);

            $imageName = '../images/teamImages/logo/logo.' . $team_id . '.png';

            file_put_contents($imageName, $data);

            unset($_SESSION['team_id']);
        }
    }
    header('location: ../profile.php?info=changesAreSaved');
    exit();