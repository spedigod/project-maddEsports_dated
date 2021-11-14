<?php 

    function invaliduserName($userName) {
      $result = true;
      if (!preg_match("/^[a-zA-Z0-9]*$/", $userName)) {
        $result = true;
      } else {
        $result = false;
      }
      return $result;
    }
    
    function invalidName($userFirstName, $userLastName) {
      $result = true;
      if ((!preg_match("/^[a-zA-Z]*$/", $userFirstName)) && (!preg_match("/^[a-zA-Z]*$/", $userLastName))) {
        $result = true;
      } else {
        $result = false;
      }
      return $result;
    }
      
    function userExists($mysql, $userName, $userEmail) {
      $userExists = $mysql -> prepare("SELECT `userName`, `userEmail` FROM `users` WHERE `userName` = ? OR `userEmail`= ?");
      $userExists -> bind_param('ss', $userName, $userEmail);
      $userExists -> execute();
      $getData = $userExists -> get_result();
      if ($getData -> num_rows > 0) {
        while ($row = $getData -> fetch_assoc()) {
          $dbUserName = $row['userName'];
          $dbUserEmail = $row['userEmail'];
        }
        if ($dbUserName == $userName) {
          return "case1";
          exit();
        } elseif ($dbUserEmail == $userEmail) {
            return "case2";
            exit();
        }
      }
      $userExists -> close();
      return "false";
      exit();
    }
    
    function userEmailInvalid($userEmail) {
      $result = true;
      if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $result = true;
      } else {
        $result = false;
      }
      return $result;
    }

    function pwdLength($userPassword) {
      if (strlen($userPassword) < 8) {
        return false;
      } else {
        return true;
      }
    }

    function userLogCheck($mysql, $userName) {
        $userLogCheck = $mysql -> prepare('SELECT `userName` FROM `users` WHERE `userName` = ?');
        $userLogCheck -> bind_param('s', $userName);

        $userLogCheck -> execute();

        $getData = $userLogCheck -> get_result();
        if ($getData -> num_rows == 1) {
            return true;
            header('location: login.inc.php');
            exit();
        } else {
            return false;
            header('location: login.inc.php');
            exit();
        }
        $userLogCheck -> close();
        header('location: login.inc.php');
        exit();
    }

    function generateUserID($mysql) {
      $n = 10;
      $a = 1;
      while ($a = 1) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $user_id = $randomString;
        $getUID = $mysql -> prepare("SELECT * FROM `users` WHERE `user_id` = ?");
        $getUID -> bind_param('s', $user_id);
        $getUID -> execute();

        $getData = $getUID -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
        $getUID -> close();
      }
      $_SESSION['user_id'] = $user_id;
      return $_SESSION['user_id'];
    }

    function generateEventID($mysql) {
      $n = 15;
      $a = 1;
      while ($a = 1) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $eventID = $randomString;
        $getEventID = $mysql -> prepare("SELECT * FROM `events` WHERE `event_id` = ?");
        $getEventID -> bind_param('s', $eventID);
        $getEventID -> execute();

        $getData = $getEventID -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
        $getEventID -> close();
      }
      $_SESSION['eventID'] = $eventID;
      return $_SESSION['eventID'];
    }

    function generateRefferalCode($mysql, $user_id) {
      $n = 20;
      $a = 1;
      while ($a = 1) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $getRefferalCode = $mysql -> prepare("SELECT * FROM `refferals` WHERE `refferalCode` = ?");
        $getRefferalCode -> bind_param('s', $randomString);
        $getRefferalCode -> execute();

        $getData = $getRefferalCode -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
        $getRefferalCode -> close();
      }
      $stmt = $mysql -> prepare("INSERT INTO `refferals` (`user_id`, `refferalCode`) 
      VALUES (?, ?)");
      $stmt -> bind_param('ss', $user_id, $randomString);
      $stmt -> execute();
      $stmt -> close();   
    }

    function setUserLevel($mysql, $user_id) {
      $userLevel = 1;
      $userExp = 0;
      $experiencePoints = 1000;

      $setLevel = $mysql -> prepare('INSERT INTO `userlevel`(`user_id`, `userLevel`, `userExp`, `experiencePoints`) VALUES (?, ?, ?, ?)');
      $setLevel -> bind_param('siii', $user_id, $userLevel, $userExp, $experiencePoints);
      $setLevel -> execute();
      $setLevel -> close();

    }

    function createUniqueID($mysql) {
      $n = 8;
      $a = 1;
      while ($a = 1) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = 'friend_';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $getRequestID = $mysql -> prepare("SELECT `publicRequestID` FROM `friendrequests` WHERE `publicRequestID` = ?");
        $getRequestID -> bind_param('s', $randomString);
        $getRequestID -> execute();

        $getData = $getRequestID -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
        $getRequestID -> close();
      }
      return $randomString;
    }

    function checkTeam($user_id, $mysql) {
      $checkTeamExistance = $mysql -> prepare("SELECT `team_id` FROM `teams` WHERE `leader_id` = ?");
      $checkTeamExistance -> bind_param('s', $user_id);
      if (!$checkTeamExistance -> execute()) {
          return $mysql -> error;
      } else {
          if ($checkTeamExistance -> num_rows > 0) {
              //Már létezik
              return false;
          } else {
              $checkTeamExistance -> close();
              //Test if user is in a group already
              $checkUserGroupList = $mysql -> prepare("SELECT `user_id` FROM `userteamlist` WHERE `user_id` = ?");
              $checkUserGroupList -> bind_param('s', $user_id);
              if (!$checkUserGroupList -> execute()) {
                  return $mysql -> error;
              } else {
                $checkUserGroupList -> get_result();
                  if ($checkUserGroupList -> affected_rows > 0) {
                      //Már benne van egy csoportban
                      return false;
                  } else {
                    return true;
                  }
              }
          }
      }
  }

    function createUniqueTeamId($mysql) {
      $n = 15;
      $a = 1;
      while ($a = 1) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $getRequestID = $mysql -> prepare("SELECT `team_id` FROM `teams` WHERE `team_id` = ?");
        $getRequestID -> bind_param('s', $randomString);
        $getRequestID -> execute();

        $getData = $getRequestID -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
        $getRequestID -> close();
      }
      return $randomString;
    }

    function changeName($mysql, $newName, $oldName, $user_id) {
      $changeData = $mysql -> prepare("UPDATE `users` SET `userName`= ? WHERE `user_id` = ?");
      $changeData -> bind_param('ss', $newName, $user_id);
      $changeData -> execute();
      if (!$changeData -> execute()) {
        header('location: ../profileEdit.php?ID='. $user_id . '&error=usernameAlreadyTaken');
        exit();
      }
      $changeData -> close();

       # disable username change for 30 days !!!Awaiting for implementing!!!!
      $disableChange = $mysql -> prepare("INSERT INTO `changedname` (user_id, new_name, old_name) VALUES (?, ?, ?)");
      $disableChange -> bind_param('sss', $user_id, $newName, $oldName);
      $disableChange -> execute();
      $disableChange -> close();
    }