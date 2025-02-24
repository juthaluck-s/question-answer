<?php
require_once '../config/condb.php';
$star = isset($_POST['star']) ? (int)$_POST['star'] : 0;
$id_detail = isset($_POST['id_detail']) ? (int)$_POST['id_detail'] : 0;

if ($star >= 1 && $star <= 5 && $id_detail > 0) {
    // ตรวจสอบว่ามี id_detail นี้อยู่แล้วหรือไม่
    $sql_check = "SELECT id_rating FROM tbl_rating WHERE id_detail = :id_detail LIMIT 1";
    $stmt_check = $condb->prepare($sql_check);
    $stmt_check->bindParam(":id_detail", $id_detail, PDO::PARAM_INT);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        // มีข้อมูล -> อัปเดต
        $sql_update = "UPDATE tbl_rating SET star_score = :star, dateScore = NOW() WHERE id_detail = :id_detail";
        $stmt_update = $condb->prepare($sql_update);
        $stmt_update->bindParam(":star", $star, PDO::PARAM_INT);
        $stmt_update->bindParam(":id_detail", $id_detail, PDO::PARAM_INT);
        $success = $stmt_update->execute();

        echo $success ? "updated" : "error";
    } else {
        // ไม่มีข้อมูล -> แทรกใหม่
        $sql_insert = "INSERT INTO tbl_rating (id_detail, star_score, dateScore) VALUES (:id_detail, :star, NOW())";
        $stmt_insert = $condb->prepare($sql_insert);
        $stmt_insert->bindParam(":id_detail", $id_detail, PDO::PARAM_INT);
        $stmt_insert->bindParam(":star", $star, PDO::PARAM_INT);
        $success = $stmt_insert->execute();

        echo $success ? "inserted" : "error";
    }
} else {
    echo "invalid";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$condb = null;
