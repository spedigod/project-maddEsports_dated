<?php 
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php?error=loginRequired');
        exit();
    } else {
        $user_id = $_SESSION['user_id'];
        require 'includeFiles/main.includes.php';
        require 'includeFiles/profileQuery.inc.php';
    }

    if (isset($_GET['ID'])) {
        $user_id_get = $_GET['ID'];
    } else {
        header('location: profile.php?ID='. $user_id);
        exit();
    }

    $searchUserID = $mysql -> prepare("SELECT `userName` FROM `users` WHERE `user_id` = ?");
    $searchUserID -> bind_param('s', $user_id_get);
    $searchUserID -> execute();
    $getResult = $searchUserID -> get_result();
    if ($getResult -> num_rows > 0) {
        while ($row = $getResult -> fetch_assoc()) {
            $userName = $row['userName'];
        }
        $user_id = $user_id_get;
        $searchUserID -> close();

    } else {
        $searchUserName = $mysql -> prepare("SELECT `user_id` FROM `users` WHERE `userName` = ?");
        $searchUserName -> bind_param('s', $user_id_get);
        $searchUserName -> execute();
        $getData = $searchUserName -> get_result();
        if ($getData -> num_rows > 0) {
            while ($row = $getData -> fetch_assoc()) {
                $user_id = $row['user_id'];
            }
        } else {
            header('location: 404.php?error=userDoesNotExists');
            exit();
        }
        $searchUserName -> close();

    }

     # get user level
    $getUserLevel = $mysql -> prepare("SELECT * FROM `userlevel` WHERE `user_id` = ?");
    $getUserLevel -> bind_param('s', $user_id);
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

    if (file_exists('images\profileImage\profile.'. $user_id .'.png')) {
        $imageName = 'profile.'. $user_id .'.png';
    } else {
        $imageName = 'profile.default.png';
    }

     # user profile picture
    /*$queryImage = $mysql -> prepare("SELECT * FROM `profileimg` WHERE `user_id` = ?");
    $queryImage -> bind_param('s', $_SESSION['user_id']);
    $queryImage -> execute();
    $getResult = $queryImage -> get_result();
    if ($getResult -> num_rows > 0) {
        while ($row = $getResult -> fetch_assoc()) {
            $status = $row['status'];
            if ($status == 0) {
                $imageName = "profileDefault";
            } else {
                $imageName = "profile.". $user_id;
            }
        }
    }
    $queryImage -> close();
    */

     # are the two users friends?
    $areFriends = $mysql -> prepare("SELECT * FROM `friends` WHERE (`friend1_id` = ? OR `friend1_id` = ?) AND (`friend2_id` = ? OR `friend2_id` = ?)");
    $areFriends -> bind_param('ssss', $_SESSION['user_id'], $user_id, $_SESSION['user_id'], $user_id);
    $areFriends -> execute();
    $getData = $areFriends -> get_result();
    if ($getData -> num_rows > 0) {
        $friends = true;
    } else {
        $friends = false;
    }
    $areFriends -> close();

     # is there a pending friend request?
    $friendRequest = $mysql -> prepare("SELECT publicRequestID FROM friendrequests WHERE from_id = ? AND to_id = ?");
    $friendRequest -> bind_param('ss', $_SESSION['user_id'], $user_id);
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
?>

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
        
        <title><?php echo $userName; ?> | Profilja</title>
    </head>
    <body style="background: #011627">
        <?php 
            if ($_SESSION['user_id'] != $user_id) {
                $getLevel = $mysql -> prepare("SELECT `userLevel` FROM `userLevel` WHERE `user_id` = ?");
                $getLevel -> bind_param('s', $user_id);
                $getLevel -> execute();
                $getData = $getLevel -> get_result();
                if ($getData -> num_rows > 0) {
                    while ($row = $getData -> fetch_assoc()) {
                        $userPageLevel = $row['userLevel'];
                    }
                }

                echo '<table style="width: 100%; margin: 0;"><tr>
                        <td style="width: 10%;">'. '<img style="margin: 10px; margin-left: 20px; border-radius: 50%; width: 250px;" src="images/profileImage/'. $imageName .'" alt="user_profile_pic">' .'</td>
                        <td style="vertical-align: bottom"><p style="margin-left: 20px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userName .'</p></td>
                        <td style="vertical-align: bottom"><p style="float: right; margin-right: 10px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userPageLevel .'</p></td></tr></table>';
                if ($friends == true) {
                    echo '<p> Ez a felhasználó már a barátod </p>';
                    echo '<form action="includeFiles/friendRequestHandler.inc.php" method="post">
                            <input type="hidden" value="'. $user_id .'" name="to_id" />
                            <button type="submit" name="friendDelete">Barát törlése</button>
                            </form>';
                } elseif ($friends == false) {
                    if ($pending == true) {
                        echo '<p> Barátkérelem elküldve!</p>';
                        echo '<p>Barátkérelem visszavonása</p>';
                    } elseif ($pending == false) {
                        echo '<form action="includeFiles/friendRequest.inc.php" method="post">
                                <input type="hidden" value="'. $user_id .'" name="to_id" />
                                <button type="submit" name="friendRequest">Barátnak jelölés</button>';
                    }
                }
            } elseif ($user_id = $_SESSION['user_id']) {
                #echo '<button><a href="includeFiles/logout.inc.php">Kilépés</a></button>';
                echo '<div style="width: 100%; margin-top: 5%; padding: 0 3% 0 3%;">
                            <table style="width: 100%; margin: 0;"><tr>
                                    <td style="width: 10%;">'. '<img style="margin: 10px; margin-left: 20px; border-radius: 50%; width: 250px;" src="images/profileImage/'. $imageName .'" alt="tesztprofilkép">' .'</td>
                                    <td style="vertical-align: bottom"><p style="margin-left: 20px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userName .'</p></td>
                                    <td style="vertical-align: bottom"><p style="float: right; margin-right: 10px; margin-bottom: -10px; font-weight: 700; font-size: 60px; color: white">'. $userLevel .'</p></td></tr> 
                                    <tr>
                                    <td colspan="3" style="width: 100% ">'. '<div class="progress" style="height: 30px !important; border-radius: 3px !important">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: '. $userExpValue .'%" aria-valuenow="'. $userExpValue .'" aria-valuemin="0" aria-valuemax="'. $expPointsValue .'">'. $userExp .'xp / '. $expPoints .'xp</div>
                                    
                                    </div>' .'</td>
                            </tr></table>
                        </div>';

                        // profil módosítása
                        echo '<form action="profileEdit.php" method="POST">
                                <input type="hidden" value="'. $user_id .'" name="user_id" />
                                <button type="submit" name="requestAccept" style="margin: 20px; height: 30px !important; border-radius: 3px !important; border: none;">profil módosítása</button>
                            </form>';
            } ?>
        
    </body>
</html>
