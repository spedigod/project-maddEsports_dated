<?php
    if ($userExp >= $expPoints) {
        $newUserLevel = $userLevel + 1;
        $expReset = $userExp - $expPoints;
        $newExpPoints = 1000;
        switch ($newExpPoints) {
            case $newUserLevel == 2:
                $newExpPoints = 2000;
                break;
            case $newUserLevel == 3:
                $newExpPoints = 4000;
                break;
            case $newUserLevel == 4:
                $newExpPoints = 6000;
                break;
            case $newUserLevel == 5:
                $newExpPoints = 8000;
                break;
            case $newUserLevel == 6:
                $newExpPoints = 10000;
                break;
            case $newUserLevel == 7:
                $newExpPoints = 12000;
                break;
            case $newUserLevel == 8:
                $newExpPoints = 14000;
                break;
            case $newUserLevel == 9:
                $newExpPoints = 16000;
                break;
            case $newUserLevel == 10:
                $newExpPoints = 18000;
                break;
            case $newUserLevel == 11:
                $newExpPoints = 20000;
                break;
            
            default:
                $newExpPoints = 1000;
                break;
        }
        $setLevel = $mysql -> prepare("UPDATE `userLevel` SET `userLevel` = ?, `userExp` = ?, `experiencePoints` = ? WHERE `user_id` = ?");
        $setLevel -> bind_param('iiis', $newUserLevel, $expReset, $newExpPoints, $user_id);
        $setLevel -> execute();
        $setLevel -> close();

        $setUserLevelCount = $mysql -> prepare("UPDATE `userData` SET `levelCount` = ? WHERE `user_id` = ?");
        $setUserLevelCount -> bind_param('ss', $newUserLevel, $_SESSION['user_id']);
        $setUserLevelCount -> execute();
        $setUserLevelCount -> close();
        
         # get user level after updating
        $setUserLevel = $mysql -> prepare("SELECT * FROM `userLevel` WHERE `user_id` = ?");
        $setUserLevel -> bind_param('s', $user_id);
        $setUserLevel -> execute();
        $getData = $setUserLevel -> get_result();
        if ($getData -> num_rows > 0) {
            while ($row = $getData -> fetch_assoc()) {
                $userLevel = $row['userLevel'];
                $userExp = $row['userExp'];
                $expPoints = $row['experiencePoints'];
            }
        }
        $setUserLevel -> close();
        $_SESSION['notification'] += 1;
    }
    $userExpValue = ($userExp / $expPoints) * 100;
    $expPointsValue = $expPoints;
    switch ($expPointsValue) {
        case $expPoints = 1000:
            $expPointsValue = $expPoints * 0.1;
            break;
        case $expPoints = 2000:
            $expPointsValue = ($expPoints * 0.1) / 2;
            break;
        case $expPoints = 5000:
            $expPointsValue = ($expPoints * 0.01) * 2;
            break;
        case $expPoints = 10000:
            $expPointsValue = $expPoints * 0.01;
            break;
        case $expPoints = 20000:
            $expPointsValue = ($expPoints * 0.01) / 2 ;
            break;
        
        default:
            $expPointsValue = 100;
            break;
    }