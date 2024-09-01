<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dolinapro";

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
