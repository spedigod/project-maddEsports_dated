<?php 
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php');
        exit();
    }
    if (!isset($_GET['ID'])) {
        header('location: events.php');
        exit();
    } else {
        include_once 'includeFiles/dbh.inc.php';
        $grabData = $mysql -> prepare("SELECT * FROM `events` WHERE `event_id` = ?");
        $grabData -> bind_param('s', $_GET['ID']);
        if (!$grabData -> execute()) {
            echo $mysql -> error;
        }
        $result = $grabData -> get_result();
        if ($result -> num_rows > 0) {
            while ($row = $result -> fetch_assoc()) {
                $eventTitle = $row['eventTitle'];
                $eventGame = $row['eventGame'];  
                $eventBanner = $row['eventBanner']; 
                $eventSmallDescription = $row['eventDescriptionS'];
                $eventDescription = $row['eventDescription'];
                $checkIn = $row['checkIn'];
                $dateOfStart = $row['dateOfStart'];
                $prizePool = $row['prizePool'];
                $eventSettings = $row['eventSettings'];
                $eventBackground = $row['eventBackground'];
                $eventAdmin = $row['creator_id'];
                $eventStatus = $row['eventStatus'];
            }
        }
        $grabData -> close();
    }

    $getUserInfo = $mysql -> prepare("SELECT `userName`, `isAdmin` FROM `users` WHERE `user_id` = ?");
    $getUserInfo -> bind_param('s', $eventAdmin);
    if (!$getUserInfo -> execute()) {
        echo $mysql -> error;
        exit();
    }
    $getUserInfo -> bind_result($userName, $isAdmin);
    $getUserInfoResult = $getUserInfo -> get_result();
    if ($getUserInfoResult -> num_rows > 0) {
        while ($getUserInfo -> fetch()) {
            # code...
        }
    }
    // if ($row['isAdmin'] == 1) {
    //     $grabAdminData = $mysql -> prepare("SELECT * FROM `administration` WHERE `user_id` = ?");
    //     $grabAdminData -> bind_param('s', $userName);
    // } else {
    //     $grabAdminData = $mysql -> prepare("SELECT * FROM `administration` WHERE `user_id` = ?");
    //     $grabAdminData -> bind_param('s', $eventAdmin);

    // }
    //     if (!$grabAdminData -> execute()) {
    //         echo "hi";
    //         exit();
    //     } else {
    //         var_dump($grabAdminData);
    //         echo asdas;
    //         exit();
    //     }
    //     $adminResult = $grabAdminData -> get_result();
    //     if ($adminResult -> num_rows > 0) {
    //         while ($row = $adminResult -> fetch_assoc()) {
    //             $adminLevel = $row['adminLevel'];
    //             $adminEmail = $row['adminEmail'];
    //         }
    //     }
    //     $grabAdminData -> close();


    $getUserInfo -> close();
    if ($adminLevel == 1) {
        $adminLvlName = 'CEO';
    } elseif ($adminLevel == 2) {
        $adminLvlName = 'Super Admin';
    } elseif ($adminLevel == 3) {
        $adminLvlName = 'Admin';
    } elseif ($adminLevel == 4) {
        $adminLvlName = 'Moderator';
    } elseif ($adminLevel == 5) {
        $adminLvlName = 'Employee';
    }
    if ($adminLevel == 1 || $adminLevel == 2 || $adminLevel == 3 || $adminLevel == 4) {
        $edit = 1;
    } else {
        $edit = 0;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $eventTitle ?></title>
</head>
<body>
    <header>
        <nav>
            asd
        </nav>
    </header>
    <div class="middleBackground">
    <h1><?=$eventTitle ?></h1>
    <?php 
        if ($eventBanner == 1) {
            if (file_exists('includeFiles/events/'. $_GET['ID'] .'/eventBanner.jpg') == 1) {
                echo '<img style="width:400px" src="includeFiles/events/'. $_GET['ID'] .'/eventBanner.jpg" alt="eventBanner">';
            } elseif (file_exists('includeFiles/events/'. $_GET['ID'] .'/eventBanner.jpeg') == 1) {
                echo '<img style="width:400px" src="includeFiles/events/'. $_GET['ID'] .'/eventBanner.jpeg" alt="eventBanner">';
            }
        }
        //Ajax real time edit on event page
    ?>
    
    <p><?php echo $eventSmallDescription?></p>
    <p><?=$eventDescription ?></p>
    <p><?=$dateOfStart ?></p>
    <p><?=$checkIn ?></p>
    <p><?=$prizePool ?></p>
    <p><img src="<?php if(file_exists('images/profileImage/profile.'. $eventAdmin .'.png')) {echo 'images/profileImage/profile.'. $eventAdmin .'.png';} else {echo 'images/profileImage/profile.default.png';} ?>" alt="creatorPic" style="width:50px"><a href="profile.php?ID=<?=$eventAdmin;?>"><?=$userName.'</a>,'. $adminLvlName;?></p>    
    <?php if ($eventStatus == 0) {
            //Az event majd lesz
            echo '<p>'. date('l \a\t g:ia T', strtotime($dateOfStart)) .'</p>';
            echo '<p class="eventStatus">To participate in this event you will have to check-in! You will have '; 
                echo date('i \M\i\n\u\t\e\s', (strtotime($dateOfStart) - strtotime($checkIn))) .'<br/> to grab your team and check-in. Otherwise your team is excluded!!</p>';
        } elseif ($eventStatus == 1) {
            //check-in starts shortly

            //check-in start in less 30 minutes
            if (strtotime('now') >= strtotime('-15 minutes', strtotime($checkIn)) && strtotime('now') <= strtotime($checkIn)) {
                echo '<p class="eventStatus">Check-in starts in: '; 
                echo '<br/>'. date('i \M\i\n\u\t\e\s', (strtotime($checkIn) - strtotime('now'))) .'<br/> Stay tuned!</p>';
            } elseif (strtotime('now') < strtotime('-15 minutes', strtotime($checkIn))) {
                echo '<p class="eventStatus">Check-in starts slowly! Stay tuned. </p>'; 
            } elseif ((strtotime('now') > strtotime('-15 minutes', strtotime($checkIn))) && !(strtotime('now') > strtotime($checkIn))) {
                echo '<p class="eventStatus">Check-in starts in less than a minute! Stay tuned. </p>'; 
            } else {
                echo '<p class="eventStatus">Check-in started! Refresh the page</p>'; 
            }

        } elseif ($eventStatus == 2) {
            if ((strtotime($dateOfStart) - strtotime('now')) >= 900) {
                // above 15 mins
                echo '<p class="eventStatus">Check-in ends in: '; 
                echo '<br/>'. date('i \M\i\n\u\t\e\s', (strtotime($dateOfStart) - strtotime('now'))) .'</p>';
                echo '<form action="includeFiles/check-in.inc.php" method="post">
                    <input type="hidden" name="groupAdmin" value="'. $_SESSION['user_id'] .'">
                    <input type="submit" value="check-inSubmit">
                    </form>';

            } elseif ((strtotime($dateOfStart) - strtotime('now')) < 900 && (strtotime($dateOfStart) - strtotime('now')) >= 0) {
                // below 15 mins
                echo '<p class="eventStatus">Hurry! Check-in ends in: '; 
                echo '</p><p class="eventStatus warning">'. date('i \M\i\n\u\t\e\s', (strtotime($dateOfStart) - strtotime('now'))).'</p>';
                echo '<form action="includeFiles/check-in.inc.php" method="post">
                    <input type="hidden" name="groupAdmin" value="'. $_SESSION['user_id'] .'">
                    <input type="submit" value="check-inSubmit">
                    </form>';

            } elseif ((strtotime($dateOfStart) - strtotime('now')) < 0) {
                // after hits deadline
                echo '<p class="eventStatus">Check-in ended<br/>Starting soon</p>';

            }
            
        } elseif ($eventStatus == 3) {
            //Check-in ended, starting soon
            echo '<p class="eventStatus">Check-in ended<br/>Starting soon<br/></p>';

        } elseif ($eventStatus == 4) {
            //Event started
            echo '<p class="eventStatus">Event has started<br/></p>';

        } elseif ($eventStatus == 5) {
            //Event ended
            echo '<p class="eventStatus">This event has ended<br/></p>';
            
        }?>
        
    </div>
</body>
</html>