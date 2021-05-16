<?php 
    
    //include 'includeFiles/validateUser.php';

    if ($isAdmin == 0) {
        header('location: home.php?userName='. $userName);
    }
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
        }
    ?>
</body>
</html>