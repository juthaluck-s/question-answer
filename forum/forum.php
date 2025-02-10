<?php
$queryAll_Question = $condb->prepare("
SELECT * FROM tbl_topic tp
JOIN tbl_user us ON tp.id_member = us.id_member
");
$queryAll_Question->execute();
$rsAll_Qt = $queryAll_Question->fetchAll();


$questionIndex = [];
$i = 1;
foreach ($rsAll_Qt as $row) {
    $questionIndex[$row['id_topic']] = $i++;
}

// จัดเรียงข้อมูล Popular Question ตาม reply มากที่สุด
usort($rsAll_Qt, function ($a, $b) {
    return (int) $b['reply'] - (int) $a['reply'];
});
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Question & Answer
                        <a href="index.php?act=add" class="btn-primary btn">New Question</a>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card card-danger card-outline">
                        <!-- /.card-header -->

                        <div class="card-body ">
                            <h5 style="text-align: center; border-bottom: inset; padding-bottom: 18px;">Poppular
                                Question
                            </h5>

                            <table id="example2" class="table table-striped table-sm">
                                <thead>
                                    <tr class="table-danger">
                                        <th width="4%" class="text-center">No.</th>
                                        <th width="12%" class="text-center">Type</th>
                                        <th class="text-center">Question</th>
                                        <th width="15%" class="text-center">User</th>
                                        <th width="15%" class="text-center">DateTime</th>
                                        <th width="5%" class="text-center">View</th>
                                        <th width="5%" class="text-center">Reply</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($rsAll_Qt as $row) {
                                    ?>
                                        <tr>
                                            <td align="center"><?= $questionIndex[$row['id_topic']] ?? '-' ?> </td>
                                            <td align="center">
                                                <?= (isset($row['type_topic']) && $row['type_topic'] !== null) ? $row['type_topic'] : '-'; ?>
                                            </td>
                                            <td align="center"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                                <a href="index.php?act=view&id_topic=<?= urlencode($row['id_topic']) ?>"
                                                    title="<?= $row['topic'] ?>" style=" text-decoration: underline;">
                                                    <?= $row['topic'] ?>
                                                </a>
                                            </td>

                                            <td align="center"><?= $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                            <td align="center">
                                                <?= date("d M Y \a\\t h:i A", strtotime($row['dateSave'])); ?>
                                            </td>


                                            <td align="center">
                                                <?= (isset($row['views']) && $row['views'] !== null) ? $row['views'] : '-'; ?>
                                            </td>
                                            <td align="center">
                                                <?= $row['reply']; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>





                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-warning">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <h5 style="text-align: center; border-bottom: inset; padding-bottom: 18px; ">New Question
                            </h5>
                            <table id="example3" class="table table-striped table-sm">
                                <thead>
                                    <tr class="table-warning">
                                        <th width="4%" class="text-center">No.</th>
                                        <th width="10%" class="text-center">Type</th>
                                        <th class="text-center">Question</th>
                                        <th width="15%" class="text-center">User</th>
                                        <th width="15%" class="text-center">DateTime</th>
                                        <th width="5%" class="text-center">View</th>
                                        <th width="5%" class="text-center">Reply</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($rsAll_Qt as $row) { ?>
                                        <tr>
                                            <!-- ลำดับเริ่มจาก 1 แล้วเพิ่มขึ้น -->
                                            <td align="center"><?= $questionIndex[$row['id_topic']] ?? '-' ?></td>
                                            <td align="center">
                                                <?= (isset($row['type_topic']) && $row['type_topic'] !== null) ? $row['type_topic'] : '-'; ?>
                                            </td>
                                            <td align="center"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                                <a href="index.php?id_topic=<?= $row['id_topic']; ?>&act=view"
                                                    title="<?= $row['topic'] ?>" style="text-decoration: underline;">
                                                    <?= $row['topic'] ?>
                                                </a>
                                            </td>


                                            <td align="center"><?= $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                            <td align="center">
                                                <?= date("d M Y \a\\t h:i A", strtotime($row['dateSave'])); ?>
                                            </td>
                                            <td align="center">
                                                <?= (isset($row['views']) && $row['views'] !== null) ? $row['views'] : '-'; ?>
                                            </td>
                                            <td align="center">
                                                <?= (isset($row['reply']) && $row['reply'] !== null) ? $row['reply'] : '-'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>



                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</div>
<!-- /.content-wrapper -->