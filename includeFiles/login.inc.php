<?php

    require 'dbh.inc.php';
    
    if (!isset($_POST['loginSubmit'])) {
         //Hiba
        header('location: ../login.php?error=loginRequired');
        exit();
    } 

        require 'functions/login.function.php';
        
        $userName = $_POST['userName'];
        $userPassword = $_POST['userPassword'];
        
         //Ellenőrzés
        if (empty($userName)) {
             //Hiba
            header('location: ../login.php?error=emptyUsernameField');
            exit();
        } elseif (empty($userPassword)) {
             //Hiba
            header('location: ../login.php?error=emtpyPasswordField&uID='. $userName);
            exit();
        } 
             
             //Funkció a beléptetésre
            loginUser($mysql, $userName, $userPassword);