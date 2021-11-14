<?php

if (isset($_POST['event_id']) || isset($_GET['event_id'])) {
    include_once 'dbh.inc.php';
        $grabData = $mysql -> prepare("SELECT * FROM `events` WHERE `event_id` = ?");
        $grabData -> bind_param('s', $_GET['event_id']);
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

        $getUserInfo = $mysql -> prepare("SELECT * FROM `users` WHERE `user_id` = ?");
        $getUserInfo -> bind_param('s', $_SESSION['user_id']);
        $getUserInfo -> execute();
        $result = $getUserInfo -> get_result();
        if ($result -> num_rows > 0) {
            while ($row = $result -> fetch_assoc()) {
                $adminName = $row['userName'];
                if ($row['isAdmin'] == 1) {
                    $grabAdminData = $mysql -> prepare("SELECT * FROM `administration` WHERE `user_id` = ?");
                    $grabAdminData -> bind_param('s', $_SESSION['user_id']);
                    $grabAdminData -> execute();
                    $adminResult = $grabAdminData -> get_result();
                    if ($adminResult -> num_rows > 0) {
                        while ($row = $adminResult -> fetch_assoc()) {
                            $adminLevel = $row['adminLevel'];
                            $adminEmail = $row['adminEmail'];
                        }
                    }
                    $grabAdminData -> close();
                }
            }
        }
        $getUserInfo -> close();
        if ($adminLevel == 1) {
            $adminLvlName = 'CEO';
        } elseif ($adminLevel == 2) {
            $adminName = 'Super Admin';
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
    if (isset($_POST['admin_id'])) {
        if (isset($_POST['eventStatus'])) {

            $updateStatus = $mysql -> prepare("UPDATE `events` SET `eventStatus`= ? WHERE `event_id` = ?");
            $updateStatus -> bind_param('ss', $_POST['eventStatus'], $_POST['event_id']);
            if ($updateStatus -> execute()) {
                $updateStatus -> close();
                header('location: ../admin/eventList.php');
                exit();
            } else {
                echo $mysql -> error;
            }
            exit();

        } else {
            header('location: ../admin/eventList.php?error');
            exit();
        }
    } elseif (isset($_POST['qUpdateSubmit'])) {
          //decide 'updatedEventStatus' value
          //Ha kevesebb mint 1 nap van kezdésig ÉS még korábban van mint check-in és "0" státuszban van
          $eventQupdateStmt1 = (((strtotime('-1 day', strtotime($dateOfStart)) <= strtotime('now')) && (strtotime('now')) < strtotime($checkIn)) && $eventStatus == 0);
          // Ha több mint 1 nap van kezdésig és nem "0" státuszban van
          $eventQupdateStmt2 = ((strtotime('-1 day', strtotime($dateOfStart)) > strtotime('now')) && $eventStatus != 0);
          //Ha check-in alatt van de nem "2" a státusz
          $eventQupdateStmt3 = (((strtotime($checkIn) <= strtotime('now')) && (strtotime($dateOfStart) >= strtotime('now'))) && $eventStatus != 2);
          //Ha már el kellett volna kezdődnie de "0"/"1"/"2" státuszban van
          $eventQupdateStmt4 = ((strtotime($dateOfStart) < strtotime('now')) && $eventStatus != 3 && $eventStatus != 4 && $eventStatus != 5);
          //Ha 10 perce mennie kellene de még mindíg "3" státuszban van
          $eventQupdateStmt5 = ((strtotime('+10 minutes', strtotime($dateOfStart)) < strtotime('now')) && $eventStatus == 3);
          //Ha 1 hét eltelt a verseny vége óta és nincs "5" státuszban
          $eventQupdateStmt6 = ((strtotime('+1 week', strtotime($dateOfStart)) < strtotime('now')) && $eventStatus != 5);

          if ($eventQupdateStmt0) {
            $updatedEventStatus = 0;

          } elseif ($eventQupdateStmt1) {
            $updatedEventStatus = 1;

          } elseif ($eventQupdateStmt2) {
            $updatedEventStatus = 0;

          } elseif ($eventQupdateStmt3) {
            $updatedEventStatus = 2;

          } elseif ($eventQupdateStmt4) {
            if ($eventQupdateStmt5) {
                $updatedEventStatus = 4;

            } else {
                $updatedEventStatus = 3;

            }
          } elseif ($eventQupdateStmt5) {
            $updatedEventStatus = 4;

          } elseif ($eventQupdateStmt6) {
            $updatedEventStatus = 5;

          }
          //auto update 
        $updateEventStatus = $mysql -> prepare("UPDATE `events` SET `eventStatus` = ? WHERE `event_id` = ? AND `eventStatus` = ?");
        $updateEventStatus -> bind_param('isi', $updatedEventStatus, $_GET['event_id'] ,$eventStatus);
        if ($updateEventStatus -> execute()) {
            header('location: ../admin/eventList.php?u='. $_GET['event_id']);
            exit();
        exit();
        } else {
            echo $mysql -> error;
            exit();
        }


        exit();
    } else {
        header('location: ../admin/eventList.php?error');
        exit();
    }
}  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            background: #1B1C22;
            margin: 0;
        }
        div.middleBackground {
            position: fixed;
            background: #121317;
            border-radius: 15px;
            padding: 20px;
            width: 60%;
            height: 100%;
            margin: 200px 20% 0 20%;
            z-index: 100;
        }
        nav {
            width: 100%;
            height: 100%;
            margin: 0;
            position: fixed;
            background: white;
            clip-path: polygon(0 0, 100% 0, 100% 8%, 0 21%);
            z-index: 1;

        }
        p {
            color: white;
        }
        p.eventStatus {
            font-weight: 600;
            font-style: italic;
            font-size: 20px;
            letter-spacing: 2px;
        }
        p.warning {
            color: red;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $eventTitle ?></title>
</head>
<body>
    <header>
        <nav>
            nav
        </nav>
    </header>
    <div class="middleBackground">
        <p><?=$eventTitle ?></p>
        <?php 
        if ($eventBanner == 1) {
            if (file_exists('events/'. $_GET['event_id'] .'/eventBanner.jpg') == 1) {
                echo '<img style="width:400px" src="events/'. $_GET['event_id'] .'/eventBanner.jpg" alt="eventBanner">';
            } elseif (file_exists('events/'. $_GET['event_id'] .'/eventBanner.jpeg') == 1) {
                echo '<img style="width:400px" src="events/'. $_GET['event_id'] .'/eventBanner.jpeg" alt="eventBanner">';
            }
        }
        //Ajax real time edit on event page
    ?>
    
    <p><?="Small description: ". $eventSmallDescription ?></p>
    <p><?="Event description: ". $eventDescription ?></p>
    <p><?="Date of start: ". $dateOfStart ?></p>
    <p><?php echo 'Check-in: '; 
    if (strtotime($dateOfStart) - strtotime($checkIn) < 3600) {
        echo '( '. date('i \M\i\n\s', strtotime($dateOfStart) - strtotime($checkIn)) .' )';
    } else {
        echo '( 1 Hour )';
    } ?></p>
    <p><?=$prizePool ?></p>
    <p><img src="<?php if(file_exists('../images/profileImage/profile.'. $eventAdmin .'.png')) {echo '../images/profileImage/profile.'. $eventAdmin .'.png';} else {echo '../images/profileImage/profile.default.png';} ?>" alt="creatorPic" style="width:50px"><a href="../profile.php?ID=<?=$eventAdmin;?>"><?=$adminName.'</a>,'. $adminLvlName;?></p>
    <?php if ($eventStatus == 0) {
            //Az event majd lesz
            echo '<p>'. date('l \a\t g:ia T', strtotime($dateOfStart)) .'</p>';
            echo '<p class="eventStatus">Check the event for missing details! <br/>This event will be on: '; 
                echo $dateOfStart .' <br/>And teams will have: ';
                if (strtotime($dateOfStart) - strtotime($checkIn) < 3600) {
                    echo date('i \M\i\n\u\t\e\s :s', (strtotime($dateOfStart) - strtotime($checkIn))) .' To check-in</p>';
                } else {
                    echo '1 Hour to check-in</p>';
                }
        } elseif ($eventStatus == 1) {
            //check-in starts shortly

            //check-in start in less 30 minutes
            if (strtotime('now') >= strtotime('-15 minutes', strtotime($checkIn)) && strtotime('now') <= strtotime($checkIn)) {
                echo '<p class="eventStatus">Check-in starts in: '; 
                echo '<br/>'. date('i \M\i\n\u\t\e\s s \s', (strtotime($checkIn) - strtotime('now'))) .'<br/> Preapare to update status!</p>';
            } elseif (strtotime('now') < strtotime('-15 minutes', strtotime($checkIn))) {
                echo '<p class="eventStatus">Check-in starts slowly! Preapare to update status! </p>'; 
            } else {
                echo '<p class="eventStatus">Check-in started! Update the status</p>'; 
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
                echo '<p class="eventStatus"Prepare to start event!! Check-in ends in: '; 
                echo '</p><p class="eventStatus warning">'. date('i \M\i\n\u\t\e\s', (strtotime($dateOfStart) - strtotime('now'))).'</p>';
                echo '<form action="includeFiles/check-in.inc.php" method="post">
                    <input type="hidden" name="groupAdmin" value="'. $_SESSION['user_id'] .'">
                    <input type="submit" value="check-inSubmit">
                    </form>';

            } elseif ((strtotime($dateOfStart) - strtotime('now')) < 0) {
                // after hits deadline
                echo '<p class="eventStatus">Check-in ended<br/>Start the event</p>';

            }
            
        } elseif ($eventStatus == 3) {
            //Check-in ended, starting soon
            echo '<p class="eventStatus">Check-in ended<br/>Start the event<br/></p>';

        } elseif ($eventStatus == 4) {
            //Event started
            echo '<p class="eventStatus">Event has started<br/></p>';

        } elseif ($eventStatus == 5) {
            //Event ended
            echo '<p class="eventStatus">This event has ended<br/></p>';
            
        }?>   
        <table>

        </table>
    </div>
</body>
</html>