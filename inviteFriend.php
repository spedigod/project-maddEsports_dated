<?php 
    // session_start();
    if (!isset($_SESSION['userName'])) {
        header('location: login/loginRequired');
        exit();
    }
    $userName = $_SESSION['userName'];
    findRefferal($mysql, $userName);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Your Friends</title>
</head>
<body>
    
</body>
</html>