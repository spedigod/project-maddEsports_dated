<?php
    if (!isset($_POST['groupInvite'])) {
        header('location: userList');
        exit();
    }

    include 'dbh.inc.php';
    // session_start();
    $userName = $_SESSION['userName'];
    $invitedUser = $_POST['user_id']; 
    $groupName = $_POST['group_id'];

    $userStatus = $mysql -> prepare("SELECT `inGroup` FROM `users` WHERE `userName` = ?");
    $userStatus -> bind_param('s', $invitedUser);
    $userStatus -> execute();
    $getResult = $userStatus -> get_result();
    if ($getResult -> num_rows > 0) {
        while ($row = $getResult -> fetch_assoc()) {
            $userInGroup = $row['inGroup'];
            if ($userInGroup == 1) {
                header('location: ../userList');
            }
            if ($userInGroup == 0) {
                $inviteUser = $mysql -> prepare("INSERT INTO `grouprequests` (`invitedUser`, `invitedBy`, `groupName`) 
                                                VALUES (?, ?, ?)");
                $inviteUser -> bind_param('sss', $invitedUser, $userName, $groupName);
                $inviteUser -> execute();
                $inviteUser -> close();
            }
        }
    }
    $userStatus -> close();