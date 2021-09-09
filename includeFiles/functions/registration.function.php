<?php
    require 'main.function.php';

    function registerUser($mysql, $userName, $userEmail, $userPassword, $userFirstName, $userLastName, $refferalCode) {
        if (invaliduserName($userName) == 0) {
            if (strlen($userName) > 3) {
                
                 # egyedi user_id
                $user_id = generateUserID($mysql);

                 # van-e már ilyen név?
                $userExists = userExists($mysql, $userName, $userEmail);
                 # hiba típusa
                if ($userExists == "case1" || $userExists == "case2") {
                    if ($userExists == "case1") {
                         # felhasználónév egyezés
                        if (!empty($refferalCode)) {
                            header('location: ../registration.php?error=userNameAlreadyExists&userEmail='. $userEmail .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName .'&inviteCode='. $refferalCode);
                            exit();
                        }
                        header('location: ../registration.php?error=userNameAlreadyExists&userEmail='. $userEmail .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName);
                        exit();
                    } elseif ($userExists == "case2") {
                         # email egyezés
                        if (!empty($refferalCode)) {
                            header('location: ../registration.php?error=userEmailAlreadyExists&userName='. $userName .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName .'&inviteCode='. $refferalCode);
                            exit();
                        }
                        header('location: ../registration.php?error=userEmailAlreadyExists&userName='. $userName .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName);
                        exit();
                    }
                } # nincs hiba

                 # email formátum ellenőrzése
                if (userEmailInvalid($userEmail) == 0) {
                    
                     # jelszó hossz ellenőrzése
                    if (pwdLength($userPassword) == true) {
                        
                         # megadott refferal kód ellenőrzése
                        if (!empty($refferalCode)) {
                            $testRefferal = $mysql -> prepare("SELECT * FROM `refferals` WHERE `refferalCode` = ?");
                            $testRefferal -> bind_param('s', $refferalCode);
                            $testRefferal -> execute();
                            $getData = $testRefferal -> get_result();
                            if ($getData -> num_rows > 0) {
                                while ($row = $getData -> fetch_assoc()) {
                                    $refferalScore = $row['refferalScore'];
                                    
                                    $refferalScore = $refferalScore + 10;
                                    $addRefferalScore = $mysql -> prepare("UPDATE `refferals` SET `refferalScore` = ? WHERE `refferalCode` = ?");
                                    $addRefferalScore -> bind_param('is', $refferalScore, $refferalCode);
                                    $addRefferalScore -> execute();
                                    $addRefferalScore -> close();
                                }
                            } else {
                                header('location: ../registration.php?error=invalidRefferalCode&userName='. $userName .'&userFirstName='. $userFirstName .'&userLastName='. $userLastName .'&userEmail='. $userEmail);
                                exit();
                            }
                            $testRefferal -> close();
                        }

                        if (invalidName($userFirstName, $userLastName) == 0) {

                             # nincs hiba
                            $userPwdHashed = password_hash($userPassword, PASSWORD_DEFAULT);
                            $isAdmin = 0;
                            $userLevel = 1;
                            $inGroup = 0;
                            $userGroup = '';
                            $isValid = 0;
                            $isCoach = 0;

                            $createUser = $mysql -> prepare('INSERT INTO `users` (`user_id`, `userName`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `usedRefferal`, `isAdmin`, `inGroup`, `isValid`, `isCoach`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                            $createUser -> bind_param('sssssssiiii', $user_id, $userName, $userEmail, $userPwdHashed, $userFirstName, $userLastName, $refferalCode, $isAdmin, $inGroup, $isValid, $isCoach);
                            if (!$createUser -> execute()) {
                                header('location: ../registration.php?error='. $mysql -> error);
                                exit();
                            }

                            # refferal kód generálása
                            generateRefferalCode($mysql, $user_id, $refferalCode);

                            # felhasználó szint megadás
                            setUserLevel($mysql, $user_id);

                        } else {
                            header('location: ../registration.php?error=invalidCharactersInName?userName='. $userName .'&userEmail='. $userEmail);
                            exit();
                        }
                    } else {
                        header('location: ../registration.php?error=passwordIsTooShort?userName='. $userName .'&userEmail='. $userEmail);
                        exit();
                    }
                } else {
                    header('location: ../registration.php?error=invalidEmailFormat?userName='. $userName);
                    exit();
                }
            } else {
                header('location: ../registration.php?error=userNameTooShort');
                exit();
            }
        } else {
            header('location: ../registration.php?error=invalidUserNameFormat');
            exit();
        }
    }