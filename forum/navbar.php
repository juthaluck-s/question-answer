  <?php
    $queryType_SuperCore = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'SuperCore';");
    $queryType_SuperCore->execute();
    $type_spc = $queryType_SuperCore->fetch();

    $queryType_RouterPE = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Router PE';");
    $queryType_RouterPE->execute();
    $type_rt_pe = $queryType_RouterPE->fetch();

    $queryType_RouterAPE = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Router APE';");
    $queryType_RouterAPE->execute();
    $type_rt_ape = $queryType_RouterAPE->fetch();

    $queryType_RouterCE = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Router CE';");
    $queryType_RouterCE->execute();
    $type_rt_ce = $queryType_RouterCE->fetch();

    $queryType_SwitchAGG = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Switch AGG';");
    $queryType_SwitchAGG->execute();
    $type_sw_agg = $queryType_SwitchAGG->fetch();

    $queryType_SwitchAccess = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Switch Access';");
    $queryType_SwitchAccess->execute();
    $type_sw_acc = $queryType_SwitchAccess->fetch();

    $queryType_SwitchCE = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Switch CE';");
    $queryType_SwitchCE->execute();
    $type_sw_ce = $queryType_SwitchCE->fetch();

    $queryType_OLT = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'OLT';");
    $queryType_OLT->execute();
    $type_olt = $queryType_OLT->fetch();

    $queryType_ONU = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'ONU';");
    $queryType_ONU->execute();
    $type_onu = $queryType_ONU->fetch();

    $queryType_Fiber_Optic = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Fiber Optic';");
    $queryType_Fiber_Optic->execute();
    $type_fb_ot = $queryType_Fiber_Optic->fetch();

    $queryType_Drop_Optic_Drop_wire = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Drop Optic / Drop Wire';");
    $queryType_Drop_Optic_Drop_wire->execute();
    $type_dot_dw = $queryType_Drop_Optic_Drop_wire->fetch();

    $queryType_NT_Power = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'NT Power';");
    $queryType_NT_Power->execute();
    $type_nt_pw = $queryType_NT_Power->fetch();

    $queryType_Customer_Powerr = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Customer Power';");
    $queryType_Customer_Powerr->execute();
    $type_ctm_pw = $queryType_Customer_Powerr->fetch();

    $queryType_Customer_Equipment = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Customer Equipment';");
    $queryType_Customer_Equipment->execute();
    $type_ctm_eqm = $queryType_Customer_Equipment->fetch();

    $queryType_Other = $condb->prepare("SELECT * FROM `tbl_topic` WHERE `type_topic` = 'Other';");
    $queryType_Other->execute();
    $type_ot = $queryType_Other->fetch();
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
                      href="index.php?act=supercore&type_topic=<?= urlencode($type_spc['type_topic']) ?>">SuperCore</a>
                  <a class="dropdown-item"
                      href="index.php?act=router_pe&type_topic=<?= urlencode($type_rt_pe['type_topic']) ?>">Router
                      PE</a>
                  <a class="dropdown-item"
                      href="index.php?act=router_ape&type_topic=<?= urlencode($type_rt_ape['type_topic']) ?>">Router
                      APE</a>
                  <a class="dropdown-item"
                      href="index.php?act=router_ce&type_topic=<?= urlencode($type_rt_ce['type_topic']) ?>">Router
                      CE</a>
                  <a class="dropdown-item"
                      href="index.php?act=switch_agg&type_topic=<?= urlencode($type_sw_agg['type_topic']) ?>">Switch
                      AGG</a>
                  <a class="dropdown-item"
                      href="index.php?act=switch_access&type_topic=<?= urlencode($type_sw_acc['type_topic']) ?>">Switch
                      Access</a>
                  <a class="dropdown-item"
                      href="index.php?act=switch_ce&type_topic=<?= urlencode($type_sw_ce['type_topic']) ?>">Switch
                      CE</a>
                  <a class="dropdown-item"
                      href="index.php?act=olt&type_topic=<?= urlencode($type_olt['type_topic']) ?>">OLT</a>
                  <a class="dropdown-item"
                      href="index.php?act=onu&type_topic=<?= urlencode($type_onu['type_topic']) ?>">ONU</a>
                  <a class="dropdown-item"
                      href="index.php?act=fiber_optic&type_topic=<?= urlencode($type_fb_ot['type_topic']) ?>">Fiber
                      Optic</a>
                  <a class="dropdown-item"
                      href="index.php?act=drop_optic_drop_wire&type_topic=<?= urlencode($type_dot_dw['type_topic']) ?>">Drop
                      Optic
                      / Drop Wire</a>
                  <a class="dropdown-item"
                      href="index.php?act=nt_power&type_topic=<?= urlencode($type_nt_pw['type_topic']) ?>">NT Power</a>
                  <a class="dropdown-item"
                      href="index.php?act=customer_power&type_topic=<?= urlencode($type_ctm_pw['type_topic']) ?>">Customer
                      Power</a>
                  <a class="dropdown-item"
                      href="index.php?act=customer_equipment&type_topic=<?= urlencode($type_ctm_eqm['type_topic']) ?>">Customer
                      Equipment</a>
                  <a class="dropdown-item"
                      href="index.php?act=other&type_topic=<?= urlencode($type_ot['type_topic']) ?>">Other</a>
              </div>
          </li>
      </ul>
  </nav>
  <!-- /.navbar -->