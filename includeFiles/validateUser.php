<?php
    // session_start();

    $isValid = $_SESSION['isValid'];
    $isCoach = $_SESSION['isCoach'];
    $isAdmin = $_SESSION['isAdmin'];

    $userName = $_SESSION['userName'];

    if (!isset($userName)) {
         //Hiba
        header('location: login.php?error=loginRequired');
        exit();
    }

    