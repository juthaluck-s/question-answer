<?php
if (isset($_GET['id']) && $_GET['act'] == 'delete') {
    try {
        $id = $_GET['id'];

        // ค้นหา id_topic ก่อนลบ
        $stmtFind = $condb->prepare('SELECT id_topic FROM tbl_detail WHERE id_detail = :id');
        $stmtFind->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtFind->execute();
        $row = $stmtFind->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Data not found");
        }

        $id_topic = $row['id_topic']; // เก็บค่า id_topic ก่อนลบ

        // ลบข้อมูลจากฐานข้อมูล
        $stmtDel = $condb->prepare('DELETE FROM tbl_detail WHERE id_detail = :id');
        $stmtDel->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDel->execute();

        if ($stmtDel->rowCount() == 1) {
            echo '<script>
                setTimeout(function() {
                    swal({
                        title: "Delete Success",
                        type: "success"
                    }, function() {
                        window.location.href = "index.php?act=view&id_topic=' . urlencode($id_topic) . '";
                    });
                }, 1000);
            </script>';
            exit;
        }
    } catch (Exception $e) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "Error",
                    text: "' . $e->getMessage() . '",
                    type: "error"
                }, function() {
                    window.location = "index.php";
                });
            }, 1000);
        </script>';
    }
}
