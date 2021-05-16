<?php

    require 'functions/login.function.php';

    if (!isset($_POST['loginSubmit'])) {
         //Hiba
        header('location: ../login/loginRequired');
        exit();
    } 
        
        $userName = $_POST['userName'];
        $userPassword = $_POST['userPassword'];
        
         //Ellenőrzés
        if (empty($userName)) {
             //Hiba
            header('location: ../login/emptyUsernameField');
            exit();
        } elseif (empty($userPassword)) {
             //Hiba
            header('location: ../login/emtpyPasswordField/'. $userName);
            exit();
        } 
             
             //Funkció a beléptetésre
            loginUser($mysql, $userName, $userPassword);