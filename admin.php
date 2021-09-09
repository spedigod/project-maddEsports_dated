<?php 
    
    require 'includeFiles/profileQuery.inc.php';

    if ($isAdmin == 0) {
        header('location: home.php?ID='. $_SESSION['user_id']);
    }
    $admin_id = $_SESSION['user_id'];

    $grabAdminLevel = $mysql -> prepare("SELECT `adminLevel` FROM `administration` WHERE `user_id` = ?");
    $grabAdminLevel -> bind_param('s', $admin_id);
    $grabAdminLevel -> execute();
    $getData = $grabAdminLevel -> get_result();
        if ($getData -> num_rows > 0) {
            while ($row = $getData -> fetch_assoc()) {
                $adminLevel = $row['adminLevel'];
            }
        }
    $grabAdminLevel -> close();

    if ($adminLevel == 1) {
        $adminName = 'CEO';
    } elseif ($adminLevel == 2) {
        $adminName = 'Super Admin';
    } elseif ($adminLevel == 3) {
        $adminName = 'Admin';
    } elseif ($adminLevel == 4) {
        $adminName = 'Moderator';
    } elseif ($adminLevel == 5) {
        $adminName = 'Employee';
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section>
        <button><a href="includeFiles/logout.inc.php">Kilépés</a></button>
        <br />
        <?php 
            echo $userFirstName. ' ' .$userLastName;
            echo '<br />';
            echo $adminName;
        ?>
    </section>
    <?php 
        if ($adminLevel == 1 || $adminLevel == 2 || $adminLevel == 3) {
           echo '<button><a href="addModerator.php">Új admin hozzáadása</a></button>';
           echo '<button><a href="eventCreator.php">Hozz létre új eseményt</a></button>';
        }
    ?>
    <?php 
        if ($adminLevel == 1 || $adminLevel == 2) {
           echo '    <button><a href="userList.php">Felhasználók</a></button>';
        }
    ?>
</body>
</html>