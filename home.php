<?php
    // session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        include 'includeFiles\main.includes.php';
    } else {
        header('location: login.php');
    }
    require 'includeFiles/profileQuery.inc.php';
    require_once 'includeFiles\functions\main.function.php';

    $request = $mysql -> prepare("SELECT * FROM `friendrequests` WHERE `to_id` = ?");
            $request -> bind_param('s', $user_id);
            if (!$request -> execute()) {
                $mysql -> error;
            } else {
                $queryResult = $request -> get_result();
                if ($queryResult -> num_rows > 0) {
                    while ($row = $queryResult -> fetch_assoc()) {
                        $grabName = $mysql -> prepare("SELECT `userName` FROM `users` WHERE `user_id` = ?");
                        $grabName -> bind_param('s', $row['from_id']);
                        if (!$grabName -> execute()) {
                            $mysql -> error;
                        } else {
                            $grabName -> bind_result($fromUserName);
                            while ($grabName -> fetch()) {
                                $array = [
                                    'friendRequest_id' => $row['publicRequestID'],
                                    'fromUserName' => $fromUserName,
                                    'form' => "<form action='includeFiles/friendRequestHandler.inc.php' method='post'>
                                                <input type='hidden' name='request_from' value='{$row['from_id']}'>
                                                <input type='hidden' name='request_id' value='{$row['publicRequestID']}'>",
                                    'createdAt' => $row['createdAt']
                                ];
                            
                                
                                if (!isset($_SESSION['friendrequests']) || empty($_SESSION['friendrequests'])) {
                                    $_SESSION['friendrequests'] = [$array];
                                    $_SESSION['friendrequest'] = 1;
                                } else {
                                    $in_array = false;
                                    for ($a = 0; $a < (array_key_last($_SESSION['friendrequests']) + 1); $a++) { 
                                        if (in_array($row['publicRequestID'], $_SESSION['friendrequests'][$a])) {
                                            $in_array = true;
                                            break;
                                        }
                                    }
                                    if ($in_array = false) {
                                        array_unshift($_SESSION['friendrequests'], $array);
                                        $_SESSION['friendrequest'] += 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/default.css">
    <title><?php echo $userName. '  | Home' ?></title>
</head>
<body>
    <section>
        <p class="welcome"> Welcome, </p> 
        <p class="userName"> <?php echo $userName ?> </p>
    </section>
    <button><a href="includeFiles/logout.inc.php">Kilépés</a></button>
    <?php 
    if ($isAdmin == 1) {
        echo '<button><a href="admin/admin.php">Admin panel</a></button>';
    }
    ?>
    <button><a href="webshop.php">Webshop</a></button>
    <button><a href="profile.php?userName=<?php echo $userName; ?>">Profil</a></button>
    <button><a href="events.php">Események</a></button>
    <button><a href="team.php"><?php if ($_SESSION['inGroup'] == 1) {echo 'Csapatom';} else {echo 'Csapatok';} ?></a></button>
    <form action="includeFiles/friendRequest.inc.php" method="POST">
        <input type="text" name="submittedUserName" id="submittedUserName" placeholder="Add friend">
        <button type="submit" name="quickAddFriendRequest">ADD</button>
    </form>
    <section>
         <!-- dropdown menübe kell rakni -->
        <p><?php if (isset($_SESSION['friendrequest'])) { echo "{$_SESSION['friendrequest']} Baratkerelmed van!"; } else {echo "Nincs baratkerelmed"; } ?></p>
        <?php 
            if (isset($_SESSION['friendrequests'][0])) {
                for ($x = 0; $x < (array_key_last($_SESSION['friendrequests']) + 1); $x++) {
                    if (file_exists('images/profileImage/profile.'. $_SESSION['friendrequests'][$x]['fromUserName'] .'.png')) {
                        $imageURL = $_SESSION['friendrequests'][$x]['fromUserName'];
                    } else {
                        $imageURL = 'default';
                    }
                    $userPicture = "<img style='width:100px'src='images/profileImage/profile.{$imageURL}.png' alt='{$_SESSION['friendrequests'][$x]['fromUserName']}'>";
                    echo <<<FRIENDREQUEST
                            <div style="background-color:#000000; color:#ffffff">
                                <p>{$userPicture}</p>
                                <p>{$_SESSION['friendrequests'][$x]['friendRequest_id']}</p>
                                <p>{$_SESSION['friendrequests'][$x]['fromUserName']}</p>
                                <p>{$_SESSION['friendrequests'][$x]['form']}
                                    <input type='hidden' name='quickAdd' value='{$x}'>
                                    <button type='submit' name='friendRequestAcceptSubmit'>Accept</button>
                                    <button type='submit' name='friendRequestDenySubmit'>Deny</button>
                                </form> 
                                </p>
                            </div>
                        FRIENDREQUEST;
                }
            }
        ?>
        <table>
            <tr>
                <th>Friends</th>
            </tr>
            <?php 
            $friendList = $mysql -> prepare("SELECT * FROM `friends` WHERE `friend1_id` = ? OR `friend2_id` = ?");
            $friendList -> bind_param('ss', $user_id, $user_id);
            if (!$friendList -> execute()) {
                echo $mysql -> error;
            }
            $queryResult = $friendList -> get_result();
            if ($queryResult -> num_rows > 0) {
                while ($row = $queryResult -> fetch_assoc()) {
                    $friend_id = $row['friend1_id'];
                    switch ($friend_id) {
                        case $user_id:
                            $friend_id = $row['friend2_id'];
                            break;
                        default:
                            $friend_id = $row['friend1_id'];
                            break;
                    }
                    $grabFriendName = $mysql -> prepare("SELECT `userName` FROM `users` WHERE `user_id` = ?");
                    $grabFriendName -> bind_param('s', $friend_id);
                    if (!$grabFriendName -> execute()) {
                        $mysql -> error;
                    } 
                    $grabFriendName -> bind_result($friendName);
                    while ($grabFriendName -> fetch()) {
                        echo <<<FRIENDS
                                <th><a href="profile.php?ID={$friend_id}">{$friendName}</a></th></tr>
                            FRIENDS;
                    }
                    $grabFriendName -> close();

                }
            }  
            $friendList -> close();
        ?>
        </table>
         <!-- dropdown menübe kell rakni -->
        <p><?php if (isset($_SESSION['notification'])) { echo "{$_SESSION['notification']} új értesítése van!"; } else {echo "Nincs új értesítése!"; } ?></p>
        <?php 
            if (isset($_SESSION['notifications'][0])) {
                for ($i = 0; $i < (array_key_last($_SESSION['notifications']) + 1); $i++) {
                    if (str_contains($_SESSION['notifications'][$i]['notification_id'], "badge")) {
                        $additionalContent = "<img style='width:100px'src='images/achivements/{$_SESSION['notifications'][$i]['notification_id']}.png' alt='{$_SESSION['notifications'][$i]['notificationName']}'>";
                    } else {
                        $additionalContent = null;
                    }
                    echo <<<NOTIFICATION
                            <div style="background-color:#000000; color:#ffffff">
                                <p>{$additionalContent}</p>
                                <p>{$_SESSION['notifications'][$i]['notification_id']}</p>
                                <p>{$_SESSION['notifications'][$i]['notificationName']}</p>
                                <p>{$_SESSION['notifications'][$i]['notificationDescription']}</p>
                                <p>{$_SESSION['notifications'][$i]['createdAt']}</p>
                                
                                <form action="includeFiles/closeNotification.inc.php" method="post">
                                        <input type="hidden" name="arrayValue" value="{$i}">
                                        <button type="submit" name="closeNotification">x</button>
                                </form>
                                </div>
                        NOTIFICATION;
                }
            }
        ?>
    </section>
</body>
</html>