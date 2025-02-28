<?php
//เริ่มต้นการใช้เซสชั่น
session_start();
require_once 'config/condb.php';


//สร้างเงื่อนไขตรวจ input ที่ส่งมาจากฟอร์ม
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['action']) && $_POST['action'] == 'login') {

    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = md5($_POST['password']); //เก็บรหัสผ่านในรูปแบบ md5 

    //check username  & password
    $stmtLogin = $condb->prepare("SELECT id_member, user_level FROM tbl_user
    WHERE username = :username AND password = :password");

    //bindparam STR, INT
    $stmtLogin->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtLogin->bindParam(':password', $password, PDO::PARAM_STR);
    $stmtLogin->execute();

    //กรอก username & password ถูกต้อง
    if ($stmtLogin->rowCount() == 1) {
        //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
        $row = $stmtLogin->fetch(PDO::FETCH_ASSOC); //sigle row
        //สร้างตัวแปร session
        $_SESSION['id_member'] = $row['id_member'];
        $_SESSION['user_level'] = $row['user_level'];


        //เช็คว่ามีตัวแปร session อะไรบ้าง
        //print_r($_SESSION);
        // exit();
        $condb = null; //close connect db

        //สร้างเงื่อนไขตรวจสอบสิทธิ์การใช้งาน
        if ($_SESSION['user_level'] == 'admin') { //admin
            header('Location: admin/'); //login ถูกต้องและกระโดดไปหน้าตามที่ต้องการ
        } else if ($_SESSION['user_level'] == 'user') {
            header('Location: user/'); //user
        }
    } else { //ถ้า username or password ไม่ถูกต้อง

        echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "เกิดข้อผิดพลาด",
                             text: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                            type: "warning"
                        }, function() {
                            window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1000);
                  </script>';
    } //else
} //isset
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/dist/css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- sweet alert -->
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body>
    <div class="wrapper">
        <div class="form-box login">
            <!-- Add your image here -->
            <img src="assets/dist/img/logo_nt.jpg" alt="Welcome Image" class="login-image">

            <form action="" method="post">

                <div class="input-box">
                    <span class="icon"><i class='bx bxs-user'></i></span>

                    <input type="text" id="username" name="username" class="form-control" placeholder="Username"
                        required>
                </div>
                <div class="input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                        required>
                </div>

                <button type="submit" name="action" value="login" class="btn btn-login">Login</button>
                &nbsp;&nbsp;
            </form>
        </div>
    </div>
</body>

</html>