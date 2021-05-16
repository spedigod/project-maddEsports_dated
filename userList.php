<?php 
    // session_start();
    if (!isset($_SESSION['userName'])) {
        header('location: login.php?error=loginRequired');
    }
    include 'includeFiles/dbh.inc.php';
    $userGroupName = $_SESSION['userGroup'];
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
    echo $userGroupName;
        $userList = $mysql -> prepare("SELECT * FROM `users` ");
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
                echo '<a href="profile.php?userName='. $row['userName'] .'">'. $row["userName"] .'</a>';
                echo '</td>';
                echo '<td>';
                echo $row['userLevel'];
                echo '</td>';
                echo '<td>';
                echo $row['userGroup'];
                echo '</td>';
                if ($row['inGroup'] == 0) {
                    if ($_SESSION['inGroup'] == 1) {
                        
                    }
                    
                }
                echo '</tr>';
            }
            echo '</table>';
        }
        $userList -> close();
    ?>
</body>
</html>