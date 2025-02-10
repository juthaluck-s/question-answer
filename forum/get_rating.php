<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_knowledge";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_detail = isset($_GET['id_detail']) ? (int)$_GET['id_detail'] : 0; // รับค่า id_detail

if ($id_detail > 0) {
    $sql = "SELECT star_score, dateScore 
            FROM tbl_rating 
            WHERE id_detail = ? 
            ORDER BY dateScore DESC LIMIT 1";

    // ใช้ prepare และ bindParam เพื่อป้องกัน SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_detail); // "i" บ่งบอกว่าเป็น Integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["star_score" => 0, "dateScore" => null]);
    }

    $stmt->close();
} else {
    echo json_encode(["star_score" => 0, "dateScore" => null]);
}

$conn->close();