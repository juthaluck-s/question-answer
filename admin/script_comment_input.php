<script>
$(document).ready(function() {
    $("#comment-input").keydown(function(e) {
        if (e.key === "Enter" && !e.shiftKey) { // กด Enter โดยไม่กด Shift
            e.preventDefault();

            let commentText = $(this).val().trim();
            let fileInput = $("#file-input")[0];
            let files = fileInput.files; // รับไฟล์ทั้งหมด

            if (commentText === "" && files.length === 0)
                return; // ถ้าไม่มีข้อความหรือไฟล์แนบ ไม่ทำอะไร

            let formData = new FormData($("#comment-form")[0]);
            formData.append("ajax", true); // ส่งข้อมูลแบบ AJAX

            let name = '<?= $firstname; ?> <?= $lastname; ?>';
            let currentTime = 'Just now';
            let fileHTML = "";

            // วนลูปแสดงไฟล์แนบ
            if (files.length > 0) {
                let fileCount = files.length; // นับจำนวนไฟล์
                let loadedCount = 0; // ตัวนับไฟล์ที่โหลดเสร็จ

                for (let i = 0; i < files.length; i++) { // แก้จาก script.script.script.files.length
                    let file = files[i];
                    let fileExt = file.name.split('.').pop().toLowerCase();
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        let fileURL = e.target.result;

                        if (["jpg", "jpeg", "png", "gif"].includes(fileExt)) {
                            fileHTML +=
                                `<img class="img-custom" src="${fileURL}" 
                                style="width: 120px; height: 120px; display: inline-block; margin: 5px; border-radius: 5px;">`;
                        } else if (fileExt === "pdf") {
                            fileHTML += `<div class="pdf-container">
                                <a href="${fileURL}" class="pdf-link" target="_blank">
                                    <i class="fas fa-file-pdf text-danger"></i> ${file.name}
                                </a>
                            </div>`;
                        } else {
                            fileHTML += `<a href="${fileURL}" download="${file.name}">
                                <i class="fas fa-file"></i> ${file.name}
                            </a><br>`;
                        }

                        loadedCount++;
                        if (loadedCount === fileCount) {
                            sendComment(name, currentTime, commentText, fileHTML, formData);
                        }
                    };

                    reader.readAsDataURL(file);
                }
            } else {
                sendComment(name, currentTime, commentText, "", formData);
            }
        }
    });

    function sendComment(name, currentTime, commentText, fileHTML, formData) {
        // ส่งข้อมูลไปเซิร์ฟเวอร์
        $.ajax({
            url: "submit_comment.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Comment submitted successfully.");
                addCommentToPage(name, currentTime, commentText, fileHTML);
                $("#comment-input").val(""); // ล้างค่าหลังจาก AJAX สำเร็จ
                $("#file-input").val("");
                $("#remove-file-button").hide();
            },
            error: function(xhr, status, error) {
                console.error("Error occurred while submitting comment:", error);
            }
        });
    }
});



function addCommentToPage(name, time, text, filesHTML) {
    let newComment = `
    <div class="card-comment">
        <img class="img-circle img-sm" src="../assets/dist/img/avatar5.png" alt="User Image">
        <div class="comment-text">
            <span class="username">${name}</span>
            <span class="text-muted float-right mr-2"><i class="far fa-clock mr-1"></i> ${time}</span>
            <p>${text}</p>
            <div class="row">
                <!-- เพิ่ม filesHTML ที่เป็นรูปภาพหรือไฟล์อื่นๆ -->
                ${filesHTML}
            </div>
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

    // เพิ่มคอมเมนต์ใหม่เข้าไปในหน้า
    $("#comments-section").append(newComment);
}
</script>