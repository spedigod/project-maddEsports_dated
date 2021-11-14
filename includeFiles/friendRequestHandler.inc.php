<?php
 # barát kérelem elfogadva
 
if (isset($_POST['friendRequestAcceptSubmit'])) {
    include 'dbh.inc.php';

    $notificationType = 1;

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

    $updateUserData = $mysql ->prepare("UPDATE `userdata` SET `friendCount` = `friendCount` + 1 WHERE user_id = ? OR user_id = ?");
    $updateUserData -> bind_param('ss', $friendOne, $friendTwo);
    $updateUserData -> execute();
    $updateUserData -> close();

    $addNotification = $mysql -> prepare("INSERT INTO `usernotifications` (user_id, notification_type) VALUE(?, ?)");
    $addNotification -> bind_param('ss', $friendOne, $notificationType);
    $addNotification -> execute();
    $addNotification -> close();

    if (isset($_POST['quickAdd'])) {
        unset($_SESSION['friendrequests'][$_POST['quickAdd']]);
    }
    $_SESSION['friendrequest'] -= 1;
    header('location: ../home.php');
    exit();

 # barát kérelem elutasítva
} else if (isset($_POST['friendRequestDenySubmit'])) {
    include 'dbh.inc.php';

    $publicRequestID = $_POST['request_id'];

    $requestAccept = $mysql -> prepare("DELETE FROM `friendrequests` WHERE publicRequestID = ? ");
    $requestAccept -> bind_param('s', $publicRequestID);
    $requestAccept -> execute();
    $requestAccept -> close();

    if (isset($_POST['quickAdd'])) {
        unset($_SESSION['friendrequests'][$_POST['quickAdd']]);
    }
    $_SESSION['friendrequest'] -= 1;
    header('location: ../home.php');
    exit();

 # barát eltávolítása
} else if (isset($_POST['friendDelete'])) {
    include 'dbh.inc.php';

    $user = $_SESSION['user_id'];
    $userPage = $_POST['user_id'];
    
    $deleteFriend = $mysql -> prepare("DELETE FROM `friends` WHERE (`friend1_id` = ? OR `friend1_id` = ?) 
                                        AND (`friend2_id` = ? OR `friend2_id` = ?)");
    $deleteFriend -> bind_param('ssss', $userPage, $user, $userPage, $user);
    $deleteFriend -> execute();
    $deleteFriend -> close();

    $updateUserData = $mysql ->prepare("UPDATE `userdata` SET `friendCount` = `friendCount` - 1 WHERE user_id = ? OR user_id = ?");
    $updateUserData -> bind_param('ss', $friendOne, $friendTwo);
    $updateUserData -> execute();
    $updateUserData -> close();
    
    header('location: ../profile.php?ID='. $user);
    exit();
} else {
    header('location: ../profile.php');
    exit();
}