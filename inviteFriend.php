<?php 
    // session_start();
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php?error=loginRequired');
        exit();
    }
    require 'includeFiles/dbh.inc.php';
    $user_id = $_SESSION['user_id'];

    $getRefferal = $mysql -> prepare("SELECT * FROM `refferals` WHERE `user_id` = ?");
    $getRefferal -> bind_param('s', $user_id);
    $getRefferal -> execute();
    $result = $getRefferal -> get_result();
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
            $userRefferal = $row['refferalCode'];
            $refferalScore = $row['refferalScore'];
        }
    }
    $getRefferal -> close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Your Friends</title>
</head>
<body>
    <p>Hívd meg barátaidat! Minden kódoddal történő regisztráció után pontokat kapsz amit késöbb majd beválthatsz.</p>
    <p>http://localhost/project-maddEsports/registration.php?inviteCode=<?=$userRefferal?></p>
</body>
</html>