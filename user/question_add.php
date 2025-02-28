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
                    <h2>Add New Topic</h2>
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
                                    <label>Type</label>
                                    <select name="type_topic" class="form-control">
                                        <option>Select</option>
                                        <option>SuperCore</option>
                                        <option>Router PE</option>
                                        <option>Router APE</option>
                                        <option>Router CE</option>
                                        <option>Switch AGG</option>
                                        <option>Switch Access</option>
                                        <option>Switch CE</option>
                                        <option>OLT</option>
                                        <option>ONU</option>
                                        <option>Fiber Optic</option>
                                        <option>Drop Optic / Drop Wire</option>
                                        <option>NT Power</option>
                                        <option>Customer Power</option>
                                        <option>Customer Equipment</option>
                                        <option>Other</option>
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
                                            <input type="file" name="upload_file[]" class="custom-file-input"
                                                id="exampleInputFile" onchange="updateFileName()"
                                                accept=".jpg,.jpeg,.png,.gif,.pdf" multiple>
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


                                <label class="col-sm-5"></label>
                                <button type="submit" class="btn btn-info ">Add</button>
                                <a href="index.php" class="btn btn-danger">Cancel</a>

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
        if (!empty($_FILES['upload_file']['name'][0])) {
            $path = "../assets/upload_file/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true); // สร้างโฟลเดอร์หากยังไม่มี
            }

            $allowedTypes = [
                'pdf',
                'jpg',
                'jpeg',
                'png',
                'gif'
            ];
            $uploadedFiles = [];

            foreach ($_FILES['upload_file']['name'] as $key => $filename) {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // ตรวจสอบว่านามสกุลถูกต้อง
                if (!in_array($ext, $allowedTypes)) {
                    echo '<script>alert("ไฟล์ที่อัปโหลดต้องเป็น PDF หรือรูปภาพเท่านั้น"); window.location = "index.php";</script>';
                    exit();
                }

                // ตรวจสอบว่ามีไฟล์ชื่อซ้ำหรือไม่
                $newname = $filename;
                $path_copy = $path . $newname;
                $count = 1;

                while (file_exists($path_copy)) {
                    $file_base = pathinfo($filename, PATHINFO_FILENAME); // ตัดนามสกุลออก
                    $newname = $file_base . "_" . $count . "." . $ext;
                    $path_copy = $path . $newname;
                    $count++;
                }

                // อัปโหลดไฟล์
                if (move_uploaded_file($_FILES['upload_file']['tmp_name'][$key], $path_copy)) {
                    $uploadedFiles[] = $newname; // เก็บชื่อไฟล์ที่อัปโหลดสำเร็จ
                } else {
                    echo '<script>alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์"); window.location = "case.php";</script>';
                    exit();
                }
            }

            // แปลงอาร์เรย์เป็นสตริงเพื่อเก็บในฐานข้อมูล
            $uploadedFilesString = implode(",", $uploadedFiles);
        } else {
            $uploadedFilesString = NULL;
        }

        // SQL Insert
        $stmtInSertCase = $condb->prepare("INSERT INTO tbl_topic (topic, type_topic, detail_topic, upload_file, id_member, dateSave) 
                            VALUES (:topic, :type_topic, :detail_topic, :upload_file, :id_member, NOW())");

        $stmtInSertCase->bindParam(
            ':topic',
            $topic,
            PDO::PARAM_STR
        );
        $stmtInSertCase->bindParam(':type_topic', $type_topic, PDO::PARAM_STR);
        $stmtInSertCase->bindParam(':detail_topic', $detail_topic, PDO::PARAM_STR);
        $stmtInSertCase->bindParam(':upload_file', $uploadedFilesString, PDO::PARAM_STR); // เก็บชื่อไฟล์ที่อัปโหลดเป็นสตริง
        $stmtInSertCase->bindParam(':id_member', $id_member, PDO::PARAM_INT);

        $result = $stmtInSertCase->execute();


        if ($result) {
            echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Successfully!",
                    icon: "success"
                }).then(function() {
                    window.location = "index.php"; // เปลี่ยน URL ที่ต้องการหลังจากการเพิ่มข้อมูล
                });
            }, 1000);
        </script>';
        }
    } catch (Exception $e) {
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Oops..Something went wrong",
                    text: "' . $e->getMessage() . '",
                    icon: "error"
                }).then(function() {
                    window.location = "index.php"; // เปลี่ยน URL ที่ต้องการหลังจากเกิดข้อผิดพลาด
                });
            }, 1000);
        </script>';
    }
}
?>



<script>
document.getElementById('exampleInputFile').addEventListener('change', function() {
    let files = this.files;
    let maxImagesOnly = 20; // อัปโหลดรูปภาพได้สูงสุด 20 ไฟล์ (ถ้าเป็นภาพอย่างเดียว)
    let maxPDFsOnly = 10; // อัปโหลด PDF ได้สูงสุด 10 ไฟล์
    let maxTotal = 20; // รวมทั้งหมดไม่เกิน 20 ไฟล์
    let maxImagesWithPDF = 10; // จำกัดรูปภาพให้ใช้ได้แค่ 10 ไฟล์เมื่ออัปโหลดร่วมกับ PDF
    let label = this.nextElementSibling; // ดึง <label> ที่แสดงชื่อไฟล์

    let imageCount = 0; // จำนวนไฟล์รูปภาพทั้งหมด
    let pdfCount = 0; // จำนวนไฟล์ PDF

    // ตรวจสอบประเภทของไฟล์ที่อัปโหลด
    for (let i = 0; i < files.length; i++) {
        let ext = files[i].name.split('.').pop().toLowerCase(); // ดึงนามสกุลไฟล์
        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
            imageCount++;
        } else if (ext === 'pdf') {
            pdfCount++;
        }
    }

    let totalFiles = imageCount + pdfCount; // จำนวนไฟล์ทั้งหมด
    let errorMsg = "";

    // กรณีเลือกรูปภาพอย่างเดียว (ได้สูงสุด 20)
    if (pdfCount === 0 && imageCount > maxImagesOnly) {
        errorMsg = `คุณสามารถอัปโหลดรูปภาพได้สูงสุด 20 รูป`;
    } else if (pdfCount > 0 && imageCount > maxImagesWithPDF) {
        errorMsg =
            `เมื่อต้องการอัปโหลดรูปภาพและ PDF พร้อมกัน โดยสามารถเลือกไฟล์รูปภาพได้สูงสุด 10 รูป และไฟล์ PDF ได้สูงสุด 10 ไฟล์`;
    }

    // กรณีเลือก PDF เกิน 10 ไฟล์
    else if (pdfCount > maxPDFsOnly) {
        errorMsg = `คุณสามารถอัปโหลด PDF ได้สูงสุด 10 ไฟล์`;
    }
    // กรณีเลือกรูปภาพและ PDF รวมกัน และรูปภาพเกิน 10 ไฟล์



    // ถ้ามีข้อผิดพลาดให้แจ้งเตือนและรีเซ็ต
    if (errorMsg !== "") {
        Swal.fire({
            title: "อัปโหลดเกินจำนวนที่กำหนด!",
            text: errorMsg,
            icon: "warning",
            confirmButtonText: "ตกลง"
        });

        // รีเซ็ต input file
        this.value = "";
        label.textContent = "Choose file"; // รีเซ็ต label
    } else {
        // อัปเดตชื่อไฟล์ที่เลือก
        if (files.length === 1) {
            label.textContent = files[0].name;
        } else if (files.length > 1) {
            label.textContent = files.length + " files selected";
        } else {
            label.textContent = "Choose file"; // ถ้าไม่ได้เลือกไฟล์ใดๆ
        }
    }
});
</script>