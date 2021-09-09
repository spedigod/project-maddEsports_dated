<?php

    require 'main.function.php';

    function loginUser($mysql, $userName, $userPassword) {

         //Teszt, hogy létezik e a felhasználó
        $userCheck = userLogCheck($mysql, $userName);
        if ($userCheck == false) {
            header('location: ../login.php?error=userNotFound');
            exit();
        } 

         //Jelszó ellenőrzése
        $grabPassword = $mysql -> prepare('SELECT `userPassword` FROM `users` WHERE `userName` = ?');
        $grabPassword -> bind_param('s', $userName);
        $grabPassword -> execute();

        $result = $grabPassword -> get_result();
        while ($row = $result -> fetch_assoc()) {
            $userPwdHashed = $row['userPassword'];
        }
        $grabPassword -> close();

         //Jelszó ellenőrzése
        $checkPassword = password_verify($userPassword, $userPwdHashed);
        if ($checkPassword == 0) {
           //Hiba
          header('location: ../login.php?error=wrongPassword&uID='. $userName);
          exit();
        }

         //Nincs hiba -> tobábbi ellenőrzések
         // session_start();
        
         //Rang ellenőrzése és user_id megszerzése
        $grabInfo = $mysql -> prepare('SELECT * FROM `users` WHERE `userName` = ?');
        $grabInfo -> bind_param('s', $userName);
        $grabInfo -> execute();

        $result = $grabInfo -> get_result();
        while ($row = $result -> fetch_assoc()) {
            $isAdmin = $_SESSION['isAdmin'] = $row['isAdmin'];
            $isCoach = $_SESSION['isCoach'] = $row['isCoach'];
            $isValid = $_SESSION['isValid'] = $row['isValid'];
            $user_id = $_SESSION['user_id'] = $row['user_id'];
        }
        $grabInfo -> close();

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