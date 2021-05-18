<?php 
     # session_start();
    if (!isset($_SESSION['userName'])) {
        header('location: login.php?error=loginRequired');
        exit();
    } else {
        include 'includeFiles/dbh.inc.php';
        $userName = $_SESSION['userName'];
    }

    if (isset($_GET['userName'])) {
        if ($_GET['userName'] == $_SESSION['userName']) {
            $userPage = $_SESSION['userName'];
            header('location: profile.php');
        } else {
            $userPage = $_GET['userName'];
        }
         # validate user
        $validUserName = $mysql -> prepare("SELECT * FROM `users` WHERE `userName` = ?");
        $validUserName -> bind_param('s', $userPage);
        $validUserName -> execute();
        $getData = $validUserName -> get_result();
        if ($getData -> num_rows == 0) {
            header('location: 404.php?err=userNotFound');
            exit();
        } else {
             # get any data from the user for userpage
        }
        $validUserName -> close();

    } else {
        $userPage = $_SESSION['userName'];
    }

     # are the two users friends?
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

    # is there a pending friend request?
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
    $friendRequest -> close(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $userPage; ?> | Profilja</title>
</head>
<body>
    <?php 
        if ($userPage != $userName) {
            if ($friends == true) {
                echo '<p> Ez a felhasználó már a barátod </p>';
                echo '<form action="includeFiles/friendRequestHandler.inc.php" method="post">
                        <input type="hidden" value="'. $userPage .'" name="user_id" />
                        <button type="submit" name="friendDelete">Barát törlése</button>
                        </form>';
            } elseif ($friends == false) {
                if ($pending == true) {
                    echo '<p> Barátkérelem elküldve!</p>';
                    echo '<p>Barátkérelem visszavonása</p>';
                } elseif ($pending == false) {
                    echo '<form action="includeFiles/friendRequest.inc.php" method="post">
                            <input type="hidden" value="'. $userPage .'" name="user_id" />
                            <button type="submit" name="friendRequest">Barátnak jelölés</button>';
                }
            }
        } elseif ($userPage = $userName) {
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