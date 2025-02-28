<?php

try {
    // คิวรี่ข้อมูลผู้ใช้จากฐานข้อมูล
    $MemberDetail = $condb->prepare("SELECT * FROM tbl_user WHERE id_member = :id_member");
    $MemberDetail->bindParam(':id_member', $_SESSION['id_member'], PDO::PARAM_INT);
    $MemberDetail->execute();
    $MemberData = $MemberDetail->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพบข้อมูลในฐานข้อมูลหรือไม่
    if ($MemberData) {
        $firstname = htmlspecialchars($MemberData['firstname']);
        $lastname = htmlspecialchars($MemberData['lastname']);
        $user_level = htmlspecialchars($MemberData['user_level']);
    } else {
        // หากไม่พบข้อมูลในฐานข้อมูล
        $firstname = "Unknown";
        $lastname = "User";
        $user_level = "guest";
    }
} catch (PDOException $e) {
    // จัดการข้อผิดพลาดในการเชื่อมต่อหรือ Query ฐานข้อมูล
    echo "Error: " . $e->getMessage();
    // exit();
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <!-- <img src="../assets/dist/img/Message9.webp" alt="Knowledge" class="brand-image img-circle "> -->

        <img src="../assets/dist/img/Message11.webp" alt="Knowledge" class="brand-image img ">
        <span class="brand-text font-weight-light">Knowledge</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- โปรไฟล์ Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <!-- <img src="../assets/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image"
                    style="margin-top: 10px;"> -->

                <img src="../assets/dist/img/user1.webp" class="img-circle " alt="User Image" style="margin-top: 10px;">
            </div>

            <div class="info">

                <a href="#" class="d-block">
                    <?= htmlspecialchars($firstname . ' ' . $lastname); ?>
                </a>
                <a href="#" class="d-block u-level">สิทธิ์ใช้งาน :
                    <?php if ($user_level): ?>
                        <?php if ($user_level == 'admin'): ?>
                            <button class="btn btn-danger btn-custom-small">Admin</button>
                        <?php elseif ($user_level == 'user'): ?>
                            <button class="btn btn-info btn-custom-small">User</button>

                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-custom-small">Unknown</button>
                    <?php endif; ?>
                </a>

            </div>
        </div>

        <style>
            .u-level {
                margin-top: 2px;
                font-size: 13px;
            }

            .btn-custom-small {
                padding: 0px 3px;
                /* ปรับขนาด padding ให้เล็ก */
                font-size: 11px;
                /* ปรับขนาดตัวอักษร */
            }

            body:not(.layout-fixed) .main-sidebar {
                /* height: inherit;
        height: max-content; */
                position: fixed;
                top: 0;
            }
        </style>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="nav-icon fas bi bi-card-text"></i>
                        <p>
                            Forum
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="../logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            ออกจากระบบ
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>