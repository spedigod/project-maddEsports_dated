<?php 
    
    /*if (isset($_SESSION['userName'])) {
        header('location: includeFiles\redirecter.inc.php');
        exit();
    }*/
    
    if ($isCoach == 0 and $isAdmin == 0) {
        header('location: home/'. $userName);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>