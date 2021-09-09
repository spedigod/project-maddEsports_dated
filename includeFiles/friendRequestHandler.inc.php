<?php
 # barát kérelem elfogadva
if (isset($_POST['requestAccept'])) {
    include 'dbh.inc.php';

    $friendTwo = $_SESSION['user_id'];
    $friendOne = $_POST['request_from'];
    $publicRequestID = $_POST['request_id'];

    $addFriend = $mysql -> prepare("INSERT INTO `friends` (`friend1_id`, `friend2_id`)
                                        VALUES (?, ?)");
    $addFriend -> bind_param('ss', $friendOne, $friendTwo);
    $addFriend -> execute();
    $addFriend -> close();

    $requestAccept = $mysql -> prepare("DELETE FROM `friendrequests` WHERE publicRequestID = ? ");
    $requestAccept -> bind_param('s', $publicRequestID);
    $requestAccept -> execute();
    $requestAccept -> close();

    header('location: ../profile.php');
    exit();

 # barát kérelem elutasítva
} else if (isset($_POST['requestDeny'])) {
    include 'dbh.inc.php';

    $publicRequestID = $_POST['request_id'];

    $requestAccept = $mysql -> prepare("DELETE FROM `friendrequests` WHERE publicRequestID = ? ");
    $requestAccept -> bind_param('s', $publicRequestID);
    $requestAccept -> execute();
    $requestAccept -> close();

    header('location: ../profile.php');
    exit();

 # nem jóváhagyott belépés
} else if (isset($_POST['friendDelete'])) {
    include 'dbh.inc.php';

    $user = $_SESSION['user_id'];
    $userPage = $_POST['user_id'];
    
    $deleteFriend = $mysql -> prepare("DELETE FROM `friends` WHERE (`friend1_id` = ? OR `friend1_id` = ?) 
                                        AND (`friend2_id` = ? OR `friend2_id` = ?)");
    $deleteFriend -> bind_param('ssss', $userPage, $user, $userPage, $user);
    $deleteFriend -> execute();
    $deleteFriend -> close();
    
    header('location: ../profile.php?ID='. $user);
    exit();
} else {
    header('location: ../profile.php');
    exit();
}