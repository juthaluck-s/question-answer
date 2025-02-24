<?php
require_once '../config/condb.php';
$id_detail = isset($_GET['id_detail']) ? (int)$_GET['id_detail'] : 0; // รับค่า id_detail

if ($id_detail > 0) {
    $sql = "SELECT star_score, dateScore FROM tbl_rating WHERE id_detail = :id_detail ORDER BY dateScore DESC LIMIT 1";

    $getrating = $condb->prepare($sql);
    $getrating->bindParam(":id_detail", $id_detail, PDO::PARAM_INT);
    $getrating->execute();
    $row = $getrating->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row ? $row : ["star_score" => 0, "dateScore" => null]);

    // ปิด statement
    $getrating = null;
} else {
    echo json_encode(["star_score" => 0, "dateScore" => null]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$condb = null;