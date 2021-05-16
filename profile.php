<?php 
    // session_start();
    if (!isset($_SESSION['userName'])) {
        header('location: login.php?error=loginRequired');
        exit();
    }
    echo $_GET['userName'];
    include_once 'includeFiles/dbh.inc.php';
    
    $userName = $_SESSION['userName'];
    if (isset($_GET['userName'])) {
        if ($_GET['userName'] == $_SESSION['userName']) {
            $userPage = $userName;
        }
        $userPage = $_GET['userName'];
        $validUserPage = $mysql -> prepare("SELECT * FROM users WHERE userName = ?");
        $validUserPage -> bind_param('s', $userPage);
        $validUserPage -> execute();
        $getData = $validUserPage -> get_result();
        if ($getData -> num_rows == 0) {
            $valid = false;
            header('location: profile.php');
        } else {
            $valid = true;
        }
        $validUserPage -> close();
    } else {
        $userPage = $userName;
    }

    $stmt = $mysql -> prepare('SELECT userName FROM users WHERE userName = ?');
    $stmt -> bind_param('s', $userPage);
    $stmt -> execute();
    $result = $stmt -> get_result();
    while ($row = $result -> fetch_assoc()) {
        $userPage = $row['userName'];
    }
    $stmt -> close();

    $friendRequest = $mysql -> prepare("SELECT publicRequestID FROM friendrequests WHERE requestFrom = ? AND requestTo = ?");
    $friendRequest -> bind_param('ss', $userName, $userPage);
    $friendRequest -> execute();
    $getData = $friendRequest -> get_result();
    if ($getData -> num_rows == 0) {
        $pending = false;
    } else {
        $pending = true;
        while ($row = $getData -> fetch_assoc()) {
            $ID = $row['publicRequestID'];
        }
    }
    $friendRequest -> close();

    $areFriends = $mysql -> prepare("SELECT * FROM `friends` WHERE (`friendOne` = ? OR `friendOne` = ?) AND (`friendTwo` = ? OR `friendTwo` = ?)");
    $areFriends -> bind_param('ssss', $userName, $userPage, $userName, $userPage);
    $areFriends -> execute();
    $getData = $areFriends -> get_result();
    if ($getData -> num_rows > 0) {
        $friends = true;
    } else {
        $friends = false;
    }
    $areFriends -> close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/default.css">
    <title><?php 
        if ($userName != $userPage) {
            echo $userPage. ' | Profilja';
        } else {
            echo 'Saját profil';
        }
    ?></title>
</head>
<body>
    <?php
        if ($userName != $userPage) {
            if ($_SESSION['inGroup'] == 1) {
                    echo '<td>';
                    echo '<form action="includeFiles/groupRequestHandler.inc.php" method="POST">';
                    echo '<input type="hidden" name="user_id" value='. $userPage .'>';
                    echo '<input type="hidden" name="group_id" value='. $userGroupName .'>';
                    echo '<input type="submit" value="groupInvite">';
                    echo '</form>';
                    echo '</td>';
            }
            if ($pending == 1) {
                echo '<p> Barátkérelem elküldve</p>';
            }
            if ($pending == 0) {
                if ($friends == 1) {
                    echo $userPage .' már a barátod';
                    echo '<form action="includeFiles/friendDelete.inc.php" method="post">
                            <input type="hidden" value="'. $userPage .'" name="user_id" />
                            <button type="submit" name="friendDelete">Barát törlése</button>
                        </form>';
                }
                if ($friends == 0) {
                    echo '<form action="includeFiles/friendRequest.inc.php" method="post">
                            <input type="hidden" value="'. $userPage .'" name="user_id" />
                            <button type="submit" name="friendRequest">Barátnak jelölés</button>
                        </form>';
                }
            }
        }
        if ($userName == $userPage) {
            echo '<button><a href="includeFiles/logout.inc.php">Kilépés</a></button>';
            $requests = $mysql -> prepare("SELECT * FROM friendrequests WHERE `requestTo` = ?");
            $requests -> bind_param('s', $userName);
            $requests -> execute();
            $getData = $requests -> get_result();
            if ($getData -> num_rows > 0) {
                $notifications = $getData -> num_rows;
                while ($row = $getData -> fetch_assoc()) {
                    echo '<p>'. $row['requestFrom'] .' barátnak jelölt 
                        <form action="includeFiles/friendRequestHandler.inc.php" method="POST">
                            <input type="hidden" value="'. $row['publicRequestID'] .'" name="request_id" />
                            <input type="hidden" value="'. $row['requestFrom'] .'" name="request_from" />
                            <button type="submit" name="requestAccept">Elfogad</button>
                            <button type="submit" name="requestDeny">Elutasít</button>
                        </form>
                    ';
                }
            }
            $requests -> close();
        }?>
</body>
</html>