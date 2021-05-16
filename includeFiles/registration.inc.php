<?php

    if (!isset($_POST['regSubmit'])) {
         //Hiba
        header('location: login/registrationRequired');
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
           header('location: registration/emptyUserNameField');
           exit();
       }
       if (empty($userEmail)) {
            //Üres Email
           header('location: registration/emptyEmailField');
           exit();
       }
       if (empty($userPassword)) {
            //Üres Jelszó
           header('location: registration/emptyPasswordField');
           exit();
       }
       if (empty($passwordCheck)) {
            //Üres Jelszó2
           header('location: registration/emptyPasswordFerifField');
           exit();
       }
       if ($userPassword != $passwordCheck) {
            //Jelszavak nem egyeznek
           header('location: registration/passwordsDontMatch');
           exit();
       }
       if (empty($userFirstName or empty($userLastName))) {
            //Nincs megadva név
           header('lcoation: registration/emptyNameFields');
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