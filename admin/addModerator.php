<?php
    // session_start();
    include '../includeFiles/profileQuery.inc.php';
    $userName = $_SESSION['userName'];
    $adminLevel = $_SESSION['adminLevel'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Moderator</title>
</head>
<body>
    <form action="../includeFiles/addNewAdministrator.inc.php" method="POST">
        <button type="submit">Új admin hozzáadása</button>
    </form>
</body>
</html>