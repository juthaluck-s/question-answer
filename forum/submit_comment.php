<?php
require_once '../config/condb.php';

if (!empty($_POST['id_topic']) && !empty($_POST['comment']) && !empty($_POST['id_member'])) {
    $id_topic = intval($_POST['id_topic']); // แปลงค่าเป็นตัวเลขเพื่อความปลอดภัย
    $comment = trim($_POST['comment']); // ลบช่องว่างที่ไม่จำเป็น
    $id_member = intval($_POST['id_member']);

    $newname = NULL; // ค่าเริ่มต้นของไฟล์ ถ้าไม่มีการอัปโหลด

    // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
    if (isset($_FILES['upload_file_comment']) && $_FILES['upload_file_comment']['error'] == 0) {
        $date1 = date("Ymd_His");
        $numrand = mt_rand();
        $upload = $_FILES['upload_file_comment']['name'];

        // กำหนดพาธที่เก็บไฟล์
        $path = "../assets/upload_file_comment/";

        // ใช้ส่วนขยายของไฟล์เดิม
        $ext = strtolower(pathinfo($upload, PATHINFO_EXTENSION));
        $newname = $numrand . $date1 . "." . $ext; // สร้างชื่อไฟล์ใหม่
        $path_copy = $path . $newname;

        // ตรวจสอบว่าไฟล์เป็นประเภทที่อนุญาต
        $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed_exts)) {
            echo '<script>alert("ประเภทไฟล์ไม่รองรับ! กรุณาอัปโหลดไฟล์ PDF, JPG, JPEG, PNG หรือ GIF เท่านั้น"); window.history.back();</script>';
            exit();
        }

        // ตรวจสอบว่าโฟลเดอร์มีอยู่หรือไม่ ถ้าไม่มีให้สร้างใหม่
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // อัปโหลดไฟล์
        if (!move_uploaded_file($_FILES['upload_file_comment']['tmp_name'], $path_copy)) {
            echo '<script>alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์!"); window.history.back();</script>';
            exit();
        }
    }

    // เตรียมคำสั่ง SQL เพื่อบันทึกคอมเมนต์
    try {
        $stmt = $condb->prepare("INSERT INTO tbl_detail (id_topic, id_member, detail, upload_file_comment, dateSave_comment) 
                                 VALUES (:id_topic, :id_member, :detail, :upload_file_comment, NOW())");

        // ผูกค่ากับตัวแปร
        $stmt->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
        $stmt->bindParam(':id_member', $id_member, PDO::PARAM_INT);
        $stmt->bindParam(':detail', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':upload_file_comment', $newname, PDO::PARAM_STR);

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
