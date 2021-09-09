<?php
    // session_start();

    include_once '../dbh.inc.php';

    $groupName = $_GET['groupName'];
    $groupLogo = 'groupLogo';
    $groupAdmin = $_SESSION['user_id'];
    $groupGame = $_GET['groupGame'];
    $inGroup = 1;

    $stmt = $mysql -> prepare('INSERT INTO `groups` (groupName, groupLogo, leader_id, groupGame)
                                 VALUES (?, ?, ?, ?)');
    $stmt -> bind_param('ssss', $groupName, $groupLogo, $groupAdmin, $groupGame);
    $stmt -> execute();
    $stmt -> close();

    $stmt = $mysql -> prepare('UPDATE `users` SET `inGroup` = ?, `userGroup` = ? WHERE `user_id` = ?');
    $stmt -> bind_param('iss', $inGroup, $groupName, $groupAdmin);
    $stmt -> execute();
    if ($stmt -> execute()) {
    }
    $stmt -> close();