<?php
    if (isset($_SESSION['user_id'])) {
        include_once 'dbh.inc.php';
        require_once 'profileQuery.inc.php';
        require_once 'functions\main.function.php';

        $user_id = $_SESSION['user_id'];

        if(isset($_POST['image'])) {

            $data = $_POST['image'];

            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);

            $data = base64_decode($image_array_2[1]);

            $imageName = '../images/profileImage/profile.' . $_SESSION['user_id'] . '.png';

            file_put_contents($imageName, $data);

        }

        /**if (isset($_POST['profile_picture_submit'])) {
            $file = $_FILES['profile_picture_reset'];

            $fileName = $_FILES['profile_picture_reset']['name'];
            $fileTmpName = $_FILES['profile_picture_reset']['tmp_name'];
            $fileSize = $_FILES['profile_picture_reset']['size'];
            $fileError = $_FILES['profile_picture_reset']['error'];
            $fileType = $_FILES['profile_picture_reset']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpeg', 'jpg', 'png');

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 1000000) {

                        $fileName = 'profile.'. $_SESSION['user_id'] .'.'. $fileActualExt;
                        $fileDestination = 'images/profileImage/'. $fileName;

                        move_uploaded_file($fileTmpName, $fileDestination);

                        # profileimg státusz megváltoztatása
                        $status = 1;
                        $statusUpdate = $mysql -> prepare("UPDATE `profileimg` SET `status` = ? WHERE `user_id` = ?");
                        $statusUpdate -> bind_param('is', $status, $user_id);
                        if (!$statusUpdate -> execute()) {
                            echo $mysql -> error;
                            exit();
                        }
                        $statusUpdate -> close();

                        header('location: ../profile.php?ID='. $user_id .'&info=pictureUpdated');
                        exit();

                    } else {
                        header('location: ../profileEdit.php?ID='. $user_id .'&error=uploadedFileIsTooBig');
                        exit();
                    }
                } else {
                    header('location: ../profileEdit.php?ID='. $user_id .'&error=errorWhileUploadingIMG');
                    exit();
                }
            } else {
                header('location: ../profileEdit.php?ID='. $user_id .'&error=fileExtensionNotAccepted');
                exit();
            }
            header('location: ../profileEdit.php?ID='. $user_id .'&error=thereWasAnError');
        }

        */
        if (isset($_POST['profile_reset_submit'])) {

            if ($_POST['username'] != $_POST['username_reset']) {
                if (!empty($_POST['username_reset'])) {
                    if (invaliduserName($_POST['username_reset']) == 0) {
                        $newName = $_POST['username_reset'];

                        $getData = $mysql -> prepare("SELECT * FROM `changedname` WHERE `user_id` = ?");
                        $getData -> bind_param('s', $user_id);
                        $getData -> execute();
                        $getResult = $getData -> get_result();
                        if ($getResult -> num_rows > 0) {

                            $or = $mysql -> prepare("SELECT * FROM `changedname` WHERE `user_id` = ? AND `lastChanged` < DATE_SUB(NOW(), INTERVAL 30 DAY)");
                            $or -> bind_param('s', $user_id);
                            $or -> execute();

                            $orResult = $or -> get_result();
                            if ($orResult -> num_rows > 0) {

                                $deleteRow = $mysql -> prepare("DELETE FROM `changedname` WHERE `user_id` = ?");
                                $deleteRow -> bind_param('s', $user_id);
                                $deleteRow -> execute();
                                $deleteRow -> close();

                                # change name
                                changeName($mysql, $newName, $userName, $user_id);

                            } else {
                                header('location: ../profileEdit.php?ID='. $user_id .'&error=canNotChangeName');
                                exit();
                            }
                            $or -> close();

                        } elseif ($getResult -> num_rows == 0) {

                            # change name
                            changeName($mysql, $newName, $userName, $user_id);

                        }
                        $getData -> close();
                    } else {
                        header('location: ../profileEdit.php?ID='. $user_id .'&error=invalidUserNameFormat');
                        exit();
                    }
                } else {
                    header('location: ../profileEdit.php?ID='. $user_id .'&error=emptyUserNameField');
                    exit();
                }
            } 

            if ($userEmail != $_POST['email_reset']) {
                if (!empty($_POST['email_reset'])) {
                    $userEmailInvalid = userEmailInvalid($_POST['email_reset']);
                    if ($userEmailInvalid == 0) {
                        $previousEmail = $userEmail;
                        $newEmail = $_POST['email_reset'];

                        $changeData = $mysql -> prepare("UPDATE `users` SET `userEmail`= ? WHERE `user_id` = ?");
                        $changeData -> bind_param('ss', $newEmail, $user_id);
                        $changeData -> execute();
                        if (!$changeData -> execute()) {
                            header('location: ../profileEdit.php?ID='. $user_id .'&error=emailAlreadyTaken');
                            exit();
                        }
                        $changeData -> close();
                    } else {
                        header('location: ../profileEdit.php?ID='. $user_id .'&error=invalidEmailFormat');
                        exit();
                    }
                } else {
                    header('location: ../profileEdit.php?ID='. $user_id .'&error=emptyEmailField');
                    exit();
                }
            }

            $newFirstName = $_POST['firstname_reset'];
            $newLastName = $_POST['lastname_reset'];

            if (($_POST['firstname_reset'] != $userFirstName) || ($_POST['lastname_reset'] != $userLastName)) {
                if (!empty($POST['firstname_reset']) && !empty($_POST['lastname_reset'])) {
                    if (invalidName($POST['firstname_reset'], $_POST['lastname_reset']) == 0) {
                        $changeData = $mysql -> prepare("UPDATE `users` SET `userFirstName` = ?, `userLastName` = ? WHERE `user_id` = ?");
                        $changeData -> bind_param('sss', $newFirstName, $newLastName, $user_id);
                        $changeData -> execute();
                        $changeData -> close();
                    } else {
                        header('location: ../profileEdit.php?ID='. $user_id .'&error=invalidNameFormat');
                        exit();
                    }
                } else {
                    header('location: ../profileEdit.php?ID='. $user_id .'&error=emptyNameFields');
                    exit();
                }
            }

            if (!empty($_POST['password_old']) && (empty($_POST['password_reset']) || empty($_POST['password2_reset']))) {
                header('location: ../profileEdit.php?ID='. $user_id .'&error=allPWDFieldsRequired');
                exit();
            } elseif (empty($_POST['password_old']) && (!empty($_POST['password_reset']) || !empty($_POST['password2_reset']))) {
                header('location: ../profileEdit.php?ID='. $user_id .'&error=oldPWDRequired');
                exit();
            } elseif (!empty($_POST['password_old']) && !empty($_POST['password_reset']) && !empty($_POST['password2_reset'])) {
                if ($_POST['password_old'] != $_POST['password_reset']) {
                    $checkOldPass = $mysql -> prepare("SELECT `userPassword` FROM `users` WHERE `user_id` = ?");
                    $checkOldPass -> bind_param('s', $user_id);
                    $checkOldPass -> execute();
                    $getResult = $checkOldPass -> get_result();
                    if ($getResult -> num_rows > 0) {
                        while ($row = $getResult -> fetch_assoc()) {
                            $pwd = $row['userPassword'];
                        }
                    }
                    $checkOldPass -> close();

                    if (password_verify($_POST['password_old'], $pwd) == 1) {
                        if ($_POST['password_reset'] == $_POST['password2_reset']) {
                            if (strlen($_POST['password_reset']) > 8) {
                                $newPWD = password_hash($_POST['password_reset'], PASSWORD_DEFAULT);

                                $changeData = $mysql -> prepare("UPDATE `users` SET `userPassword` = ? WHERE `user_id` = ?");
                                $changeData -> bind_param('ss', $newPWD, $user_id);
                                $changeData -> execute();
                                $changeData -> close();

                                unset($PWDverify);
                                header('location: ../login.php?error=pwdChanged&uID='. $userName);
                                session_unset();
                                session_destroy();
                            } else {
                                header('location: ../profileEdit.php?ID='. $user_id .'&error=newPasswordIsTooShort');
                                exit();
                            }
                        } else {
                            header('location: ../profileEdit.php?ID='. $user_id .'&error=newPasswordsNotMatching');
                            exit();
                        }
                    } else {
                        header('location: ../profileEdit.php?ID='. $user_id .'&error=oldPasswordIsIncorrect');
                        exit();
                    }
                } else {
                    header('location: ../profileEdit.php?ID='. $user_id .'&error=newPasswordCantBeOldPassword');
                    exit();
                }
            }
        }
    }
    header('location: ../profile.php?info=changesAreSaved');
    exit();