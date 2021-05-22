<?php 

    function invaliduserName($userName) {
        $result;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $userName)) {
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
        $result;
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
          $result = true;
        } else {
          $result = false;
        }
        return $result;
      }

    function userLogCheck($mysql, $userName, $userPassword) {
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

    function getRefferalCode($n, $mysql, $userName, $refferalCode) {
      $n = 10;
      $a = 1;
      while ($a = 1) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $refferalCode = $randomString;
        $getRefferalCode = $mysql -> prepare("SELECT * FROM `refferals` WHERE `refferalCode` = ?");
        $getRefferalCode -> bind_param('s', $refferalCode);
        $getRefferalCode -> execute();

        $getData = $getRefferalCode -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
        $getRefferalCode -> close();
      }
      $stmt = $mysql -> prepare("INSERT INTO `refferals` (`userName`, `refferalCode`) 
      VALUES (?, ?)");
      $stmt -> bind_param('ss', $userName, $refferalCode);
      $stmt -> execute();
      $stmt -> close();   
    }
    function setUserLevel($mysql, $userName, $level, $userExp, $experiencePoints) {
      $setLevel = $mysql -> prepare('INSERT INTO `userlevel`(`userName`, `userLevel`, `userExp`, `experiencePoints`) VALUES (?, ?, ?, ?)');
      $setLevel -> bind_param('siii', $userName, $level, $userExp, $experiencePoints);
      $setLevel -> execute();
      $setLevel -> close();

    }

    function createUniqueID($mysql) {
      $n = 15;
      $a = 1;
      while ($a = 1) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $stmt = $mysql -> prepare("SELECT `publicRequestID` FROM `friendrequests` WHERE `publicRequestID` = ?");
        $stmt -> bind_param('s', $randomString);
        $stmt -> execute();

        $getData = $stmt -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
      }
      return $randomString;
    }