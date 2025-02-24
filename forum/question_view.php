<?php
if (isset($_SESSION['id_member'])) {
    $id_member = $_SESSION['id_member'];

    // คิวรีเพื่อดึงข้อมูล firstname และ lastname ของผู้ใช้จากฐานข้อมูล
    $UserInfo = $condb->prepare("SELECT firstname, lastname FROM tbl_user WHERE id_member = :id_member");
    $UserInfo->bindParam(':id_member', $id_member, PDO::PARAM_INT);
    $UserInfo->execute();
    $user = $UserInfo->fetch(PDO::FETCH_ASSOC);
    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
} else {
    $firstname = "Unknown";
    $lastname = "User";
}
?>

<?php
if (isset($_GET['id_topic']) && isset($_GET['act']) && $_GET['act'] == 'view') {
    $id_topic = $_GET['id_topic'];  // รับค่า id_topic ที่ส่งมาจาก URL

    // คิวรีเพื่อดึงข้อมูลของโพสต์จาก tbl_topic
    $Topic_view = $condb->prepare("SELECT 
        tp.id_topic, tp.topic, tp.type_topic, tp.detail_topic, tp.upload_file, tp.views, tp.reply, tp.id_member, tp.dateSave,
        ur.username, ur.password, ur.id_member AS user_id_member, ur.firstname, ur.lastname, ur.user_level, ur.user_tel, ur.user_email, ur.user_type, ur.user_region
    FROM tbl_topic AS tp
    LEFT JOIN tbl_user AS ur ON tp.id_member = ur.id_member
    WHERE tp.id_topic = :id_topic");

    $Topic_view->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
    $Topic_view->execute();
    $qt_view = $Topic_view->fetch(PDO::FETCH_ASSOC);
}


// คิวรีดึงความคิดเห็นของโพสต์
$Comment = $condb->prepare("
    SELECT sub.*, 
        CASE WHEN sub.total_rating > 0 THEN 1 ELSE 2 END AS rating_order
    FROM (
        SELECT 
            dt.id_detail, dt.id_topic AS topic_id,  -- แก้ชื่อเพื่อป้องกันซ้ำ
            dt.detail, dt.upload_file_comment, dt.dateSave_comment, dt.id_member,
            ur.firstname, ur.lastname, 
            tp.topic,  tp.type_topic, tp.detail_topic,
            IFNULL(SUM(rt.star_score), 0) AS total_rating, 
            MAX(rt.dateScore) AS latest_rating_date
        FROM tbl_detail AS dt
        LEFT JOIN tbl_user AS ur ON dt.id_member = ur.id_member
        LEFT JOIN tbl_topic AS tp ON dt.id_topic = tp.id_topic
        LEFT JOIN tbl_rating AS rt ON dt.id_detail = rt.id_detail
        WHERE dt.id_topic = :id_topic
        GROUP BY dt.id_detail, topic_id, dt.detail, dt.upload_file_comment, dt.dateSave_comment, dt.id_member, ur.firstname, ur.lastname, tp.topic, tp.type_topic, tp.detail_topic
    ) AS sub
    ORDER BY 
        rating_order ASC,  -- มีดาวให้มาก่อน
        sub.total_rating DESC,  -- เรียงจากดาวมากสุด
        sub.latest_rating_date DESC,  -- ถ้าดาวเท่ากัน ให้เรียงตามวันที่กดดาวล่าสุด
        sub.dateSave_comment ASC  -- ถ้าไม่มีดาวเลย ให้เรียงตามวันที่โพสต์คอมเมนต์
");

$Comment->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
$Comment->execute();
$rs_CM = $Comment->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมด



// คิวรีเพื่อหาจำนวนคอมเมนต์
$Select_comment = "SELECT COUNT(*) AS comment_count FROM tbl_detail WHERE id_topic = :id_topic";
$Count_Comment = $condb->prepare($Select_comment);
$Count_Comment->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
$Count_Comment->execute();
$C_cm = $Count_Comment->fetch(PDO::FETCH_ASSOC);
$comment_count = $C_cm['comment_count'];


// $Score = $condb->prepare("SELECT dt.*, rti.id_detail, rti.star_score, rti.dateScore
//     FROM tbl_detail AS dt
//     LEFT JOIN tbl_rating AS rti ON dt.id_detail = rti.id_detail
//     WHERE dt.id_detail = :id_detail
//     ORDER BY rti.star_score DESC, rti.dateScore DESC");

// $Score->bindParam(':id_detail', $id_detail, PDO::PARAM_INT);
// $Score->execute();
// $rs_Sc = $Score->fetchAll(PDO::FETCH_ASSOC);;



// แสดงผลข้อมูลความคิดเห็น
// echo '<pre>';
// print_r($rs_CM);
// echo '</pre>';
?>


<!-- <div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="col-sm-6">
                <h2>
                    <?= $qt_view['topic']; ?>
                </h2>
            </div>
        </div>
    </section> -->

<div class="content-wrapper">
    <section class="content-header"></section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Box Comment -->
                    <div class="card card-widget card-responsive">
                        <div class="card-header">
                            <div class="user-block">
                                <img class="img-circle" src="../assets/dist/img/user1.webp" alt="User Image">

                                <span class="username"><?= $qt_view['firstname']; ?> <?= $qt_view['lastname']; ?></span>
                                <spanspan class="description">
                                    <?= date("d M Y \a\\t h:i A", strtotime($qt_view['dateSave'])); ?><spanspann>
                            </div>
                            <!-- /.user-block -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- post text -->
                            <h2>
                                <div class="custom-style">
                                    <?= $qt_view['topic']; ?>
                                </div>

                            </h2>
                            <!-- <p class="description-1"><?= $qt_view['type_topic']; ?></p> -->
                            <p><?= isset($qt_view['detail_topic']) && !empty($qt_view['detail_topic']) ? $qt_view['detail_topic'] : 'No detail available'; ?>
                            </p>

                            <div class="image-grid">
                                <?php
                                $max_display = 4;
                                $imagesTopic = explode(',', $qt_view['upload_file']);
                                $image_count = 0; // นับเฉพาะรูปภาพ
                                $pdf_files = []; // เก็บ PDF แยกออกจากรูปภาพ
                                $image_list = []; // เก็บเฉพาะไฟล์รูปภาพ

                                // นับจำนวนภาพและแยก PDF
                                foreach ($imagesTopic as $file):
                                    $file_path = '../assets/upload_file/' . trim($file);
                                    $file_ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

                                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])):
                                        $image_list[] = $file_path;
                                        $image_count++;
                                    elseif ($file_ext == 'pdf'):
                                        $pdf_files[] = [
                                            'path' => $file_path,
                                            'name' => $file
                                        ];
                                    endif;
                                endforeach;

                                // แสดงผลรูปภาพ
                                $image_index = 0; // นับ index ของรูปภาพเท่านั้น
                                foreach ($image_list as $index => $file_path):
                                    if ($image_index < $max_display): ?>

                                <div class="modal-section-1">
                                    <img src="<?= $file_path ?>" data-index="<?= $image_index ?>" data-source="topic"
                                        class="clickable-image"
                                        style="cursor:pointer; cursor:pointer;height: 200px;width: 300px; margin:5px;border-radius: 5px;">
                                </div>


                                <?php elseif ($image_index === $max_display): ?>
                                <div class="more-images" onclick="openModal1(<?= $image_index ?>)"
                                    style="cursor:pointer; cursor:pointer;height: 50px;width: 50px; border-radius: 5px;">
                                    <span>+<?= max(0, $image_count - $max_display) ?></span>
                                </div>
                                <?php endif;
                                    $image_index++; // นับเฉพาะรูปภาพ
                                endforeach;
                                ?>
                            </div>

                            <!-- แสดงรายการไฟล์ PDF -->
                            <?php if (!empty($pdf_files)): ?>
                            <div class="pdf-list">
                                <?php foreach ($pdf_files as $pdf): ?>
                                <div>
                                    <a href="<?= $pdf['path'] ?>" target="_blank" style="text-decoration:none;">
                                        <i class="fas fa-file-pdf text-danger"></i> <?= $pdf['name'] ?>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>


                            <div id="imageModal1" class="modal" onclick="closeModal1(event)">
                                <!-- ปุ่มปิดที่มุมขวาบน -->
                                <span class="close-button" onclick="closeModal1(event)">&#10006;</span>
                                <span class="modal-arrow prev-arrow left"
                                    onclick="changeImage1(-1, event)">&#10094;</span>
                                <img id="modalImage1" onclick="zoomImage1(event)" onwheel="zoomWithScroll1(event)"
                                    onmousedown="startPan1(event)" onmousemove="panImage1(event)" onmouseup="endPan1()"
                                    onmouseleave="endPan1()">
                                <span class="modal-arrow next-arrow right"
                                    onclick="changeImage1(1, event)">&#10095;</span>
                                <div class="thumbnail-bar" id="thumbnailBar1"></div>
                            </div>


                            <div id="imageModal1" class="modal" onclick="closeModal1(event)">
                                <!-- ปุ่มปิดที่มุมขวาบน -->
                                <span class="close-button" onclick="closeModal1(event)">&#10006;</span>
                                <span class="modal-arrow prev-arrow left"
                                    onclick="changeImage1(-1, event)">&#10094;</span>
                                <img id="modalImage1" onclick="zoomImage1(event)" onwheel="zoomWithScroll1(event)"
                                    onmousedown="startPan1(event)" onmousemove="panImage1(event)" onmouseup="endPan1()"
                                    onmouseleave="endPan1()">
                                <span class="modal-arrow next-arrow right"
                                    onclick="changeImage1(1, event)">&#10095;</span>
                                <div class="thumbnail-bar" id="thumbnailBar1"></div>
                            </div>


                            <!-- /.card-body -->
                            <div class="card-body">
                                <span class="float-right">
                                    <a href="#" class="link-black text-sm">
                                        <i class="far fa-comments mr-1"></i> Reply (<?= $comment_count ?>)
                                    </a>
                                </span>
                            </div>
                        </div> <!-- /.card-body -->

                        <!-- <div class="card-footer card-comments" id="comments-section"> -->
                        <?php foreach ($rs_CM as $comment): ?>
                        <div class="card-footer card-comments" id="comments-section" style="
   
    border-bottom: 1px solid #d7d9db;

">
                            <img class="img-circle img-sm" src="../assets/dist/img/avatar5.png" alt="User Image">
                            <div class="comment-text">
                                <span class="username">
                                    <?= htmlspecialchars($comment['firstname'] . " " . $comment['lastname']); ?>
                                </span>
                                <span style="font-size: 12px;">
                                    <i class="far fa-clock"></i>
                                    <?= date("d M Y \a\\t h:i A", strtotime($comment['dateSave_comment'])); ?>
                                </span>
                                <p style="padding-top: 10px;"><?= nl2br(htmlspecialchars($comment['detail'])); ?></p>

                                <div class="row">
                                    <div class="image-grid">

                                        <?php
                                            $max_display = 4; // จำนวนรูปที่แสดง
                                            $file_list = explode(',', $comment['upload_file_comment']);
                                            $file_json = json_encode($file_list); // แปลงเป็น JSON
                                            $file_count = count($file_list); // นับจำนวนไฟล์ทั้งหมด

                                            $image_files = []; // เก็บไฟล์ภาพ
                                            $pdf_files = []; // เก็บไฟล์ PDF

                                            // นับจำนวนภาพและแยก PDF
                                            foreach ($file_list as $file):
                                                $file_path = "../assets/upload_file_comment/" . trim($file);
                                                $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);

                                                if (file_exists($file_path)) {
                                                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        // เก็บไฟล์ภาพ
                                                        $image_files[] = $file;
                                                    } elseif ($file_ext === 'pdf') {
                                                        // เก็บไฟล์ PDF
                                                        $pdf_files[] = [
                                                            'path' => $file_path,
                                                            'name' => basename($file)
                                                        ];
                                                    }
                                                }
                                            endforeach;
                                            ?>

                                        <div class="comment-images"
                                            data-images='<?= htmlspecialchars($file_json, ENT_QUOTES, 'UTF-8') ?>'>
                                            <?php
                                                $image_displayed = 0;

                                                // แสดงไฟล์ภาพ (จำกัดการแสดง)
                                                foreach ($image_files as $file):
                                                    $file_path = "../assets/upload_file_comment/" . trim($file);
                                                    if ($image_displayed < $max_display): ?>
                                            <img class="img-custom clickable-image" src="<?= $file_path ?>"
                                                data-index="<?= $image_displayed ?>"
                                                style="width: 120px; height: 120px; display: inline-block; margin: 5px; cursor: pointer; border-radius: 5px;"
                                                onclick="openModal2(this)">
                                            <?php
                                                        $image_displayed++;
                                                    endif;
                                                endforeach;
                                                ?>

                                            <?php if (count($image_files) > $max_display): ?>
                                            <span class="more-images" onclick="openModal2(this, true)"
                                                style="cursor:pointer;height: 50px;width: 50px; margin: 5px; border-radius: 5px;">
                                                +<?= count($image_files) - $max_display; ?>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>

                                <!-- แสดงรายการไฟล์ PDF -->
                                <?php if (!empty($pdf_files)): ?>
                                <div class="pdf-list" style="margin-top: 10px;">
                                    <?php foreach ($pdf_files as $pdf): ?>
                                    <div>
                                        <a href="<?= $pdf['path'] ?>" target="_blank" style="text-decoration:none;">
                                            <i class="fas fa-file-pdf text-danger"></i> <?= $pdf['name'] ?>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>



                                <!-- Modal สำหรับแสดงรูป -->
                                <div id="imageModal2" class="modal" onclick="closeModal2(event)">
                                    <span class="close-button" onclick="closeModal2(event)">&#10006;</span>
                                    <span class="modal-arrow prev-arrow left"
                                        onclick="changeImage2(-1, event)">&#10094;</span>
                                    <img id="modalImage2" onclick="zoomImage2(event)" onwheel="zoomWithScroll2(event)">
                                    <span class="modal-arrow next-arrow right"
                                        onclick="changeImage2(1, event)">&#10095;</span>
                                    <div class="thumbnail-bar" id="thumbnailBar2"></div>
                                </div>
                                <!-- Rating stars placed below the image -->

                                <div id="stars-<?= $comment['id_detail']; ?>" class="stars-container"
                                    data-id-detail="<?= $comment['id_detail']; ?>">
                                    <span class="star" data-score="1">&#9733;</span>
                                    <span class="star" data-score="2">&#9733;</span>
                                    <span class="star" data-score="3">&#9733;</span>
                                    <span class="star" data-score="4">&#9733;</span>
                                    <span class="star" data-score="5">&#9733;</span>
                                    <a href="index.php?id=<?= $comment['id_detail']; ?>&act=delete"
                                        class="btn btn-outline-danger custom-btn-size"
                                        onclick="return confirm('ยืนยันการลบข้อมูล??');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <p id="result-<?= $comment['id_detail']; ?>"></p>



                                    <style>
                                    .custom-btn-size {
                                        padding: 3px 2px;
                                        margin-left: 10px;
                                        margin-bottom: 3px;

                                        /* ปรับขนาด padding ตามที่ต้องการ */
                                        font-size: 12px;
                                        /* ปรับขนาดฟอนต์ */
                                    }
                                    </style>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>


                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <form id="comment-form" action="submit_comment.php" method="POST" enctype="multipart/form-data">
                            <img class="img-fluid img-circle img-sm" src="../assets/dist/img/avatar5.png"
                                alt="User Image">
                            <div class="img-push">

                                <!-- Hidden Fields -->
                                <input type="hidden" name="id_topic" value="<?= htmlspecialchars($id_topic); ?>">
                                <input type="hidden" name="id_member" value="<?= htmlspecialchars($id_member); ?>">

                                <!-- Input comment -->
                                <div class="reply-box">
                                    <textarea class="reply-input" id="comment-input" name="comment"
                                        placeholder="Press enter to reply" required></textarea>
                                    <div class="reply-actions">
                                        <div class="custom-file">
                                            <input type="file" name="upload_file_comment[]" id="file-input"
                                                accept=".jpg,.jpeg,.png,.gif,.pdf" multiple class="file-input"
                                                onchange="updateFileName()" />
                                            <button type="submit" class="reply-button btn-info">Reply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>




                    <!-- /.card-footer -->

                    <!-- Box Comment1 -->
                </div>
            </div>
        </div>
    </section>
</div>

<?php
if (isset($_GET['id_topic'])) {
    $id_topic = $_GET['id_topic'];


    try {
        // อัปเดต views
        $queryUpdateViews = $condb->prepare("UPDATE tbl_topic SET views = views + 1 WHERE id_topic = :id_topic");
        $queryUpdateViews->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
        $queryUpdateViews->execute();

        if ($queryUpdateViews->rowCount() > 0) {
            // Debug: Success message
            // echo "Views updated successfully!";
        } else {
            // Debug: No rows updated
            // echo "No rows updated.";
        }
    } catch (PDOException $e) {
        echo "Error updating views: " . $e->getMessage();
    }
}
?>