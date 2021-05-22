<?php
    $_SESSION['notification'] = 0;
    include 'dbh.inc.php';

    $requests = $mysql -> prepare("SELECT * FROM `friendrequests` WHERE `requestTo` = ?");
    $requests -> bind_param('s', $userName);
    $requests -> execute();
    $getData = $requests -> get_result();
    if ($getData -> num_rows > 0) {
        $notifications = $getData -> num_rows;
        while ($row = $getData -> fetch_assoc()) {
            $_SESSION['notification'] += 1;
        } 
    }
    $requests -> close();