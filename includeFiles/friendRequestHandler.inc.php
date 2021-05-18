<?php
 # barát kérelem elfogadva
if (isset($_POST['requestAccept'])) {
    include 'dbh.inc.php';

    $friendTwo = $_SESSION['userName'];
    $friendOne = $_POST['request_from'];
    $publicRequestID = $_POST['request_id'];

    $addFriend = $mysql -> prepare("INSERT INTO `friends` (`friendOne`, `friendTwo`)
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

    $userName = $_SESSION['userName'];
    $userPage = $_POST['user_id'];
    
    $deleteFriend = $mysql -> prepare("DELETE FROM `friends` WHERE (`friendOne` = ? OR `friendOne` = ?) 
                                        AND (`friendTwo` = ? OR `friendTwo` = ?)");
    $deleteFriend -> bind_param('ssss', $userPage, $userName, $userPage, $userName);
    $deleteFriend -> execute();
    $deleteFriend -> close();
    
    header('location: ../profile.php?userName='. $userPage);
    exit();
} else {
    header('location: ../profile.php');
    exit();
}