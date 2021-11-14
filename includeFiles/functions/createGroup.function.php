<?php
    // session_start();

    include_once '../dbh.inc.php';
    include_once 'main.function.php';

    if ($_SESSION['inGroup'] == 1) {
        header('location: ../../groups.php?error=youAreInAGroup');
        exit();
    }

    $team_id = createUniqueTeamId($mysql);
    $teamName = $_GET['teamName'];
    $teamLogo = 'teamLogo';
    $user_id = $_SESSION['user_id'];
    $teamGame = $_GET['teamGame'];
    $teamLevel = 1;
    $memberCount = 1;
    $leaderStatus = 1;
    $userTeamLevel = 1;
    $inGroup = 1;
    $dateTime = time();

    if (checkTeam($user_id, $mysql) == 1) {
        $stmt = $mysql -> prepare('INSERT INTO `teams` (`team_id`, `teamName`, `teamGame`, `teamLogo`, `memberCount`, `leader_id`, `teamLevel`)
                                 VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt -> bind_param('ssssisi', $team_id, $teamName, $teamGame, $teamLogo, $memberCount, $user_id, $teamLevel);
        if (!$stmt -> execute()) {
            echo $mysql -> error;
            exit();
        }  else {
            $stmt2 = $mysql -> prepare('INSERT INTO `userteamlist`(`user_id`, `team_id`, `leaderStatus`, `userTeamLevel`, `userJoinDate`) VALUES (?, ?, ?, ?, ?)');
            $stmt2 -> bind_param('ssiis', $user_id, $team_id, $leaderStatus, $userTeamLevel, $dateTime);
            if (!$stmt2 -> execute()) {
                echo $mysql -> error;
                exit();
            } else {
                $stmt3 = $mysql -> prepare('UPDATE `users` SET `inGroup` = ? WHERE `user_id` = ?');
                $stmt3 -> bind_param('is', $inGroup, $user_id);
                if (!$stmt3 -> execute()) {
                    echo $mysql -> error;
                    exit();
                } else {
                    $achievement_id = 'badge_team_leader';
                    $stmt4 = $mysql -> prepare("INSERT INTO `userachievements` (`user_id`, `achievement_id`) VALUES (?, ?)");
                    $stmt4 -> bind_param('ss', $user_id, $achievement_id);
                    $date = date('Y-m-d H:i:s', strtotime('now'));
                    if (!$stmt4 -> execute()) {
                        echo $mysql -> error;
                        exit();
                    } else {
                        $stmt5 = $mysql -> prepare("SELECT `achievementName`, `achievementDescription` FROM `achievementlist` WHERE `achievement_id` = ?");
                        $stmt5 -> bind_param('s', $achievement_id);
                        if (!$stmt5 -> execute()) {
                            echo $mysql -> error;
                            exit();
                        } else {
                            $stmt5 -> bind_result($achievementName, $achievementDescription);
                            $stmt5 -> fetch();
                            $array = [
                                'notification_id' => $achievement_id,
                                'notificationName' => $achievementName,
                                'notificationDescription' => $achievementDescription,
                                'createdAt' => $date
                            ];
                            if (!isset($notifications) || empty($notifications)) {
                                $notifications = [$array];
                            } else {
                                array_unshift($notifications, $array);
                            }
                        }
                    }
                    $_SESSION['notification'] = $_SESSION['notification'] + 1;
                    $_SESSION['notifications'] = $notifications;
                    $_SESSION['inGroup'] == 1;
                    $stmt5 -> close();
                    $stmt4 -> close();
                    $stmt3 -> close();
                    $stmt2 -> close(); 
                    $stmt -> close();
                    header('location: ../../home.php?m=teamCreatedSuccessfully');
                    exit();
                }
            }
        }
    } else {
        header('location: ../../groups.php?error=somethingWentWrong');
        exit();
    }

    

    