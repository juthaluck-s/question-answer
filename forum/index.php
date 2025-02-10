<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
$act = (isset($_GET['act']) ? $_GET['act'] : '');

if ($act == 'add') {
    include 'question_add.php';
} else if ($act == 'view') {
    include 'question_view.php';
} else if ($act == 'delete') {
    include 'reply_delete.php';
} else if ($act == 'router') {
    include 'type_router.php';
} else if ($act == 'switch') {
    include 'type_switch.php';
} else {
    include 'forum.php';
}

include 'footer.php';