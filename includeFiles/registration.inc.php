<?php

    if (!isset($_POST['regSubmit'])) {
         //Hiba
        header('location: ../registration.php?error=registrationRequired');
        exit();
    }

        require 'dbh.inc.php';
        require 'functions/registration.function.php';

        if (empty($_POST['userName'])) {
            //Üres felh.
           header('location: ../registration.php?error=emptyUserNameField');
           exit();
       }
       if (empty($_POST['userEmail'])) {
            //Üres Email
           header('location: ../registration.php?error=emptyEmailField&userName='. $_POST['userName']);
           exit();
       }
       if (empty($_POST['userPassword'])) {
            //Üres Jelszó
           header('location: ../registration.php?error=emptyPasswordField&userName='. $_POST['userName'].'&userEmail='. $_POST['userEmail']);
           exit();
       }
       if (empty($_POST['passwordCheck'])) {
            //Üres Jelszó2
           header('location: ../registration.php?error=emptyPasswordFerifField&userName='. $_POST['userName'].'&userEmail='. $_POST['userEmail']);
           exit();
       }
       if ($_POST['userPassword'] != $_POST['passwordCheck']) {
            //Jelszavak nem egyeznek
           header('location: ../registration.php?error=passwordsDontMatch&userName='. $_POST['userName'].'&userEmail='. $_POST['userEmail']);
           exit();
       }
       if (empty($_POST['userFirstName'] or empty($_POST['userLastName']))) {
            //Nincs megadva név
           header('lcoation: ../registration.php?error=emptyNameFields&userName='. $_POST['userName'].'&userEmail='. $_POST['userEmail']);
           exit();
       }
       if (empty($_POST['refferalCode'])) {
           $_POST['refferalCode'] = '';
       }

         # felhasználó regisztrálása
        registerUser($mysql, $_POST['userName'], $_POST['userEmail'], $_POST['userPassword'], $_POST['userFirstName'], $_POST['userLastName'], $_POST['refferalCode']);

         # sikeres regisztráció utáni átirányítás
        header('location: ../login.php?registration=success&uID='. $_POST['userName']);
        exit();
        