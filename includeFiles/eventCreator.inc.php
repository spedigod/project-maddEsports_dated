<?php

if (isset($_POST['createEvent'])) {
    include_once 'functions/main.function.php';
    include_once 'dbh.inc.php';

    $allowedExt = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'txt', 'TXT');    
    $event_id = generateEventID($mysql);

    if (!file_exists('events/'. $event_id)) {
        mkdir('events/'. $event_id, 0777, true);
    }

    if (empty($_POST['eventTitle'])) {
        header('location: ../eventCreator.php?error=missingTitle');
        exit();
    } else {
        $eventTitle = $_POST['eventTitle'];
    }
    if (empty($_POST['eventGame'])) {
        header('location: ../eventCreator.php?error=missingGameType');
        exit();
    } else {
        $eventGame = $_POST['eventGame'];
    }
    if (!empty($_FILES['eventBanner'])) {
        $eventBanner = $_FILES['eventBanner'];
        $eventBannerName = $_FILES['eventBanner']['name'];
        $eventBannerTmpName = $_FILES['eventBanner']['tmp_name'];
        $eventBannerSize = $_FILES['eventBanner']['size'];
        $eventBannerError = $_FILES['eventBanner']['error'];
        $eventBannerType = $_FILES['eventBanner']['type'];

        $eventBannerExt = explode('.', $eventBannerName);
        $eventBannerActualExt = strtolower(end($eventBannerExt));
        if (in_array($eventBannerActualExt, $allowedExt)) {
            if ($eventBannerError == 0) {
                if ($eventBannerSize < 8000000) {
                    $eventBannerActualName = 'eventBanner.'. $eventBannerActualExt;
                    $destination = 'events/'. $event_id .'/'. $eventBannerActualName;
                    if (!move_uploaded_file($eventBannerTmpName, $destination)) {
                        header('location: ../eventCreator.php?error=erroreventBanner');
                        exit();
                    }
                } else {
                    header('location: ../eventCreator.php?error=fileTooLarge');
                    exit();
                }
            } else {
                header('location: ../eventCreator.php?error=uploadError');
                exit();
            }
        } else {
            header('location: ../eventCreator.php?error=extError');
            exit();
        }
        $eventBanner = 1;
    } else {
        $eventBanner = 0;
    }
    if (empty($_POST['eventSmallDescription'])) {
        header('location: ../eventCreator.php?error=missingSmallDescription');
        exit();
    } else {
        $eventSmallDescription = $_POST['eventSmallDescription'];
    }
    if (!empty($_FILES['eventDescription'])) {
        $eventDescription = $_FILES['eventDescription'];
        $eventDescriptionName = $_FILES['eventDescription']['name'];
        $eventDescriptionTmpName = $_FILES['eventDescription']['tmp_name'];
        $eventDescriptionSize = $_FILES['eventDescription']['size'];
        $eventDescriptionError = $_FILES['eventDescription']['error'];
        $eventDescriptionType = $_FILES['eventDescription']['type'];
        $eventDescriptionExt = explode('.', $eventDescriptionName);
        $eventDescriptionActualExt = strtolower(end($eventDescriptionExt));
        if (in_array($eventDescriptionActualExt, $allowedExt)) {
            if ($eventDescriptionError == 0) {
                if ($eventDescriptionSize < 1000000) {
                    $eventDescriptionActualName = 'eventDescription.'. $eventDescriptionActualExt;
                    $destination = 'events/'. $event_id .'/'. $eventDescriptionActualName;
                    if (!move_uploaded_file($eventDescriptionTmpName, $destination)) {
                        header('location: ../eventCreator.php?error=erroreventDescription');
                        exit();
                    }
                } else {
                    header('location: ../eventCreator.php?error=fileTooLarge');
                    exit();
                }
            } else {
                header('location: ../eventCreator.php?error=uploadError');
                exit();
            }
        } else {
            header('location: ../eventCreator.php?error=extError');
            exit();
        }
        $eventDescription = 1;
    } else {
        $eventDescription = 0;
    }
    if (!empty($_FILES['eventBackground'])) {
        $eventBackground = $_FILES['eventBackground'];
        $eventBackgroundName = $_FILES['eventBackground']['name'];
        $eventBackgroundTmpName = $_FILES['eventBackground']['tmp_name'];
        $eventBackgroundSize = $_FILES['eventBackground']['size'];
        $eventBackgroundError = $_FILES['eventBackground']['error'];
        $eventBackgroundType = $_FILES['eventBackground']['type'];

        $eventBackgroundExt = explode('.', $eventBackgroundName);
        $eventBackgroundActualExt = strtolower(end($eventBackgroundExt));
        if (in_array($eventBackgroundActualExt, $allowedExt)) {
            if ($eventBackgroundError == 0) {
                if ($eventBackgroundSize < 10000000) {
                    $eventBackgroundActualName = 'eventBackground.'. $eventBackgroundActualExt;
                    $destination = 'events/'. $event_id .'/'. $eventBackgroundActualName;
                    if (!move_uploaded_file($eventBackgroundTmpName, $destination)) {
                        header('location: ../eventCreator.php?error=erroreventBackground');
                        exit();
                    }
                } else {
                    header('location: ../eventCreator.php?error=fileTooLarge');
                    exit();
                }
            } else {
                header('location: ../eventCreator.php?error=uploadError');
                exit();
            }
        } else {
            header('location: ../eventCreator.php?error=extError');
            exit();
        }
        $eventBackground = 1;
    } else {
        $eventBackground = 0;
    }
    if (!empty($_FILES['eventSettings'])) {
        $eventSettings = $_FILES['eventSettings'];
        $eventSettingsName = $_FILES['eventSettings']['name'];
        $eventSettingsTmpName = $_FILES['eventSettings']['tmp_name'];
        $eventSettingsSize = $_FILES['eventSettings']['size'];
        $eventSettingsError = $_FILES['eventSettings']['error'];
        $eventSettingsType = $_FILES['eventSettings']['type'];

        $eventSettingsExt = explode('.', $eventSettingsName);
        $eventSettingsActualExt = strtolower(end($eventSettingsExt));
        if (in_array($eventSettingsActualExt, $allowedExt)) {
            if ($eventSettingsError == 0) {
                if ($eventSettingsSize < 1000000) {
                    $eventSettingsActualName = 'eventSettings.'. $eventSettingsActualExt;
                    $destination = 'events/'. $event_id .'/'. $eventSettingsActualName;
                    if (!move_uploaded_file($eventSettingsTmpName, $destination)) {
                        header('location: ../eventCreator.php?error=erroreventSettings');
                        exit();
                    }
                } else {
                    header('location: ../eventCreator.php?error=fileTooLarge');
                    exit();
                }
            } else {
                header('location: ../eventCreator.php?error=uploadError');
                exit();
            }
        } else {
            header('location: ../eventCreator.php?error=extError');
            exit();
        }
        $eventSettings = 1;
    }
    if (empty($_POST['prizePool'])) {
        $prizePool = 0;
    } else {
        $prizePool = $_POST['prizePool'];
    }
    if (empty($_POST['eventStart'])) {
        $dateOfStart = '0000-00-00 00:00';
    } else {
        $dateOfStart = $_POST['eventStart'];
    }
    if (!empty($_POST['checkIn'])) {
        if ($_POST['checkIn'] == "0mins") {
             $checkIn = date('Y-m-d h:i', strtotime($_POST['eventStart']));
        } elseif ($_POST['checkIn'] == "15mins") {
             $checkIn = date('Y-m-d h:i', strtotime('15 minutes', strtotime($_POST['eventStart'])));
        } elseif ($_POST['checkIn'] == "30mins") {
            echo $checkIn = date('Y-m-d h:i', strtotime('30 minutes', strtotime($_POST['eventStart'])));
        } elseif ($_POST['checkIn'] == "45mins") {
             $checkIn = date('Y-m-d h:i', strtotime('45 minutes', strtotime($_POST['eventStart'])));
        } elseif ($_POST['checkIn'] == "1hour") {
             $checkIn = date('Y-m-d h:i', strtotime('1 hour', strtotime($_POST['eventStart'])));
        } else {
             $checkIn = date('Y-m-d h:i', strtotime('15 minutes', strtotime($_POST['eventStart'])));
        }
    }

    $submitEvent = $mysql -> prepare("INSERT INTO `events` (`event_id`, `eventTitle`, `eventGame`, `eventBanner`, `eventDescriptionS`, `eventDescription`, `checkIn`, `dateOfStart`, `prizePool`, `eventSettings`, `eventBackground`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $submitEvent -> bind_param('sssisissiii', $event_id, $eventTitle, $eventGame, $eventBanner, $eventSmallDescription, $eventDescription, $checkIn, $dateOfStart, $prizePool, $eventSettings, $eventBackground);
    if (!$submitEvent -> execute()) {
        echo $mysql -> error;
        $submitEvent -> close();
    } else {
        $submitEvent -> close();
        header('location: ../event.php?ID='. $event_id);
        exit();
    }
}