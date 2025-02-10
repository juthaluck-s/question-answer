<?php
$queryAdd_Topic = $condb->prepare("SELECT * FROM `tbl_topic`");
$queryAdd_Topic->execute();
$rsAdd_topic = $queryAdd_Topic->fetchAll();
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <h2>Add New Question</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-info card-responsive">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Topic*</label>
                                    <input type="text" name="topic" class="form-control" placeholder="Topic..."
                                        required>
                                </div>

                                <div class="form-group">
                                    <label>Types</label>
                                    <select name="type_topic" class="form-control">
                                        <option>Select</option>
                                        <option>Router</option>
                                        <option>Switch</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="detail_topic">Detail*</label>
                                    <textarea class="form-control" name="detail_topic" id="summernote"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>File input</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="upload_file" class="custom-file-input"
                                                id="exampleInputFile" onchange="updateFileName()"
                                                accept=".jpg,.jpeg,.png,.gif,.pdf">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>


                                <?php

                                if (isset($_SESSION['id_member'])) {
                                    $id_member = $_SESSION['id_member'];
                                    $query = $condb->prepare("SELECT * FROM tbl_user WHERE id_member = :id_member");
                                    $query->bindParam(':id_member', $id_member, PDO::PARAM_INT);
                                    $query->execute();
                                    $userData = $query->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลเป็น array
                                }
                                ?>

                                <div class="form-group" style="display: none;">
                                    <label>User</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo $firstname . ' ' . $lastname; ?>" readonly>
                                    <input type="hidden" name="id_member" value="<?php echo $id_member; ?>">
                                </div>

                                <div class="card-footer">
                                    <label class="col-sm-5"></label>
                                    <button type="submit" class="btn btn-info ">Add</button>
                                    <a href="index.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        // รับค่าจากฟอร์ม
        $topic = $_POST['topic'];
        $type_topic = $_POST['type_topic'];
        $detail_topic = $_POST['detail_topic'];
        $id_member = $_POST['id_member']; // ตรวจสอบให้แน่ใจว่า id_member ได้รับค่าแล้ว

        // ตรวจสอบว่าค่าของ type_topic เป็น "Select" หรือไม่
        if ($type_topic == 'Select') {
            $type_topic = NULL; // ถ้าเป็น "Select" ให้เปลี่ยนเป็น NULL
        }

        // ตัวแปรสำหรับวันที่และการตั้งชื่อไฟล์
        $date1 = date("Ymd_His");
        $numrand = mt_rand();
        $upload = $_FILES['upload_file']['name'];

        // ตรวจสอบการอัปโหลดไฟล์
        if ($_FILES['upload_file']['name'] != '') {
            // กำหนดพาธที่เก็บไฟล์
            $path = "../assets/upload_file/";

            // ใช้ส่วนขยายของไฟล์เดิม
            $ext = strtolower(pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION));
            $newname = $numrand . $date1 . "." . $ext;  // สร้างชื่อไฟล์ใหม่
            $path_copy = $path . $newname;

            // ตรวจสอบว่าไฟล์เป็น PDF หรือรูปภาพ
            if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
                // อัปโหลดไฟล์
                if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $path_copy)) {
                    echo "Upload File Success!";
                } else {
                    echo '<script>alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์"); window.location = "case.php";</script>';
                    exit();
                }
            } else {
                // หากไฟล์ไม่ใช่ PDF หรือรูปภาพ
                echo '<script>alert("กรุณาอัปโหลดไฟล์ที่เป็น PDF หรือรูปภาพเท่านั้น"); window.location = "index.php";</script>';
                exit();
            }
        } else {
            $newname = NULL; // กรณีไม่มีการอัปโหลดไฟล์
        }

        // SQL Insert
        $stmtInSertCase = $condb->prepare("INSERT INTO tbl_topic (topic, type_topic, detail_topic, upload_file, id_member, dateSave) 
                                            VALUES (:topic, :type_topic, :detail_topic, :upload_file, :id_member, NOW())");

        // Binding parameters
        $stmtInSertCase->bindParam(':topic', $topic, PDO::PARAM_STR); // ใช้ PDO::PARAM_STR สำหรับข้อความ
        $stmtInSertCase->bindParam(':type_topic', $type_topic, PDO::PARAM_STR);
        $stmtInSertCase->bindParam(':detail_topic', $detail_topic, PDO::PARAM_STR); // ใช้ PDO::PARAM_STR สำหรับข้อความ
        $stmtInSertCase->bindParam(':upload_file', $newname, PDO::PARAM_STR);
        $stmtInSertCase->bindParam(':id_member', $id_member, PDO::PARAM_INT); // ใช้ PDO::PARAM_INT สำหรับตัวเลข

        $result = $stmtInSertCase->execute();

        if ($result) {
            echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "Successfully!",
                            type: "success"
                        }, function() {
                            window.location = "index.php"; // เปลี่ยน URL ที่ต้องการหลังจากการเพิ่มข้อมูล
                        });
                    }, 1000);
                </script>';
        }
    } catch (Exception $e) {
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "Oops..Something went wrong",
                         text: "' . $e->getMessage() . '", // เพิ่มข้อความจากข้อผิดพลาด
                        type: "error"
                    }, function() {
                        window.location = "index.php"; // เปลี่ยน URL ที่ต้องการหลังจากเกิดข้อผิดพลาด
                    });
                }, 1000);
            </script>';
    }
}
?>