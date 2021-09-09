<?php
    if (!isset($_POST['friendRequest'])) {
        header('location: ../profile.php');
        exit();
    }
    if (!isset($_SESSION['user_id'])) {
        header('location: ../login.php?error=loginRequired');
        exit();
    }
    include 'functions/main.function.php';
    require 'dbh.inc.php';

    $requestFrom = $_SESSION['user_id'];
    $requestTo = $_POST['to_id'];
    $publicRequestID = createUniqueID($mysql);

    $friendRequest = $mysql -> prepare('INSERT INTO `friendrequests` (`from_id`, `to_id`, `publicRequestID`)
                                    VALUES (?, ?, ?)');
    $friendRequest -> bind_param('sss', $requestFrom, $requestTo, $publicRequestID);
    $friendRequest -> execute();
    if (!$friendRequest -> execute()) {
        echo("Error description: " . $mysql -> error);
    }
    $friendRequest -> close();

    header('location: ../profile.php?ID='. $requestTo);
    exit();
