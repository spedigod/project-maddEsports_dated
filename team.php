<?php 
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    require 'includeFiles/dbh.inc.php';
    //SESSION['inGroup]

    if ($_SESSION['inGroup'] == 1) {
        $grabGroupDetails = $mysql -> prepare("SELECT * FROM `userteamlist` WHERE `user_id` = ?");
        $grabGroupDetails -> bind_param('s', $user_id);
        if ($grabGroupDetails -> execute()) {
            $result = $grabGroupDetails -> get_result();
            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $team_id = $row['team_id'];
                    $leaderStatus = $row['leaderStatus'];
                    $userTeamLevel = $row['userTeamLevel'];
                }
            } else {
                echo 'user not found in "userTeamList"';
            }
        } else {
            echo $mysql -> error;
            exit();
        }
    } else {
        $team_id = null;
    }

    if (isset($_GET['team_id'])) {
        if ($team_id == $_GET['team_id']) {
             //Saját csapot
            if (isset($userTeamLevel)) {
                if ($userTeamLevel == 1 || $userTeamLevel == 2) {
                     //Moderátor vagy vezető rang a csapatban
                    $edit = true;
                } else {
                    $edit = false;
                }
            } else {
                $edit = false;
            }
            
        } else {
             //Nem a user csapatának oldala
             $team_id = $_GET['team_id'];
            $edit = false;
        }
            $query = $mysql -> prepare("SELECT * FROM `teams` WHERE `team_id` = ?");
            $query -> bind_param('s', $team_id);
            if ($query -> execute()) {
                $queryResult = $query -> get_result();
                if ($queryResult -> num_rows > 0) {
                    while ($row = $queryResult -> fetch_assoc()) {
                        $teamName = $row['teamName'];
                        $teamGame = $row['teamGame'];
                        $teamLogo = $row['teamLogo'];
                        $memberCount = $row['memberCount'];
                        $leader_id = $row['leader_id'];
                        $teamLevel = $row['teamLevel'];
    
                        if ($leader_id == $user_id) {
                            $edit = true;
                        }
                    }
                }
            } else {
                echo $mysql -> error;
                exit();
            }

    } elseif ($_SESSION['inGroup'] == 1) {
         //Nincs $_GET['team_id'], de csapatban van
        if ($leaderStatus == 1) {
            $query = $mysql -> prepare("SELECT * FROM `teams` WHERE `team_id` = ? AND `leader_id` = ?");
            $query -> bind_param('ss', $team_id, $user_id);
        } else {
            $query = $mysql -> prepare("SELECT * FROM `teams` WHERE `team_id` = ?");
            $query -> bind_param('s', $team_id);
        }
        if ($query -> execute()) {
            $queryResult = $query -> get_result();
            if ($queryResult -> num_rows > 0) {
                while ($row = $queryResult -> fetch_assoc()) {
                    $teamName = $row['teamName'];
                    $teamGame = $row['teamGame'];
                    $teamLogo = $row['teamLogo'];
                    $memberCount = $row['memberCount'];
                    $leader_id = $row['leader_id'];
                    $teamLevel = $row['teamLevel'];

                    if ($leader_id == $user_id || $userTeamLevel == 1 || $userTeamLevel == 2) {
                        $edit = true;
                    }
                }
            }
        } else {
            echo $mysql -> error;
            exit();
        }
    } else {
         //Nincs csapatban és nem csapatra keresett rá
         include 'includeFiles/createGroup.inc.php';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Csoportok</title>
</head>
<body>
    <?php 
        if (isset($edit)) {
            if ($edit == true) {
                echo <<<TEXT
                    <form action="teamSettings.php" method="post">
                        <input type="hidden" name="team_id" value="$team_id">
                        <input type="submit" name="teamSettingSubmit" value="teamSettingSubmit">
                    </form>
                TEXT;
            }
        }
    ?>
    
</body>
</html>