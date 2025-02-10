<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_knowledge";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$star = isset($_POST['star']) ? (int)$_POST['star'] : 0;
$id_detail = isset($_POST['id_detail']) ? (int)$_POST['id_detail'] : 0;

if ($star >= 1 && $star <= 5 && $id_detail > 0) {
    // ตรวจสอบว่ามี id_detail นี้อยู่แล้วหรือไม่
    $sql_check = "SELECT id_rating FROM tbl_rating WHERE id_detail = $id_detail LIMIT 1";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // มีข้อมูลอยู่แล้ว -> อัปเดตค่า star_score
        $sql_update = "UPDATE tbl_rating SET star_score = $star, dateScore = NOW() WHERE id_detail = $id_detail";
        if ($conn->query($sql_update) === TRUE) {
            echo "updated"; // แจ้งว่าอัปเดตสำเร็จ
        } else {
            echo "error";
        }
    } else {
        // ไม่มีข้อมูล -> แทรกข้อมูลใหม่
        $sql_insert = "INSERT INTO tbl_rating (id_detail, star_score, dateScore) VALUES ($id_detail, $star, NOW())";
        if ($conn->query($sql_insert) === TRUE) {
            echo "inserted"; // แจ้งว่าเพิ่มสำเร็จ
        } else {
            echo "error";
        }
    }
} else {
    echo "invalid";
}

$conn->close();