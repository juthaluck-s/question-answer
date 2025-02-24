<?php

$servername = "localhost";
$username = "root";
$password = ""; // รหัสผ่านสำหรับ MySQL
$dbname = "db_knowledge"; // ชื่อฐานข้อมูล

try {
    $condb = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
