<?php
    // session_start();

    $userName = $_SESSION['userName'];

    include 'functions/main.function.php';

    $stmt = $mysql -> prepare('SELECT `inGroup` FROM `users` WHERE `userName` = ?');
    $stmt -> bind_param('s', $userName);
    $stmt -> execute();

    $result = $stmt -> get_result();
    if ($result = 0) {
        $_SESSION['inGroup'] = 0;
    }