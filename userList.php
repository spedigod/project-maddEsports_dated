<?php 
    
    require 'includeFiles/profileQuery.inc.php';

    if ($isAdmin == 0) {
        header('location: home.php?ID='. $_SESSION['user_id']);
    }
    $admin_id = $_SESSION['user_id'];

    $isAdmin = $mysql -> prepare("SELECT * FROM `administration` WHERE `user_id` = ?");
    $isAdmin -> bind_param('s', $admin_id);
    $isAdmin -> execute();
    $getData = $isAdmin -> get_result();
        if ($getData -> num_rows == 0) {
            header('location: home.php?ID='. $_SESSION['user_id']);
            exit();
        }
    $isAdmin -> close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find any user</title>
</head>
<body>
    <?php 
            $userList = $mysql -> prepare("SELECT `user_id`,`userName`,`inGroup`,`userLevel` FROM `users`");
            $userList -> execute();
            $getData = $userList -> get_result();
            if ($getData -> num_rows > 0) {
                echo '<table>';
                echo '<tr>';
                echo '<th> Felhasználónév</th>';
                echo '<th> Szint</th>';
                echo '<th> Csapat</th>';
                echo '</tr>';
                while ($row = $getData -> fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>';
                    echo '<a href="profile.php?ID='. $row['user_id'] .'">'. $row["userName"] .'</a>';
                    echo '</td>';
                    echo '<td>';
                    echo $row['userLevel'];
                    echo '</td>';
                    echo '<td>';
                    if (!empty($row['userGroup'])) {
                        echo $row['userGroup'];
                    }
                    echo '</td>';
                    if ($_SESSION['inGroup'] == 1) {
                        echo '<td>';
                        if ($row['inGroup'] == 0) {
                            echo '<a href=""><button>Meghívás csapatba</button></a>';
                        }
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }
            $userList -> close();
    ?>
</body>
</html>