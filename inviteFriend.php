<?php 
    // session_start();
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php?error=loginRequired');
        exit();
    }
    $user_id = $_SESSION['user_id'];
    findRefferal($mysql, $user_id);

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