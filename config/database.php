<?php
$host = 'db';
$user = 'devuser';
$password = 'devpass';
$db = 'test_db';

$dsn = 'mysql:host=' . $host . ';dbname=' . $db;
$pdo = new PDO($dsn, $user, $password);
