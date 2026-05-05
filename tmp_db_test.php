<?php
require 'configuration/config.php';
try {
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET, DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  echo 'OK';
} catch (PDOException $e) {
  echo 'ERROR: '.$e->getMessage();
}
