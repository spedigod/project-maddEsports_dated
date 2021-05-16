<?php
    // session_start();

    include_once '../dbh.inc.php';

    $groupName = $_GET['groupName'];
    $groupLogo = 'groupLogo';
    $groupGame = $_GET['groupGame'];
    $groupAdmin = $_SESSION['userName'];
    $inGroup = 1;

    $stmt = $mysql -> prepare('INSERT INTO groups (groupName, groupLogo, groupGame, groupAdmin)
                                 VALUES (?, ?, ?, ?)');
    $stmt -> bind_param('ssss', $groupName, $groupLogo, $groupGame, $groupAdmin);
    $stmt -> execute();
    $stmt -> close();

    $stmt = $mysql -> prepare('UPDATE users SET inGroup = ?, userGroup = ? WHERE userName = ?');
    $stmt -> bind_param('iss', $inGroup, $groupName, $groupAdmin);
    $stmt -> execute();
    if ($stmt -> execute()) {
    }
    $stmt -> close();