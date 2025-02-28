<?php

if (isset($_GET['id']) && $_GET['act'] == 'delete_topic') {


    //trigger exception in a "try" block
    try {

        $id = $_GET['id'];
        //echo $id;

        $stmtDel_Tp = $condb->prepare('DELETE FROM tbl_topic WHERE id_topic =:id');
        $stmtDel_Tp->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDel_Tp->execute();

        $condb = null; //close connect db
        //echo 'จำนวน row ที่ลบได้ ' .$stmtDel_Tp->rowCount();
        if ($stmtDel_Tp->rowCount() == 1) {
            echo '<script>
         setTimeout(function() {
          swal({
              title: "ลบข้อมูลสำเร็จ",
              type: "success"
          }, function() {
              window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
          });
        }, 1000);
    </script>';
            exit;
        }
    } //try
    //catch exception
    catch (Exception $e) {
        //echo 'Message: ' .$e->getMessage();
        echo '<script>
         setTimeout(function() {
          swal({
              title: "เกิดข้อผิดพลาด",
              type: "error"
          }, function() {
              window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
          });
        }, 1000);
    </script>';
    } //catch
} //isset