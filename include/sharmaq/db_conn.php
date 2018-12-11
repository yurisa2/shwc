<?php

class ShPDO {
  function __construct() {
    global $server;
    global $db;
    global $user;
    global $pass;

    $this->conn = new PDO("mysql:dbname=$db;host=$server", $user, $pass);
  }


}



?>
