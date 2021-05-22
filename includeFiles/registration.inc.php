<?php

    require 'dbh.inc.php';


    if (!isset($_POST['regSubmit'])) {
         //Hiba
        header('location: ../login.php?error=registrationRequired');
        exit();
    }

        include 'functions/registration.function.php';

        $userName = $_POST['userName'];
        $userEmail = $_POST['userEmail'];
        $userPassword = $_POST['userPassword'];
        $passwordCheck = $_POST['passwordCheck'];
        $userFirstName = $_POST['userFirstName'];
        $userLastName = $_POST['userLastName'];
        $refferalCode = $_POST['refferalCode'];

        if (empty($userName)) {
            //Üres felh.
           header('location: ../registration.php?error=emptyUserNameField');
           exit();
       }
       if (empty($userEmail)) {
            //Üres Email
           header('location: ../registration.php?error=emptyEmailField&userName='. $userName);
           exit();
       }
       if (empty($userPassword)) {
            //Üres Jelszó
           header('location: ../registration.php?error=emptyPasswordField&userName='. $userName.'&userEmail='. $userEmail);
           exit();
       }
       if (empty($passwordCheck)) {
            //Üres Jelszó2
           header('location: ../registration.php?error=emptyPasswordFerifField&userName='. $userName.'&userEmail='. $userEmail);
           exit();
       }
       if ($userPassword != $passwordCheck) {
            //Jelszavak nem egyeznek
           header('location: ../registration.php?error=passwordsDontMatch&userName='. $userName.'&userEmail='. $userEmail);
           exit();
       }
       if (empty($userFirstName or empty($userLastName))) {
            //Nincs megadva név
           header('lcoation: ../registration.php?error=emptyNameFields&userName='. $userName.'&userEmail='. $userEmail);
           exit();
       }
       if (empty($refferalCode)) {
           $refferalCode = '';
       }

        $userPwdHashed = password_hash($userPassword, PASSWORD_DEFAULT);

        $isAdmin = 0;
        $userLevel = 1;
        $inGroup = 0;
        $userGroup = '';
        $isValid = 1;
        $isCoach = 0;

        registerUser($mysql, $userName, $userEmail, $userPwdHashed, $userFirstName, $userLastName, $refferalCode, $isAdmin, $userLevel, $inGroup, $userGroup, $isValid, $isCoach);
        header('location: ../login.php?registration=success&uID='. $userName);
        exit();
        