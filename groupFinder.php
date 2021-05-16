<?php 

    // session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find A Group</title>
</head>
<body>
<section>
    <?php if ($_SESSION['inGroup'] == 0) {
        include 'includeFiles/createGroup.inc.php';
    } if ($_SESSION['inGroup'] == 1) {
        echo '<p>Már tagja vagy egy csapatnak</p>';
    }
    ?>
</section>
</body>
</html>