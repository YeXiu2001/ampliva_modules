<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'asdasd';

$mysqli = new mysqli(hostname: $host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
