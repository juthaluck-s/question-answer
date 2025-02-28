<!-- <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
    </div>
</footer> -->
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
<!-- DataTables  & ../assets/plugins -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/plugins/jszip/jszip.min.js"></script>
<script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<!-- <script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script> -->
<script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Page specific script -->
<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 200, // ความสูงของ textarea
        placeholder: 'Detail...',
        tabsize: 2,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36',
            '48'
        ], // ✅ เพิ่มตัวเลือก Font Size

        callbacks: {
            onImageUpload: function(files) {
                // ปิดการอัปโหลดรูปภาพ
                alert('การแนบไฟล์รูปภาพไม่ได้รับอนุญาต');
            },
            onMediaDelete: function(target) {
                // ปิดการลบวิดีโอหรือสื่ออื่น
                alert('ไม่สามารถลบสื่อได้');
                target.remove();
            },
            onKeydown: function(e) {
                // ปิดการแทรกลิงก์ (ผ่าน Ctrl+K)
                if (e.ctrlKey && e.key === 'k') {
                    e.preventDefault();
                    alert('ไม่อนุญาตให้แทรกลิงก์');
                }
            },
            onPaste: function(e) {
                // ปิดการวางไฟล์รูปภาพ/วิดีโอใน textarea
                const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                if (clipboardData && clipboardData.files.length > 0) {
                    e.preventDefault();
                    alert('ไม่อนุญาตให้วางไฟล์');
                }
            }
        }
    });
});

$(function() {
    $("#example2")
        .DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            // buttons: ["colvis"], // ปุ่มสำหรับแสดงหรือซ่อนคอลัมน์
            pageLength: 5,
            order: [
                [6, 'desc']
            ], // เรียงลำดับจากมากไปน้อย (ช่อง 0 คือ ลำดับเลข)
        })
        .buttons()
        .container()
        .appendTo("#example2_wrapper .col-md-6:eq(0)");
});


$(function() {
    $("#example3")
        .DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            pageLength: 5,
            order: [
                [0, 'desc']
            ], // เรียงลำดับจากมากไปน้อย (ช่อง 0 คือ ลำดับเลข)
        })
        .buttons()
        .container()
        .appendTo("#example3_wrapper .col-md-6:eq(0)");
});
$(function() {
    $("#example4")
        .DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            pageLength: 10,
            order: [
                [0, 'desc']
            ], // เรียงลำดับจากมากไปน้อย (ช่อง 0 คือ ลำดับเลข)
        })
        .buttons()
        .container()
        .appendTo("#example3_wrapper .col-md-6:eq(0)");
});
</script>

</body>