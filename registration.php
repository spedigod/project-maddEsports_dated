<?php
    if (isset($_GET['uID'])) {
        $userName = $_GET['uID'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
</head>
<body>
    <form action="includeFiles/registration.inc.php" method="post">
        <input type="text" name="userName" id="userName" placeholder="username" required>
        <input type="email" name="userEmail" id="userEmail" placeholder="email">
        <input type="password" name="userPassword" id="userPassword" placeholder="password">
        <input type="password" name="passwordCheck" id="passwordCheck" placeholder="password again">
        <input type="text" name="userFirstName" id="userFirstName" placeholder="first name">
        <input type="text" name="userLastName" id="userLastName" placeholder="last name">
        <input type="text" name="refferalCode" id="refferalCode" placeholder="Refferal code (optional)">
        <input type="submit" name="regSubmit" id="regSubmit">
    </form>
</body>
</html>

