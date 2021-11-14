<?php
    if (!isset($_SESSION['user_id'])) {
        header('location: ../login.php?error=loginRequired');
        exit();
    }
    require_once 'functions/main.function.php';
    require_once 'dbh.inc.php';
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['quickAddFriendRequest']) || isset($_POST['friendRequest'])) {

         //Convert Name to ID
        $requestTo = $_POST['submittedUserName'];
        $grabID = $mysql -> prepare("SELECT `user_id` FROM `users` WHERE `userName` = ? OR `user_id` = ?");
        $grabID -> bind_param('ss', $requestTo, $requestTo);
        if (!$grabID -> execute()) {
            echo $mysql -> error;
            exit();
        } 
        $grabIDResult = $grabID -> get_result();
        if ($grabIDResult -> num_rows == 0) {
            header('location: ../home.php?error=nuSuchUser');
            exit();
        } else {
            while ($row = $grabIDResult -> fetch_assoc()) {
                $submittedUserID = $row['user_id'];
            }
        }
        $grabID -> close();

         //Test if user_id match
        if ($user_id == $submittedUserID) {
            if (isset($_POST['friendRequest'])) {
                header('location: ../profile.php?ID='. $user_id .'&error=cantAddYourself');
                exit();
            } elseif (isset($_POST['quickAddFriendRequest'])) {
                header('location: ../home.php?error=cantAddYourself');
                exit();
            }
        }
        
         //Send request to submitted user_id
        $publicRequestID = createUniqueID($mysql);

        $testFriendRequest = $mysql -> prepare("SELECT * FROM `friendrequests` WHERE (`from_id` = ? OR `from_id` = ?) 
                                                                                AND (`to_id` = ? OR `to_id` = ?)");
        $testFriendRequest -> bind_param('ssss', $user_id, $submittedUserID, $user_id, $submittedUserID);
        if (!$testFriendRequest -> execute()) {
            echo $mysql -> error;
            exit();
        }
        $testFriendRequestResult = $testFriendRequest -> get_result();
        if ($testFriendRequestResult -> num_rows > 0) {
            if (isset($_POST['friendRequest'])) {
                header('location: ../profile.php?ID='. $submittedUserID .'&error=pendingFriendRequestAlready');
            exit();
            } elseif (isset($_POST['quickAddFriendRequest'])) {
                header('location: ../home.php?error='. $requestTo .'pendingFriendRequestAlready');
                exit();
            }
        } elseif ($testFriendRequestResult -> num_rows == 0) {
            $testFriendTable = $mysql -> prepare("SELECT * FROM `friends` WHERE (`friend1_id` = ? OR `friend1_id` = ?) 
                                                                            AND (`friend2_id` = ? OR `friend2_id` = ?)");
            $testFriendTable -> bind_param('ssss', $user_id, $submittedUserID, $user_id, $submittedUserID);
            if (!$testFriendTable -> execute()) {
                echo $mysql -> error;
                exit();
            }
            $testFriendTableResult = $testFriendTable -> get_result();
            if ($testFriendTableResult -> num_rows > 0) {
                if (isset($_POST['friendRequest'])) {
                    header('location: ../profile.php?ID='. $submittedUserID .'&error=alreadyFriend');
                exit();
                } elseif (isset($_POST['quickAddFriendRequest'])) {
                    header('location: ../home.php?error='. $requestTo .'alreadyFriend');
                    exit();
                }
            } elseif ($testFriendTableResult -> num_rows == 0) {
                $friendRequest = $mysql -> prepare('INSERT INTO `friendrequests` (`from_id`, `to_id`, `publicRequestID`)
                                            VALUES (?, ?, ?)');
                $friendRequest -> bind_param('sss', $user_id, $submittedUserID, $publicRequestID);
                if (!$friendRequest -> execute()) {
                    echo("Error description: " . $mysql -> error);
                    exit();
                }
                $friendRequest -> close();
                if (isset($_POST['friendRequest'])) {
                    header('location: ../profile.php?ID='. $submittedUserID .'&error=friendAddedSuccesfully');
                    exit();
                } elseif (isset($_POST['quickAddFriendRequest'])) {
                    header('location: ../home.php?error=friendAddedSuccesfully');
                    exit();
                }
                header('location: ../home.php?error=friendAddedSuccesfully');
                exit();
            }
        } else {
            header('location: ../home.php?error=bigError');
            exit();
        }
    } else {
        header('location: ../profile.php');
        exit();
    }


    
    
    

    
