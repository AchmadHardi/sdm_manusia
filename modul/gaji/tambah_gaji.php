<?php

    ob_start();
    include "../../config/koneksi.php";

    if(!isset($_SESSION['username'])) {
        header("location: ".$root."login.php");
    }

    include "../../pages/header.php";
    include "../../pages/navbar.php";
    include "../../pages/sidebar.php";

?>

<div class="content-wrapper">
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Table Gaji</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Gaji</a></li>
              <li class="breadcrumb-item active">Tambah Gaji</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid" data-enable-remember="TRUE" data-no-transition-after-reload="TRUE">
        <div class="card">
            <div class="card-body">
                <div id="tambah-success" class="alert alert-success alert-dismissible" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Tambah data berhasil</h5>
                </div>
                <div id="tambah-fail" class="alert alert-danger alert-dismissible" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Tambah data gagal</h5>
                  <i id="tambah-fail-desc"></i>
                </div>
                <form id="form-gaji" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Karyawan</label>
                        <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                            <option value="">Pilih Karyawan...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gaji">Gaji</label>
                        <input type="number" class="form-control" id="gaji" name="gaji" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Simpan">
                </form>
            </div>
        </div>
      </div>
    </section>
</div>


<?php include "../../pages/script.php" ?>

<script>
  $(document).ready(function() {
    $.ajax({
      url: '../../services/gaji/index.php?action=getPunyaGaji',
      method: 'get',
      success: function(response){
        var res = JSON.parse(response);
        var kary = res.data;
        var option = `<option value="">Pilih Karyawan...</option>`;
        $.each(kary, function (index, value) {
          option += `<option value="${kary[index].id}">${kary[index].nama}</option>`; 
          $('#id_karyawan').html(option);
        });
        
      }
    })

    $('#form-gaji').submit(function(e) {
      e.preventDefault();
      
      var id_karyawan = $('#id_karyawan').val();
      var gaji = $('#gaji').val();

      var form_data = new FormData(); 
      form_data.append('id_karyawan', id_karyawan);
      form_data.append('gaji', gaji);

      $.ajax({
        url: "../../services/gaji/index.php?action=create",
        method: "post",
        data: form_data,
        contentType: 'multipart/form-data',
        processData: false,
        contentType: false,
        success: function(response) {
          var res = JSON.parse(response);
          console.log(res);
          if(res.code === 200) {
            $('#tambah-success').css('display','');
            window.location.href = "table_gaji.php";
          }
          
          if(res.code !== 200) {
            $('#tambah-fail-desc').html(res.desc);
            $('#tambah-fail').css('display','');
          }
        }
      });

    });
  });
</script>

</body>
</html>