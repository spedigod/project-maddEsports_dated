<?php

    include_once 'dbh.inc.php';
    // session_start();

    $stmt = $mysql -> prepare('SELECT * FROM users WHERE userName = ?');
    $stmt -> bind_param('s', $userName);
    $stmt -> execute();

    $result = $stmt -> get_result();
    while ($row = $result -> fetch_assoc()) {
        $userName = $row['userName'];
        $userEmail = $row['userEmail'];
        $userFirstName = $row['userFirstName'];
        $userLastName = $row['userLastName'];
        $userLevel = $row['userLevel'];
        $isAdmin = $row['isAdmin'];
        $inGroup = $_SESSION['inGroup'] = $row['inGroup'];
        $_SESSION['userGroup'] = $userGroup = $row['userGroup'];
    }
    $stmt -> close();

    if ($inGroup == 1) {
        $stmt = $mysql -> prepare('SELECT * FROM groups WHERE groupName = ?');
        $stmt -> bind_param('s', $userGroup);
        $stmt -> execute();

        $result = $stmt -> get_result();
        while ($row = $result -> fetch_assoc()) {
            $groupName = $row['groupName'];
            $groupLogo = $row['groupLogo'];
            $groupAdmin = $row['groupAdmin'];
            $groupGame = $row['groupGame'];
            $groupMember1 = $row['groupMember1'];
            $groupMember2 = $row['groupMember2'];
            $groupMember3 = $row['groupMember3'];
            $groupMember4 = $row['groupMember4'];
            $groupMember5 = $row['groupMember5'];
        }
        $stmt -> close();
    }

    $adminQuery = $mysql -> prepare("SELECT * From `administration` WHERE `adminEmail` = ?");
    $adminQuery -> bind_param('s', $userEmail);
    $adminQuery -> execute();
    $getData = $adminQuery -> get_result();
    if ($getData -> num_rows > 0) {
        while ($row = $getData -> fetch_assoc()) {
            $adminLevel = $row['adminLevel'];
        }
    }
    $adminQuery -> close();

