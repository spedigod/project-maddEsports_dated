<?php

    require 'main.function.php';

    function loginUser($mysql, $userName, $userPassword) {
         //Teszt, hogy létezik e a felhasználó
        $userCheck = userLogCheck($mysql, $userName, $userPassword);
        if ($userCheck == false) {
            header('location: ../login.php?error=userNotFound');
            exit();
        } 
         //Jelszó ellenőrzése
        $stmt = $mysql -> prepare('SELECT `userPassword` FROM `users` WHERE `userName` = ?');
        $stmt -> bind_param('s', $userName);
        $stmt -> execute();

        $result = $stmt -> get_result();
        while ($row = $result -> fetch_assoc()) {
            $userPwdHashed = $row['userPassword'];
        }
         //Jelszó ellenőrzése
        $checkPassword = password_verify($userPassword, $userPwdHashed);
        if ($checkPassword == 0) {
           //Hiba
          header('location: ../login.php?error=wrongPassword?uID='. $userName);
          exit();
        }

         //Nincs hiba -> tobábbi ellenőrzések
         // session_start();
        $_SESSION['userName'] = $userName;
        
         //Rang ellenőrzése
        $stmt = $mysql -> prepare('SELECT * FROM `users` WHERE `userName` = ?');
        $stmt -> bind_param('s', $userName);
        $stmt -> execute();

        $result = $stmt -> get_result();
        while ($row = $result -> fetch_assoc()) {
            $isAdmin = $_SESSION['isAdmin'] = $row['isAdmin'];
            $isCoach = $_SESSION['isCoach'] = $row['isCoach'];
            $isValid = $_SESSION['isValid'] = $row['isValid'];
        }
        //Érték alapján átirányítás
        /**if ($isAdmin == 1) {
            header('location: ../home');
            exit();
        } else if ($isCoach == 1) {
            header('location: ../home');
            exit();
        } else if ($isValid == 1) {
            header('location: ../home');
            exit();
        } else {
            header('location: ../home/verifyEmail');
            exit();
        } */
        header('location: ../home.php');
        exit();
    }