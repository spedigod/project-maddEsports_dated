<?php
    require_once 'dbh.inc.php';

    $stmt = $mysql -> prepare('SELECT * FROM `users` WHERE `user_id` = ?');
    $stmt -> bind_param('s', $_SESSION['user_id']);
    $stmt -> execute();

    $result = $stmt -> get_result();
    while ($row = $result -> fetch_assoc()) {
        $userName = $row['userName'];
        $userEmail = $row['userEmail'];
        $userFirstName = $row['userFirstName'];
        $userLastName = $row['userLastName'];
        $isAdmin = $row['isAdmin'];
        $inGroup = $_SESSION['inGroup'] = $row['inGroup'];
        if (!empty($row['userGroup'])) {
            $_SESSION['userGroup'] = $userGroup = $row['userGroup'];
        }
    }
    $stmt -> close();

    if ($inGroup == 1) {
        $stmt = $mysql -> prepare('SELECT * FROM `groups` WHERE `groupName` = ?');
        $stmt -> bind_param('s', $userGroup);
        $stmt -> execute();

        $result = $stmt -> get_result();
        while ($row = $result -> fetch_assoc()) {
            $groupName = $row['groupName'];
            $groupAdmin = $row['leader_id'];
            $groupLogo = $row['groupLogo'];
            $groupGame = $row['groupGame'];
            $groupMember1 = $row['member1_id'];
            $groupMember2 = $row['member2_id'];
            $groupMember3 = $row['member3_id'];
            $groupMember4 = $row['member4_id'];
            $groupMember5 = $row['member5_id'];
        }
        $stmt -> close();
    }

    if (file_exists('images\profileImage\profile.'. $_SESSION['user_id'] .'.png')) {
        $_SESSION['imageName'] = 'profile.'. $_SESSION['user_id'] .'.png';
    } else {
        $_SESSION['imageName'] = 'profileDefault.png';
    }

    $adminQuery = $mysql -> prepare("SELECT * From `administration` WHERE `user_id` = ?");
    $adminQuery -> bind_param('s', $_SESSION['user_id']);
    $adminQuery -> execute();
    $getData = $adminQuery -> get_result();
    if ($getData -> num_rows > 0) {
        while ($row = $getData -> fetch_assoc()) {
            $adminLevel = $row['adminLevel'];
        }
    }
    $adminQuery -> close();

