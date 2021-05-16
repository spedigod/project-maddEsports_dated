<?php

if (isset($_POST['requestAccept'])) {
    include 'dbh.inc.php';
    // session_start();
    $friendTwo = $_SESSION['userName'];
    $friendOne = $_POST['request_from'];
    $publicRequestID = $_POST['request_id'];

    $requestAccept = $mysql -> prepare("DELETE FROM `friendrequests` WHERE publicRequestID = ? ");
    $requestAccept -> bind_param('s', $publicRequestID);
    $requestAccept -> execute();
    $requestAccept -> close();

    $addFriend = $mysql -> prepare("INSERT INTO friends (`friendOne`, `friendTwo`)
                                        VALUES (?, ?)");
    $addFriend -> bind_param('ss', $friendOne, $friendTwo);
    $addFriend -> execute();
    $addFriend -> close();

    header('location: ../profile');
    exit();
}else if (isset($_POST['requestDeny'])) {
    include 'dbh.inc.php';
    $publicRequestID = $_POST['request_id'];

    $requestAccept = $mysql -> prepare("DELETE FROM `friendrequests` WHERE publicRequestID = ? ");
    $requestAccept -> bind_param('s', $publicRequestID);
    $requestAccept -> execute();
    $requestAccept -> close();

    header('location: ../profile');
    exit();
} else {
    header('location: ../profile');
    exit();
}