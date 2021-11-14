<?php 
    include_once 'includeFiles/dbh.inc.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            background: #1B1C22;
        }
        a {
            text-decoration: none;
        }
        table.eventTable {
            border-radius: 10px;
            padding: 5px;
            width: 1100px;
            transition: all 80ms linear;
            margin: auto auto;
        }
        table.eventTable:hover {
            background: #121317;
        }
        table.eventTable img {
            width: 150px;
            border-radius: 5px;
        }
        table.eventTable a,
        table.eventTable p {
            text-align: left;
            color: white;
            font-family: sans-serif;
        }
        table.eventTable a.title {
            font-size: 25px;
            font-weight: bold;
            
        }
        table.pastEvent {
            color: grey;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Események</title>
</head>
<body>
    <?php
        // 0 = upcoming
        // 1 = soon
        // 2 = check-in
        // 3 = starting soon
        // 4 = live
        // 5 = ended
        $eventLister = $mysql -> prepare("SELECT * FROM `events` ORDER BY `dateOfStart` DESC");
        $eventLister -> execute();
        $eventList = $eventLister -> get_result();
        if ($eventList -> num_rows > 0) {
            
            while ($row = $eventList -> fetch_assoc()) {
                //upcoming
                if ($row['eventStatus'] == 0 || $row['eventStatus'] == 1 || $row['eventStatus'] == 2 || $row['eventStatus'] == 3 || $row['eventStatus'] == 4) {
                    echo '<a href="event.php?ID='. $row['event_id'] .'"><table class="eventTable">';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td style="width:160px">';
                    echo '<a href="event.php?ID='. $row['event_id'] .'"><img src="includeFiles/events/'. $row['event_id'] .'/eventBanner.jpg" alt="eventBanner"></a>';
                    echo '</td>';
                    echo '<td>';
                    echo '<p><a class="title">'. $row['eventTitle'] .'</a><br/><a style="padding-left: 10px;color: #8D92A5;font-style: italic;">'. $row['eventDescriptionS'] .'</a></p>';
                    echo '</td>';
                    echo '<td style="width:130px">';
                    if ($row['eventStatus'] == 0) {
                        echo '<p>'. date('l \a\t g:ia T', strtotime($row['dateOfStart'])) .'</p>';
                    } elseif ($row['eventStatus'] == 1) {
                        echo '<p style="color:white;font-weight:300;font-style:italic">Soon<br/></p>';
                    } elseif ($row['eventStatus'] == 2) {
                        echo '<p style="color:gold;font-weight:600;font-style:italic">Check-in <br/>'. date('i \M\i\n\s', (strtotime($row['dateOfStart']) - strtotime('now'))) .'</p>';
                    } elseif ($row['eventStatus'] == 3) {
                        echo '<p style="color:orange;font-weight:600;font-style:italic">Starting soon<br/></p>';
                    } elseif ($row['eventStatus'] == 4) {
                        echo '<p style="font-size:20px;letter-spacing:2px;color:green;font-weight:800">Live •<br/></p>';
                    };
                    echo '</td>';
                    echo '</tr>';
                    echo '</table></a>';
                }
                if ($row['eventStatus'] == 5) {
                    echo '<a href="event.php?ID='. $row['event_id'] .'"><table class="eventTable">';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td style="width:160px">';
                    echo '<a href="event.php?ID='. $row['event_id'] .'"><img src="includeFiles/events/'. $row['event_id'] .'/eventBanner.jpg" alt="eventBanner"></a>';
                    echo '</td>';
                    echo '<td>';
                    echo '<p><a class="title">'. $row['eventTitle'] .'</a><br/><a style="padding-left: 10px;color: #8D92A5;font-style: italic;">'. $row['eventDescriptionS'] .'</a></p>';
                    echo '</td>';
                    echo '<td style="width:130px">';
                    echo '<p style="font-size:22px;letter-spacing:1px;color:red;font-weight:600"">Ended</p>';
                    echo '</td>';
                    echo '</tr>';
                    echo '</table></a>';
                }
                
            }
            
        }
    
    ?>
    <img src="" alt="">
</body>
</html>