<?php
require_once '../config/condb.php';

if (!empty($_POST['id_topic']) && !empty($_POST['comment']) && !empty($_POST['id_member'])) {
    $id_topic = intval($_POST['id_topic']);
    $comment = trim($_POST['comment']);
    $id_member = intval($_POST['id_member']);

    $uploadedFiles = []; // เก็บชื่อไฟล์ทั้งหมด

    // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
    if (!empty($_FILES['upload_file_comment']['name'][0])) {
        $path = "../assets/upload_file_comment/";

        // ตรวจสอบว่าโฟลเดอร์มีอยู่หรือไม่ ถ้าไม่มีให้สร้างใหม่
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];

        foreach ($_FILES['upload_file_comment']['name'] as $key => $filename) {
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // ตรวจสอบว่าไฟล์เป็นประเภทที่อนุญาต
            if (!in_array($ext, $allowed_exts)) {
                echo '<script>alert("ประเภทไฟล์ไม่รองรับ! กรุณาอัปโหลดไฟล์ PDF, JPG, JPEG, PNG หรือ GIF เท่านั้น"); window.history.back();</script>';
                exit();
            }

            // ใช้ชื่อไฟล์เดิม (ป้องกันชื่อซ้ำ)
            $newname = $filename;
            $path_copy = $path . $newname;
            $count = 1;

            // ตรวจสอบว่ามีไฟล์ชื่อซ้ำหรือไม่ ถ้ามีให้เพิ่มเลขต่อท้าย
            while (file_exists($path_copy)) {
                $file_base = pathinfo($filename, PATHINFO_FILENAME);
                $newname = $file_base . "_" . $count . "." . $ext;
                $path_copy = $path . $newname;
                $count++;
            }

            // อัปโหลดไฟล์
            if (move_uploaded_file($_FILES['upload_file_comment']['tmp_name'][$key], $path_copy)) {
                $uploadedFiles[] = $newname; // เก็บชื่อไฟล์ที่อัปโหลดสำเร็จ
            } else {
                echo '<script>alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์!"); window.history.back();</script>';
                exit();
            }
        }
    }

    // รวมชื่อไฟล์เป็น string (comma-separated) เพื่อบันทึกในฐานข้อมูล
    $uploadedFilesString = !empty($uploadedFiles) ? implode(",", $uploadedFiles) : NULL;

    // เตรียมคำสั่ง SQL เพื่อบันทึกคอมเมนต์
    try {
        $stmt = $condb->prepare("INSERT INTO tbl_detail (id_topic, id_member, detail, upload_file_comment, dateSave_comment) 
                                 VALUES (:id_topic, :id_member, :detail, :upload_file_comment, NOW())");

        // ผูกค่ากับตัวแปร
        $stmt->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
        $stmt->bindParam(':id_member', $id_member, PDO::PARAM_INT);
        $stmt->bindParam(':detail', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':upload_file_comment', $uploadedFilesString, PDO::PARAM_STR);

        // ดำเนินการคำสั่ง SQL
        if ($stmt->execute()) {
            echo '<script>
                window.location.href = "index.php?act=view&id_topic=' . urlencode($id_topic) . '";
            </script>';
            exit();
        } else {
            throw new Exception("เกิดข้อผิดพลาดในการเพิ่มคอมเมนต์");
        }
    } catch (Exception $e) {
        echo '<script>alert("เกิดข้อผิดพลาด: ' . $e->getMessage() . '"); window.history.back();</script>';
        exit();
    }
} else {
    echo '<script>alert("กรุณากรอกข้อมูลให้ครบถ้วน!"); window.history.back();</script>';
    exit();
}