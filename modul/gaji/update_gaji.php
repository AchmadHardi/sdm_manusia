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
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
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
                <div id="ubah-success" class="alert alert-success alert-dismissible" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Ubah data berhasil</h5>
                </div>
                <div id="ubah-fail" class="alert alert-danger alert-dismissible" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Ubah data gagal</h5>
                  <i id="ubah-fail-desc"></i>
                </div>
                <form id="form-gaji" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Karyawan</label>
                        <input type="text" class="form-control" id="nama" name="nama" readonly>
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

<?php include "../../pages/footer.php" ?>
<?php include "../../pages/script.php" ?>

<script>
  const url = new URL(window.location.href);
  const id = url.searchParams.get("id");

  $(document).ready(function(){
    $.ajax({
      url: `../../services/gaji?action=getById&id=${id}`,
      method: "get",
      success: function(response){
        var res = JSON.parse(response);
        var kary = res.data;
        console.log(res);
        $('#id_karyawan').val(kary.id);
        $('#nama').val(kary.nama);
        $('#gaji').val(kary.gaji);
      }
    });
  });

  $(document).on('submit','#form-gaji',function(e) {
    e.preventDefault();
    var gaji = $('#gaji').val();

    var form_data = new FormData(); 
    form_data.append('id', id);
    form_data.append('gaji', gaji);

    $.ajax({
      url: "../../services/gaji/index.php?action=update",
      method: "post",
      data: form_data,
      contentType: 'multipart/form-data',
      processData: false,
      contentType: false,
      success: function(response) {
        var res = JSON.parse(response);
        console.log(res);
        if(res.code === 200) {
          $('#ubah-success').css('display','');
          window.location.href = "table_gaji.php";
        }
        
        if(res.code !== 200) {
          $('#ubah-fail-desc').html(res.desc);
          $('#ubah-fail').css('display','');
        }
      }
    });
  });
</script>

</body>
</html>