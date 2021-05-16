<?php
    // session_start();

    if (isset($_SESSION['userName'])) {
        // header('location: includeFiles\redirecter.inc.php');
        $userName = $_SESSION['userName'];
    } else {
        header('location: login.php');
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
        <?php include 'includeFiles/profileQuery.inc.php'; ?>
        <p class="welcome"> Welcome, </p> 
        <p class="userName"> <?php echo $userName ?> </p>
    </section>
    <button><a href="includeFiles/logout.inc.php">Kilépés</a></button>
    <?php 
    if ($isAdmin == 1) {
        echo '<button><a href="admin">Admin panel</a></button>';
    }
    ?>
    <button><a href="groupFinder">Csapatok</a></button>
    <button><a href="profile<?php $userName ?>">Profil</a></button>
    <button><a href="userList">Felhasználók</a></button>
    <section>
        <?php 
            $friendList = $mysql -> prepare("SELECT * FROM `friends` WHERE `friendOne` = ? OR `friendTwo` = ?");
            $friendList -> bind_param('ss', $userName, $userName);
            $friendList -> execute();
            $getData = $friendList -> get_result();
            if ($getData -> num_rows > 0) {
                while ($row = $getData -> fetch_assoc()) {
                    $friend = $row['friendOne'];
                    switch ($friend) {
                        case $userName:
                            echo $row['friendTwo'];
                            break;
                        default:
                            echo $row['friendOne'];
                    }
                }
            }
            $friendList -> close();
        ?>
    </section>
</body>
</html>