<?php 

    require 'dbh.inc.php';

    function invaliduserName($userName) {
        $result;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $userName)) {
          $result = true;
        } else {
          $result = false;
        }
        return $result;
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
        $stmt = $mysql -> prepare('SELECT userName FROM users WHERE userName = ?');
        $stmt -> bind_param('s', $userName);

        $stmt -> execute();

        $getData = $stmt -> get_result();
        if ($getData -> num_rows == 1) {
            return true;
            header('location: login.inc.php');
            exit();
        } else {
            return false;
            header('location: login.inc.php');
            exit();
        }
        header('location: login.inc.php');
        exit();
    }

    function userExists($mysql, $userName) {
        $stmt = $mysql -> prepare('SELECT userName FROM users WHERE userName = ?');
        $stmt -> bind_param('s', $userName);

        $stmt -> execute();

        $getData = $stmt -> get_result();
        if ($getData -> num_rows == 1) {
            return true;
            header('location: registration.inc.php');
            exit();
        } else {
            return false;
            header('location: registration.inc.php');
            exit();
        }
        header('location: registration.inc.php');
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
        $stmt = $mysql -> prepare("SELECT * FROM refferals WHERE refferalCode = ?");
        $stmt -> bind_param('s', $refferalCode);
        $stmt -> execute();

        $getData = $stmt -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
      }
      $stmt = $mysql -> prepare("INSERT INTO refferals (`userName`, `refferalCode`) 
      VALUES (?, ?)");
      $stmt -> bind_param('ss', $userName, $refferalCode);
      $stmt -> execute();
      $stmt -> close();   
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
        $stmt = $mysql -> prepare("SELECT publicRequestID FROM friendrequests WHERE publicRequestID = ?");
        $stmt -> bind_param('s', $randomString);
        $stmt -> execute();

        $getData = $stmt -> get_result();
        if ($getData -> num_rows == 0) {
            break;
        }
      }
      return $randomString;
    }