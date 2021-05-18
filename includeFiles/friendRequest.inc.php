<?php
    if (!isset($_POST['friendRequest'])) {
        header('location: ../profile.php');
        exit();
    }
    if (!isset($_SESSION['userName'])) {
        header('location: ../login.php?error=loginRequired');
        exit();
    }
    include 'functions/main.function.php';

    $requestFrom = $_SESSION['userName'];
    $requestTo = $_POST['user_id'];
    $publicRequestID = createUniqueID($mysql);

    $friendRequest = $mysql -> prepare('INSERT INTO `friendrequests` (`requestFrom`, `requestTo`, `publicRequestID`)
                                    VALUES (?, ?, ?)');
    $friendRequest -> bind_param('sss', $requestFrom, $requestTo, $publicRequestID);
    $friendRequest -> execute();
    if (!$friendRequest -> execute()) {
        echo("Error description: " . $mysql -> error);
    }
    $friendRequest -> close();

    header('location: ../profile.php?userName='. $requestTo);
    exit();
