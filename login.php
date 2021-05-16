<?php
    if (isset($_SESSION['userName'])) {
        header('location: home.php');
        exit();
    }

    if (isset($_GET['uID'])) {
        $userName = $_GET['uID'];
    } else {
        $userName = '';
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
    <form action="includeFiles/login.inc.php" method="post">
        <input type="text" name="userName" id="userName" value="<?php echo $userName ?>" placeholder="Felhasználónév">
        <input type="password" name="userPassword" id="userPassword" placeholder="Jelszó">

        <input type="submit" name="loginSubmit" id="loginSubmit">
    </form>
</body>
</html>