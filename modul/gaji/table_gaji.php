<?php

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
              <li class="breadcrumb-item active">Table Gaji</li>
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
            <div class="card-header">
                <a href="<?=$root?>modul/gaji/tambah_gaji.php" class="btn btn-primary">Tambah</a>
            </div>
            <div class="card-body">
                <table id="gaji" class="table table-striped" style="width:100%"></table>
            </div>
        </div>
      </div>
    </section>
</div>

<?php include "../../pages/footer.php" ?>
<?php include "../../pages/script.php" ?>

<script>
  
  $(document).ready(function() {
    $.ajax({
      url: "../../services/gaji?action=getAll",
      method: "get",
      success: function(response) {
        var res = JSON.parse(response);
        var gaji_karyawan = res.data;
        $.each(gaji_karyawan, function (index, value) {
          if(gaji_karyawan[index].foto === null) gaji_karyawan[index].foto = `<img src="../../dist/img/foto/avatar.png" height="100">`;
          else gaji_karyawan[index].foto = `<img src="../../dist/img/foto/${gaji_karyawan[index].foto}" height="100">`;

          gaji_karyawan[index].gaji = `Rp${parseInt(gaji_karyawan[index].gaji).toLocaleString('id-ID')}`;

          gaji_karyawan[index].fungsi += `<button id="ubah-gaji" class="btn btn-warning" data-gaji=${gaji_karyawan[index].id}><i class="fa fa-pen"></i></button> `;
          gaji_karyawan[index].fungsi += `<button id="hapus-gaji" class="btn btn-danger" data-gaji=${gaji_karyawan[index].id}><i class="fa fa-trash"></i></button> `;
        });
        $('#gaji').DataTable({
          "dom": '<"top"f>rt<"bottom"p><"clear">',
          "columns" : res.column,
          "data" : gaji_karyawan
        });
      }
    });
  });

  $(document).on('click','#hapus-gaji',function(e){
    e.preventDefault();
    var conf = confirm("Apakah anda yakin untuk menghapus data?");
    var id = $(this).attr('data-gaji');
    
    if(conf) {
      $.ajax({
        url: `../../services/gaji?action=delete&id=${id}`,
        method: "get",
        success: function(response){
          var res = JSON.parse(response);
          alert(res.desc);
          location.reload(true);
        }
      });
    }

  });

  $(document).on('click','#ubah-gaji',function(e){
    e.preventDefault();
    var id = $(this).attr('data-gaji');
    window.location.href = `update_gaji.php?id=${id}`;
  });
</script>

</body>
</html>