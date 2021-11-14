<?php
    require '../includeFiles/profileQuery.inc.php';

    if ($isAdmin == 0) {
        header('location: home.php?ID='. $_SESSION['user_id']);
    }
    $admin_id = $_SESSION['user_id'];

    $grabAdminLevel = $mysql -> prepare("SELECT `adminLevel` FROM `administration` WHERE `user_id` = ?");
    $grabAdminLevel -> bind_param('s', $admin_id);
    $grabAdminLevel -> execute();
    $getData = $grabAdminLevel -> get_result();
        if ($getData -> num_rows > 0) {
            while ($row = $getData -> fetch_assoc()) {
                $adminLevel = $row['adminLevel'];
            }
        }
    $grabAdminLevel -> close();

    if ($adminLevel == 1) {
        $adminName = 'CEO';
    } elseif ($adminLevel == 2) {
        $adminName = 'Super Admin';
    } elseif ($adminLevel == 3) {
        $adminName = 'Admin';
    } elseif ($adminLevel == 4) {
        $adminName = 'Moderator';
    } elseif ($adminLevel == 5) {
        $adminName = 'Employee';
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
        a {
            text-decoration: none;
        }
        table.eventTable {
            background: #1B1C22;
            border-radius: 10px;
            border: solid #121317 1px;
            padding: 5px;
            width: 99%;
            transition: all 80ms linear;
            margin: auto auto;
            margin-bottom: 5px;
        }
        table.eventTable:hover  {
            background: #121317;
        }
        table.eventTable img {
            width: 150px;
            border-radius: 5px;
            transition: all 200ms ease-in-out;
        }
        table.eventTable a,
        table.eventTable p {
            text-align: left;
            color: white;
            font-family: sans-serif;
        }
        table.eventTable a.title {
            font-size: 14px;
            
        }
        table.pastEvent {
            color: grey;
        }
        table.eventTable td {
            border-right: solid #ffffff 1px
        }
        select {
            margin-left: 20px;
            padding: 10px;
            width: 200px;
            font-size:20px;
            vertical-align:middle;
            background: transparent;
            border: none;
            color: teal;
        }
        select option {
            font-size: 20px;
            padding: 15px;
            background: #1B1C22;
            border: none;
            box-shadow: none;
            
        }
        table.qUpdate p {
            font-size: 20px !important;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event list</title>
</head>
<body>
    <?php 
        if ($adminLevel == 3) {
            echo '<h3>Az általad létrehozott események listája</h3>';
            $eventLister = $mysql -> prepare("SELECT * FROM `events` WHERE `creator_id` = ? ORDER BY `dateOfStart` DESC");
            $eventLister -> bind_param('s', $admin_id);
            $eventLister -> execute();
            $getData = $eventLister -> get_result();
            if ($getData -> num_rows > 0) {
                while ($row = $getData -> fetch_assoc()) {
                        echo '<table class="eventTable">';
                        echo '<tr>';
                        echo '<td style="width:160px">';
                        echo '<img src="../includeFiles/events/'. $row['event_id'] .'/eventBanner.jpg" alt="eventBanner">';
                        echo '</td>';
                        echo '<td style="width:400px">';
                        echo '<p><a class="title">'. $row['eventTitle'] .'</a><br/><a style="padding-left: 10px;color: #8D92A5;font-style: italic;">'. $row['eventDescriptionS'] .'</a></p>';
                        echo '</td>';
                        echo '<td style="width:250px">';
                        echo '<p style="text-align:center;font-size:15px;letter-spacing:1px;color:white;margin: 15px">Start: '. $row['dateOfStart'] .'<br/>Check-in: ';
                        if (strtotime($row['dateOfStart']) - strtotime($row['checkIn']) < 3600) {
                            echo date('i \M\i\n\s', strtotime($row['dateOfStart']) - strtotime($row['checkIn']));
                        } else {
                            echo '1 Hour';
                        }
                        echo'</p>';
                        echo '</td>';
                        echo '<td style="width:250px">';
                        echo '<a href="adminProfile.php?ID='. $row['creator_id'] .'"><p style="text-align:center;font-size:20px;letter-spacing:1px;">'. $row['creator_id'] .'</p></a>';
                        echo '</td>';
                        echo '<td style="width:200px">';
                        echo '<p style="text-align:center;font-size:15px;letter-spacing:1px;">'. $row['eventGame'] .'</p>';
                        echo '</td>';
                        echo '<td>';
                        echo '<form action="../includeFiles/eventEditor.inc.php" method="post">
                                <input type="hidden" name="event_id" value="'. $row['event_id'] .'">
                                <input type="hidden" name="admin_id" value="'. $admin_id .'">
                                <select name="eventStatus" id="eventStatus" onchange="this.form.submit()">';
                                    echo '<option style="color:grey;font-weight:300;font-style:italic" value="1" '; if ($row['eventStatus'] == 1) {echo 'selected="selected"';} echo '>Soon</option>';
                                    echo '<option style="color:gold;font-weight:600;font-style:italic" value="2" '; if ($row['eventStatus'] == 2) {echo 'selected="selected"';} echo '>Check-in</option>';
                                    echo '<option style="color:orange;font-weight:600;font-style:italic" value="3" '; if ($row['eventStatus'] == 3) {echo 'selected="selected"';} echo '>Starting soon</option>';
                                    echo '<option style="letter-spacing:2px;color:green;font-weight:800" value="4" '; if ($row['eventStatus'] == 4) {echo 'selected="selected"';} echo '>Live</option>';
                                    echo '<option style="letter-spacing:2px;color:red;font-weight:800" value="5" '; if ($row['eventStatus'] == 5) {echo 'selected="selected"';} echo '>Ended</option>';
                                echo '</select></form>';
                        echo '</td>';
                        echo '<td style="border-right:none;width:200px">';
                        echo '<a style="" href="../includeFiles\eventEditor.inc.php?event_id='. $row['event_id'] .'"><p style="text-align:center;color:grey;font-size:25px;font-weight:800;cursor:pointer;">E D I T</p></a>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</table>';
                }
            }
            $eventLister -> close();

        } elseif ($adminLevel == 1 || $adminLevel == 2) {
            echo '<h3>Lista az összes létrehozott eseményről</h3>';
            $eventLister = $mysql -> query("SELECT * FROM `events` ORDER BY `dateOfStart` DESC");
            if ($eventLister -> num_rows > 0) {
                while ($row = $eventLister -> fetch_assoc()) {
                        echo '<table class="eventTable">';
                        echo '<tr>';
                        echo '<td style="width:160px">';
                        echo '<img src="../includeFiles/events/'. $row['event_id'] .'/eventBanner.jpg" alt="eventBanner">';
                        echo '</td>';
                        echo '<td style="width:400px">';
                        echo '<p><a class="title">'. $row['eventTitle'] .'</a><br/><a style="padding-left: 10px;color: #8D92A5;font-style: italic;">'. $row['eventDescriptionS'] .'</a></p>';
                        echo '</td>';
                        echo '<td style="width:250px">';
                        echo '<p style="text-align:center;font-size:15px;letter-spacing:1px;color:white;margin: 15px">Start: '. $row['dateOfStart'] .'<br/>Check-in: ';
                        if (strtotime($row['dateOfStart']) - strtotime($row['checkIn']) < 3600) {
                            echo date('i \M\i\n\s', strtotime($row['dateOfStart']) - strtotime($row['checkIn']));
                        } else {
                            echo '1 Hour';
                        }
                        echo'</p>';
                        echo '</td>';
                        echo '<td style="width:250px">';
                        echo '<a href="adminProfile.php?ID='. $row['creator_id'] .'"><p style="text-align:center;font-size:20px;letter-spacing:1px;">'. $row['creator_id'] .'</p></a>';
                        echo '</td>';
                        echo '<td style="width:200px">';
                        echo '<p style="text-align:center;font-size:15px;letter-spacing:1px;">'. $row['eventGame'] .'</p>';
                        echo '</td>';
                        echo '<td>';
                        echo '<form action="../includeFiles/eventEditor.inc.php" method="post">
                                <input type="hidden" name="event_id" value="'. $row['event_id'] .'">
                                <input type="hidden" name="admin_id" value="'. $admin_id .'">
                                <select name="eventStatus" id="eventStatus" onchange="this.form.submit()">';
                                    echo '<option style="color:grey;font-weight:300;font-style:italic" value="1" '; if ($row['eventStatus'] == 1) {echo 'selected="selected"';} echo '>Soon</option>';
                                    echo '<option style="color:gold;font-weight:600;font-style:italic" value="2" '; if ($row['eventStatus'] == 2) {echo 'selected="selected"';} echo '>Check-in</option>';
                                    echo '<option style="color:orange;font-weight:600;font-style:italic" value="3" '; if ($row['eventStatus'] == 3) {echo 'selected="selected"';} echo '>Starting soon</option>';
                                    echo '<option style="letter-spacing:2px;color:green;font-weight:800" value="4" '; if ($row['eventStatus'] == 4) {echo 'selected="selected"';} echo '>Live</option>';
                                    echo '<option style="letter-spacing:2px;color:red;font-weight:800" value="5" '; if ($row['eventStatus'] == 5) {echo 'selected="selected"';} echo '>Ended</option>';
                                echo '</select></form>';
                        echo '</td>';
                        echo '<td style="border-right:none;width:250px">';
                        //Ha kevesebb mint 1 nap van kezdésig ÉS még korábban van mint check-in és "0" státuszban van
                        $eventQupdateStmt1 = (((strtotime('-1 day', strtotime($row['dateOfStart'])) <= strtotime('now')) && (strtotime('now')) < strtotime($row['checkIn'])) && $row['eventStatus'] == 0);
                        // Ha több mint 1 nap van kezdésig és nem "0" státuszban van
                        $eventQupdateStmt2 = ((strtotime('-1 day', strtotime($row['dateOfStart'])) > strtotime('now')) && $row['eventStatus'] != 0);
                        //Ha check-in alatt van de nem "2" a státusz
                        $eventQupdateStmt3 = (((strtotime($row['checkIn']) <= strtotime('now')) && (strtotime($row['dateOfStart']) >= strtotime('now'))) && $row['eventStatus'] != 2);
                        //Ha 10 perce mennie kellene de még mindíg "3" státuszban van
                        $eventQupdateStmt4 = ((strtotime('+10 minutes', strtotime($row['dateOfStart'])) < strtotime('now')) && $row['eventStatus'] == 3);
                        //Ha már el kellett volna kezdődnie de "0"/"1"/"2" státuszban van
                        $eventQupdateStmt5 = ((strtotime($row['dateOfStart']) < strtotime('now')) && $row['eventStatus'] != 3 && $row['eventStatus'] != 4 && $row['eventStatus'] != 5);
                        //Ha 1 hét eltelt a verseny vége óta és nincs "5" státuszban
                        $eventQupdateStmt6 = ((strtotime('+1 week', strtotime($row['dateOfStart'])) < strtotime('now')) && $row['eventStatus'] != 5);

                        if ($eventQupdateStmt1 || $eventQupdateStmt2 || $eventQupdateStmt3 || $eventQupdateStmt4 || $eventQupdateStmt5 || $eventQupdateStmt6) {
                            
                            echo '<table class="qUpdate"><tr>
                                <p style="color:#dadfe1;font-weight:700;padding:10px;text-align:center;margin-bottom:0;margin-top:0">Requires status update!</p>
                            </tr>';
                            echo '<tr>
                            <form action="../includeFiles\eventEditor.inc.php?event_id='. $row['event_id'] .'" method="post"><input style="background:transparent;border:none;color:grey;cursor:pointer;font-weight:800;font-size:20px;width:100%;padding-top:0;height: 25px" type="submit" value="Qucik update" name="qUpdateSubmit"></form>
                            </tr></table>';

                        }
                        echo '</td>';
                        echo '<td style="border-right:none;width:200px">';
                        echo '<a style="" href="../includeFiles\eventEditor.inc.php?event_id='. $row['event_id'] .'"><p style="text-align:center;color:grey;font-size:25px;font-weight:800;cursor:pointer;">E D I T</p></a>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</table>';
                }
                echo '</table>';
            }
            $eventLister -> close();
        }
    ?>
</body>
</html>