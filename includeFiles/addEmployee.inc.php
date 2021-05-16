<?php

    // session_start();
    $userName = $_SESSION['userName'];
    include 'dbh.inc.php';

    $addEmployee = $mysql -> prepare("INSERT INTO `administration` (`adminEmail`, `AdminLevel`)
                                    VALUES ()");