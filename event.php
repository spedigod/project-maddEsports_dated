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
            }
        }
        $grabData -> close();
    }

    $getUserInfo = $mysql -> prepare("SELECT * FROM `users` WHERE `user_id` = ?");
    $getUserInfo -> bind_param('s', $eventAdmin);
    $getUserInfo -> execute();
    $result = $getUserInfo -> get_result();
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
            $adminName = $row['userName'];
            if ($row['isAdmin'] == 1) {
                $grabAdminData = $mysql -> prepare("SELECT * FROM `administration` WHERE `user_id` = ?");
                $grabAdminData -> bind_param('s', $eventAdmin);
                $grabAdminData -> execute();
                $adminResult = $grabAdminData -> get_result();
                if ($adminResult -> num_rows > 0) {
                    while ($row = $adminResult -> fetch_assoc()) {
                        $adminLevel = $row['adminLevel'];
                        $adminEmal = $row['adminEmail'];
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
?>

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
    </style>
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
    <p><img src="<?php if(file_exists('images/profileImage/profile.'. $eventAdmin .'.png')) {echo 'images/profileImage/profile.'. $eventAdmin .'.png';} else {echo 'images/profileImage/profile.default.png';} ?>" alt="creatorPic" style="width:50px"><a href="profile.php?ID=<?=$eventAdmin;?>"><?=$adminName.',</a>'. $adminLvlName;?></p>    
    </div>
</body>
</html>