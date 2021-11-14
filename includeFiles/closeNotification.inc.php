<?php

    unset($_SESSION['notifications'][$_POST['arrayValue']]);
    $_SESSION['notification'] -= 1;
    header('location: ../home.php');