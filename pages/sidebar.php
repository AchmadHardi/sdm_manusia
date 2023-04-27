<?php
    function active($currect_page){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);
        if(strpos($url, "?") > 0) {
          $url = substr($url, 0, strpos($url, "?"));
        }
        if(in_array($url,$currect_page)){
            return 'active'; 
        } 
        return '';
    }
    $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);
        $url = substr($url, 0, strpos($url, "?"));
?>

<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/sdm" class="brand-link">
      <img src="<?=$root?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Aplikasi SDM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?=$root?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=ucfirst($_SESSION['username'])?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?=$root?>" class="nav-link <?=active(array('','index.php'))?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=$root?>modul/karyawan/table_karyawan.php" class="nav-link <?=active(array('table_karyawan.php','tambah_karyawan.php','update_karyawan.php'))?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Karyawan
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=$root?>modul/gaji/table_gaji.php" class="nav-link <?=active(array('table_gaji.php','tambah_gaji.php','update_gaji.php'))?>">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Gaji
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=$root?>modul/user/table_user.php" class="nav-link <?=active(array('table_user.php','tambah_user.php','update_user.php'))?>">
              <i class="nav-icon fas fa-user"></i>
              <p>
                User
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>