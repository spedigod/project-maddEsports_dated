<?php 
     # session_start();
    if (!isset($_SESSION['userName'])) {
        header('location: login.php?error=loginRequired');
        exit();
    } else {
        $userName = $_SESSION['userName'];
        include 'includeFiles\main.includes.php';
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

     # get user level
    $getUserLevel = $mysql -> prepare("SELECT * FROM `userLevel` WHERE `userName` = ?");
    $getUserLevel -> bind_param('s', $userName);
    $getUserLevel -> execute();
    $getData = $getUserLevel -> get_result();
    if ($getData -> num_rows > 0) {
        while ($row = $getData -> fetch_assoc()) {
            $userLevel = $row['userLevel'];
            $userExp = $row['userExp'];
            $expPoints = $row['experiencePoints'];
        }
    }
    $getUserLevel -> close();

     # level up
    include 'includeFiles\levelSystem.inc.php';

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <title><?php echo $userPage; ?> | Profilja</title>
</head>
<body style="background: #011627">
    <?php 
        if ($userPage != $userName) {
            $getLevel = $mysql -> prepare("SELECT `userLevel` FROM `userLevel` WHERE `userName` = ?");
            $getLevel -> bind_param('s', $userPage);
            $getLevel -> execute();
            $getData = $getLevel -> get_result();
            if ($getData -> num_rows > 0) {
                while ($row = $getData -> fetch_assoc()) {
                    $userPageLevel = $row['userLevel'];
                }
            }

            echo '<table style="width: 100%; margin: 0;"><tr>
                    <td style="width: 10%;">'. '<img style="margin: 10px; margin-left: 20px; border-radius: 50%; width: 200px;" src="includeFiles\profilepicture.png" alt="tesztprofilkép">' .'</td>
                    <td style="vertical-align: bottom"><p style="margin-left: 20px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userPage .'</p></td>
                    <td style="vertical-align: bottom"><p style="float: right; margin-right: 10px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userPageLevel .'</p></td></tr></table>';
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
             #echo '<button><a href="includeFiles/logout.inc.php">Kilépés</a></button>';
             echo '<div style="width: 100%; margin-top: 5%; padding: 0 3% 0 3%;">
                        <table style="width: 100%; margin: 0;"><tr>
                                <td style="width: 10%;">'. '<img style="margin: 10px; margin-left: 20px; border-radius: 50%; width: 200px;" src="includeFiles\profilepicture.png" alt="tesztprofilkép">' .'</td>
                                <td style="vertical-align: bottom"><p style="margin-left: 20px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $_SESSION['userName'] .'</p></td>
                                <td style="vertical-align: bottom"><p style="float: right; margin-right: 10px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userLevel .'</p></td></tr> 
                                <tr>
                                <td colspan="3" style="width: 100% ">'. '<div class="progress" style="height: 30px !important; border-radius: 3px !important">
                                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: '. $userExpValue .'%" aria-valuenow="'. $userExpValue .'" aria-valuemin="0" aria-valuemax="'. $expPointsValue .'">'. $userExp .'xp / '. $expPoints .'xp</div>
                                
                                </div>' .'</td>
                        </tr></table>
                    </div>';
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
        } ?>
      
</body>
</html>
