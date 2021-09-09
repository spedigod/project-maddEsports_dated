<?php
    // session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        include 'includeFiles\main.includes.php';
    } else {
        header('location: login.php');
    }
    require 'includeFiles/profileQuery.inc.php';

    /*// barátkérelmek listázása
    $requests = $mysql -> prepare("SELECT * FROM friendrequests WHERE `to_id` = ?");
    $requests -> bind_param('s', $user_id);
    $requests -> execute();
    $getData = $requests -> get_result();
    if ($getData -> num_rows > 0) {
        $notifications = $getData -> num_rows;
        while ($row = $getData -> fetch_assoc()) {
            $grabName = $mysql -> prepare("SELECT `userName` FROM `users` WHERE `user_id` = ?");
            $grabName -> bind_param('s', $row['from_id']);
            $grabName -> execute();
                echo '<p>'. $name['userName'] .' barátnak jelölt 
                    <form action="includeFiles/friendRequestHandler.inc.php" method="POST">
                        <input type="hidden" value="'. $row['publicRequestID'] .'" name="request_id" />
                        <input type="hidden" value="'. $row['from_id'] .'" name="request_from" />
                        <button type="submit" name="requestAccept">Elfogad</button>
                        <button type="submit" name="requestDeny">Elutasít</button>
                    </form>
                ';
        }
        
    }
    $requests -> close(); */

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
        echo '<button><a href="admin.php">Admin panel</a></button>';
    }
    ?>
    <button><a href="groupFinder.php">Csapatok</a></button>
    <button><a href="profile.php?userName=<?php echo $userName; ?>">Profil</a></button>
    <button><a href="events.php">Események</a></button>
    <section>
        <table>
            <tr>
                <th>Friends</th>
            </tr>
            <?php 
            $friendList = $mysql -> prepare("SELECT * FROM `friends` WHERE `friend1_id` = ? OR `friend2_id` = ?");
            $friendList -> bind_param('ss', $userName, $userName);
            $friendList -> execute();
            $getData = $friendList -> get_result();
            if ($getData -> num_rows > 0) {
                while ($row = $getData -> fetch_assoc()) {
                    $friend = $row['friend1_id'];
                    switch ($friend) {
                        case $userName:
                            echo '<tr>
                                    <th>'. $row["friend2_id"]. '</th></tr>';
                            break;
                        default:
                        echo '<tr>
                                <th>'. $row["friend1_id"]. '</th></tr>';
                    }
                }
            }
            $friendList -> close();
        ?>
        </table>
         <!-- dropdown menübe kell rakni -->
        <p><?php echo $_SESSION['notification'] .' új értesítése van!'; ?></p>
    </section>
</body>
</html>