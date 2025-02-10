  <?php
    $queryType_router = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Router';");
    $queryType_router->execute();
    $type_rt = $queryType_router->fetch();

    $queryType_switch = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Switch';");
    $queryType_switch->execute();
    $type_sw = $queryType_switch->fetch();
    ?>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <!-- <li class="nav-item d-none d-sm-inline-block">
              <a href="index.php" class="nav-link">Dashboard</a>
          </li> -->
          <li class="nav-item d-none d-sm-inline-block">
              <a href="index.php" class="nav-link">Forum</a>
          </li>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Type
              </a>
              <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                  <a class="dropdown-item"
                      href="index.php?act=router&type_topic=<?= urlencode($type_rt['type_topic']) ?>">Router</a>
                  <a class="dropdown-item"
                      href="index.php?act=switch&type_topic=<?= urlencode($type_sw['type_topic']) ?>">Switch</a>
              </div>
          </li>
      </ul>
  </nav>
  <!-- /.navbar -->