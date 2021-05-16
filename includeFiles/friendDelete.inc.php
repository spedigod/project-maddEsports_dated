<?php

    // session_start();

if (!isset($_SESSION['userName'])) {
    header('location: ../login/loginRequired');
    exit();
}
if (!isset($_POST['friendDelete'])) {
    header('location: ../profile');
    exit();
}

include 'dbh.inc.php';
$userName = $_SESSION['userName'];
$userPage = $_POST['user_id'];

$deleteFriend = $mysql -> prepare("DELETE * FROM `friends` WHERE (`friendOne` = ? OR `friendOne` = ?) 
                                    AND (`friendTwo` = ? OR `friendTwo` = ?)");
$deleteFriend -> bind_param('ssss', $userPage, $userName, $userPage, $userName);
$deleteFriend -> execute();
$deleteFriend -> close();

header('location: ../profile/'. $userPage);
exit();

