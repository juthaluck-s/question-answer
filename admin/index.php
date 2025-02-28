<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
$act = (isset($_GET['act']) ? $_GET['act'] : '');

if ($act == 'add') {
    include 'question_add.php';
} else if ($act == 'view') {
    include 'question_view.php';
} else if ($act == 'delete_topic') {
    include 'topic_delete.php';
} else if ($act == 'delete') {
    include 'reply_delete.php';
} else if ($act == 'supercore') {
    include 'type_supercore.php';
} else if ($act == 'router_pe') {
    include 'type_router_pe.php';
} else if ($act == 'router_ape') { //d
    include 'type_router_ape.php'; //d
} else if ($act == 'router_ce') { //ce
    include 'type_router_ce.php'; //ce
} else if ($act == 'switch_agg') {
    include 'type_switch_agg.php';
} else if ($act == 'switch_access') {
    include 'type_switch_access.php';
} else if ($act == 'switch_ce') {
    include 'type_switch_ce.php';
} else if ($act == 'olt') {
    include 'type_olt.php';
} else if ($act == 'onu') {
    include 'type_onu.php';
} else if ($act == 'fiber_optic') {
    include 'type_fiber_optic.php';
} else if ($act == 'drop_optic_drop_wire') {
    include 'type_drop_optic_drop_wire.php';
} else if ($act == 'nt_power') {
    include 'type_nt_power.php';
} else if ($act == 'customer_power') {
    include 'type_customer_power.php';
} else if ($act == 'customer_equipment') {
    include 'type_customer_equipment.php';
} else if ($act == 'other') {
    include 'type_other.php';
} else {
    include 'forum.php';
}

include 'script_comment_input.php';
include 'script_file_input.php';
include 'script_star.php';
include 'script_topic.php';
include 'script_comment.php';
include 'footer.php';
