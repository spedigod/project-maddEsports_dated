<?php

    require 'main.function.php';

    function registerUser($mysql, $userName, $userEmail, $userPwdHashed, $userFirstName, $userLastName, $refferalCode, $isAdmin, $userLevel, $inGroup, $userGroup, $isValid, $isCoach) {
        $invaliduserName = invaliduserName($userName);
        if ( $invaliduserName == 1) {
            //Hiba
            if (isset($refferalCode)) {
                header('location: ../registration.php?error=inValiduserName&userEmail='. $userEmail.'&userFirstName='. $userFirstName.'&userLastName='. $userLastName.'&inviteCode='. $refferalCode);
                exit();
            }
           header('location: ../registration.php?error=inValiduserName&userEmail='. $userEmail.'&userFirstName='. $userFirstName.'&userLastName='. $userLastName);
           exit();
       }
       $userExists = userExists($mysql, $userName, $userEmail);
         //Hiba
        if ($userExists == "case1" || $userExists == "case2") {
             if ($userExists == "case1") {
                 # felh.név egyezés
                if (!empty($refferalCode)) {
                    header('location: ../registration.php?error=userNameAlreadyExists&userEmail='. $userEmail .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName .'&inviteCode='. $refferalCode);
                    exit();
                }
                header('location: ../registration.php?error=userNameAlreadyExists&userEmail='. $userEmail .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName);
                exit();
            } elseif ($userExists == "case2") {
                 # email egyezés
                if (!empty($refferalCode)) {
                    header('location: ../registration.php?error=userEmailAlreadyExists&userName='. $userName .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName .'&inviteCode='. $refferalCode);
                    exit();
                }
                header('location: ../registration.php?error=userEmailAlreadyExists&userName='. $userName .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName);
                exit();
            }
        }
        $userEmailInvalid = userEmailInvalid($userEmail);
        if ($userEmailInvalid == 1) {
             //Hiba
            header('location: ../registration.php?error=invalidEmailFormat');
            exit();
        }

        if (!empty($refferalCode)) {
            $testRefferal = $mysql -> prepare("SELECT * FROM `refferals` WHERE `refferalCode` = ?");
            $testRefferal -> bind_param('s', $refferalCode);
            $testRefferal -> execute();
            $getData = $testRefferal -> get_result();
            if ($getData -> num_rows > 0) {
                while ($row = $getData -> fetch_assoc()) {
                    $refferalScore = $row['refferalScore'];
                    
                    $refferalScore = $refferalScore + 10;
                    $addRefferalScore = $mysql -> prepare("UPDATE `refferals` SET `refferalScore` = ? WHERE `refferalCode` = ?");
                    $addRefferalScore -> bind_param('is', $refferalScore, $refferalCode);
                    $addRefferalScore -> execute();
                    $addRefferalScore -> close();
                }
            }
            $testRefferal -> close();
            header('location: ../registration.php?error=invalidRefferalCode&userName='. $userName .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName .'&userEmail='. $userEmail);
            exit();
        }

         //Nincs hiba
        $stmt = $mysql -> prepare('INSERT INTO `users` (`userName`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `usedRefferal`, `isAdmin`, `userLevel`, `inGroup`, `userGroup`, `isValid`, `isCoach`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt -> bind_param('ssssssiiisii', $userName, $userEmail, $userPwdHashed, $userFirstName, $userLastName, $refferalCode, $isAdmin, $userLevel, $inGroup, $userGroup, $isValid, $isCoach);
        $stmt -> execute();
        /*
        if (!$stmt -> execute()) {
            echo("Error description: " . $mysql -> error);
        }
        */
        $stmt -> close();
        
        $n = 10;
        getRefferalCode($n, $mysql, $userName, $refferalCode);

        $level = 1;
        $experiencePoints = 1000;
        $userExp = 0;
        setUserLevel($mysql, $userName, $level, $userExp, $experiencePoints);

        

    }