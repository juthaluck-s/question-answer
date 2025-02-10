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
    $Question_view = $condb->prepare("SELECT 
        tp.id_topic, tp.topic, tp.type_topic, tp.detail_topic, tp.upload_file, tp.views, tp.reply, tp.id_member, tp.dateSave,
        ur.username, ur.password, ur.id_member AS user_id_member, ur.firstname, ur.lastname, ur.user_level, ur.user_tel, ur.user_email, ur.user_type, ur.user_region
    FROM tbl_topic AS tp
    LEFT JOIN tbl_user AS ur ON tp.id_member = ur.id_member
    WHERE tp.id_topic = :id_topic");

    $Question_view->bindParam(':id_topic', $id_topic, PDO::PARAM_INT);
    $Question_view->execute();
    $qt_view = $Question_view->fetch(PDO::FETCH_ASSOC);
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
                                <img class="img-circle" src="../assets/dist/img/avatar5.png" alt="User Image">

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

                            <?php if (!empty($qt_view['upload_file'])): ?>
                                <?php
                                $file_path = 'uploads/' . $qt_view['upload_file'];
                                $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
                                ?>

                                <!-- หากไฟล์เป็นรูปภาพ -->
                                <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <img class="img-fluid pad" src="../assets/upload_file/<?= $qt_view['upload_file'] ?>"
                                        alt="Uploaded Image" width="500px">

                                    <!-- หากไฟล์เป็น PDF -->
                                <?php elseif ($file_ext == 'pdf'): ?>
                                    <a href="../assets/upload_file/<?= $qt_view['upload_file'] ?>" target="_blank">
                                        <i class="fas fa-file-pdf text-danger"></i> <?= $qt_view['upload_file']; ?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>


                            <!-- /.card-body -->
                            <div class="card-body">
                                <!-- <a href="#" class="link-black text-sm"><i class="fas fa-splotch mr-1"></i> Like</a> -->
                                <!-- <span class="text-muted text-sm mr-2">127 likes</span> -->
                                <span class="float-right">
                                    <a href="#" class="link-black text-sm">
                                        <i class="far fa-comments mr-1"></i> Reply (<?= $comment_count ?>)
                                    </a>
                                </span>

                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer card-comments" id="comments-section">
                            <?php foreach ($rs_CM as $comment): ?>
                                <div class="card-comment">
                                    <img class="img-circle img-sm" src="../assets/dist/img/avatar5.png" alt="User Image">
                                    <div class="comment-text">
                                        <span class="username">
                                            <?= htmlspecialchars($comment['firstname'] . " " . $comment['lastname']); ?>
                                        </span>
                                        <span class="text-muted float-right mr-2">
                                            <i class="far fa-clock mr-1"></i>
                                            <?= date("d M Y \a\\t h:i A", strtotime($comment['dateSave_comment'])); ?>
                                        </span>
                                        <p><?= nl2br(htmlspecialchars($comment['detail'])); ?></p>

                                        <div class="row">
                                            <?php if (!empty($comment['upload_file_comment'])): ?>
                                                <?php
                                                $file_path = "../assets/upload_file_comment/" . $comment['upload_file_comment'];
                                                $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
                                                ?>

                                                <?php if (file_exists($file_path)): ?>
                                                    <!-- ตรวจสอบว่าไฟล์มีอยู่จริง -->
                                                    <!-- แสดงรูปภาพ -->
                                                    <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                        <?php
                                                        $image_size = getimagesize($file_path);
                                                        $width = $image_size[0];
                                                        $height = $image_size[1];

                                                        // เช็คแนวของรูปภาพ
                                                        if ($width > $height) {
                                                            $img_width = 600;
                                                            $img_height = 300;
                                                        } else {
                                                            $img_width = 400;
                                                            $img_height = 450;
                                                        }
                                                        ?>
                                                        <img class="img-custom" src="<?= $file_path; ?>"
                                                            style="width: <?= $img_width; ?>px; height: <?= $img_height; ?>px;">
                                                    <?php elseif ($file_ext === 'pdf'): ?>
                                                        <a href="<?= $file_path; ?>" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger"></i> <?= basename($file_path); ?>
                                                        </a>

                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>

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

                                                .custom-btn-hovor:hover {
                                                    color: #fff;
                                                    background-color: black;
                                                    /* เพิ่มสีพื้นหลังเมื่อ hover */
                                                    border: 1px solid white;
                                                    /* เพิ่มเส้นขอบ */
                                                    transition: all 0.3s ease-in-out;
                                                    /* ทำให้การเปลี่ยนแปลงนุ่มนวล */
                                                }
                                            </style>




                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>



                        <!-- /.card-footer -->
                        <div class="card-footer">
                            <form id="comment-form" action="submit_comment.php" method="POST"
                                enctype="multipart/form-data">
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
                                                <input type="file" name="upload_file_comment" id="file-input"
                                                    accept=".jpg,.jpeg,.png,.gif,.pdf" class="file-input"
                                                    onchange="updateFileName()" />
                                                <button type="submit" class="reply-button btn-info">Reply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>




                        <!-- /.card-footer -->
                    </div>
                    <!-- Box Comment1 -->
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".stars-container").forEach(container => {
            let id_detail = container.getAttribute("data-id-detail");
            let stars = container.querySelectorAll(".star");
            let result = document.getElementById(`result-${id_detail}`);

            function highlightStars(score) {
                stars.forEach(star => {
                    star.classList.toggle("selected", star.dataset.score <= score);
                });
            }

            function fetchRating() {
                fetch(`get_rating.php?id_detail=${id_detail}&t=${Date.now()}`)
                    .then(response => response.json())
                    .then(data => {
                        let currentRating = parseInt(data.star_score) || 0; // ดึงค่า star_score ล่าสุด
                        highlightStars(currentRating); // ไฮไลต์ดาวตามคะแนนที่ได้รับ
                        if (data.dateScore) {
                            // result.textContent =
                            //     `Last rated on: ${new Date(data.dateScore).toLocaleString()}`;
                        } else {
                            // result.textContent = "No ratings yet.";
                        }
                    });
            }

            stars.forEach(star => {
                star.addEventListener("click", function() {
                    let score = this.dataset.score;
                    fetch("save_rating.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `star=${score}&id_detail=${id_detail}`
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result === "updated" || result === "inserted") {
                                highlightStars(
                                    score); // ไฮไลต์ดาวเมื่อมีการให้คะแนนใหม่
                                fetchRating(); // รีเฟรชการแสดงผลคะแนน
                            } else {
                                console.error("Error saving rating:", result);
                            }
                        });
                });
            });

            fetchRating(); // เรียกใช้ฟังก์ชันเพื่อดึงคะแนนล่าสุด
        });
    });
</script>

<script>
    function updateFileName() {
        var input = document.getElementById('file-input');
        var label = document.querySelector('.custom-file-label');
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = 'Choose file';
        }
    }
</script>

<script>
    $("#comment-input").keypress(function(e) {
        if (e.which == 13) {
            e.preventDefault();

            let commentText = $(this).val().trim();
            let fileInput = $("#file-input")[0];
            let file = fileInput.files[0];

            if (commentText === "" && !file) return;

            let formData = new FormData($("#comment-form")[0]);
            formData.append("ajax", true);

            $.ajax({
                url: "submit_comment.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let name = '<?= $firstname; ?> <?= $lastname; ?>';
                    let currentTime = 'Just now';

                    let fileHTML = "";
                    if (file) {
                        let fileURL = URL.createObjectURL(file);
                        let fileName = file.name;
                        let fileExt = fileName.split('.').pop().toLowerCase();

                        if (["jpg", "jpeg", "png", "gif"].includes(fileExt)) {
                            let img = new Image();
                            img.src = fileURL;

                            img.onload = function() {
                                let imgWidth, imgHeight;
                                if (img.width > img.height) {
                                    imgWidth = 600;
                                    imgHeight = 300;
                                } else {
                                    imgWidth = 400;
                                    imgHeight = 450;
                                }

                                fileHTML = `<img class="img-custom" src="${fileURL}" 
                                         style="width: ${imgWidth}px; height: ${imgHeight}px;">`;

                                addCommentToPage(name, currentTime, commentText, fileHTML);
                            };
                        } else if (fileExt === "pdf") {
                            fileHTML = `<a href="${fileURL}" target="_blank">
                                        <i class="fas fa-file-pdf text-danger"></i> ${fileName}
                                    </a>`;
                            addCommentToPage(name, currentTime, commentText, fileHTML);
                        }
                    } else {
                        addCommentToPage(name, currentTime, commentText, fileHTML);
                    }

                    $("#comment-input").val("");
                    fileInput.value = "";
                    $("#remove-file-button").hide();
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred while submitting comment:", error);
                }
            });
        }
    });

    // ฟังก์ชันเพิ่มคอมเมนต์เข้า HTML
    function addCommentToPage(name, time, text, fileHTML) {
        let newComment = `
        <div class="card-comment">
            <img class="img-circle img-sm" src="../assets/dist/img/avatar5.png" alt="User Image">
            <div class="comment-text">
                <span class="username">${name}</span>
                <span class="text-muted float-right mr-2"><i class="far fa-clock mr-1"></i> ${time}</span>
                <p>${text}</p>
                <div class="row">${fileHTML}</div>
                 <div id="stars">
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
                                        </div>
            </div>
        </div>`;

        $("#comments-section").append(newComment);
    }
</script>

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