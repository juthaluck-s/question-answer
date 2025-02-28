<?php
$queryType_SwitchAccess = $condb->prepare("
SELECT tp.id_topic, tp.type_topic, tp.topic, tp.dateSave, tp.views, tp.reply,
       us.firstname, us.lastname
FROM tbl_topic tp
JOIN tbl_user us ON tp.id_member = us.id_member
WHERE tp.type_topic = 'Switch Access'
ORDER BY tp.dateSave DESC;
");
$queryType_SwitchAccess->execute();
$type_sw_acc = $queryType_SwitchAccess->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Type : Switch Access</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-info card-outline">
                        <div class="card-body">
                            <table id="example4" class="table table-striped table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="4%" class="text-center">No.</th>
                                        <th width="12%" class="text-center">Type</th>
                                        <th class="text-center">Topic</th>
                                        <th width="15%" class="text-center">User</th>
                                        <th width="15%" class="text-center">DateTime</th>
                                        <th width="5%" class="text-center">View</th>
                                        <th width="5%" class="text-center">Reply</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 1; // กำหนดลำดับหมายเลข
                                    foreach ($type_sw_acc as $row) {
                                    ?>
                                    <tr>
                                        <td align="center"><?= $index++; ?> </td>
                                        <td align="center">
                                            <?= htmlspecialchars($row['type_topic'] ?? '-'); ?>
                                        </td>
                                        <td align="center"
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                            <a href="index.php?act=view&id_topic=<?= urlencode($row['id_topic']) ?>"
                                                title="<?= htmlspecialchars($row['topic'] ?? '-') ?>"
                                                style="text-decoration: underline;">
                                                <?= htmlspecialchars($row['topic'] ?? '-') ?>
                                            </a>
                                        </td>

                                        <td align="center">
                                            <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                                        <td align="center">
                                            <?= isset($row['dateSave']) ? date("d M Y \a\\t h:i A", strtotime($row['dateSave'])) : '-'; ?>
                                        </td>
                                        <td align="center">
                                            <?= htmlspecialchars($row['views'] ?? '-'); ?>
                                        </td>
                                        <td align="center">
                                            <?= htmlspecialchars($row['reply'] ?? '-'); ?>
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
    </section>
</div>