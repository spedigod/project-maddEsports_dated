<?php

   //Adatbázis elérés
  $serverName = "localhost";
  $dBUsername = "root";
  $dBPassword = "";
  $dBName = "maddesports";

   //Csatlakozás
  $mysql = new mysqli($serverName, $dBUsername, $dBPassword, $dBName);

    //Hiba ellenőrzés
   if ($mysql->connect_errno) {
    printf("Connect failed: %s\n", $mysql->connect_error);
    exit();
}
