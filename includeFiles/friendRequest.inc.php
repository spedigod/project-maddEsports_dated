<?php

if (!isset($_POST['friendRequest'])) {
    header('location: ../profile');
    exit();
}

// session_start();

if (!isset($_SESSION['userName'])) {
    header('location: ../loginloginRequired');
    exit();
}

include 'functions/main.function.php';

$requestFrom = $_SESSION['userName'];
$requestTo = $_POST['user_id'];
$publicRequestID = createUniqueID($mysql);

$friendRequest = $mysql -> prepare('INSERT INTO friendrequests (`requestFrom`, `requestTo`, `publicRequestID`)
                                    VALUES (?, ?, ?)');
$friendRequest -> bind_param('sss', $requestFrom, $requestTo, $publicRequestID);
$friendRequest -> execute();
if (!$friendRequest -> execute()) {
    echo("Error description: " . $mysql -> error);
}
$friendRequest -> close();

header('location: ../profile/'. $requestTo);
exit();
