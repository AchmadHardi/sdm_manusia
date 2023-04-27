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
            <h1 class="m-0">Table Karyawan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
              <li class="breadcrumb-item active">Table Karyawan</li>
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
                <a href="<?=$root?>modul/karyawan/tambah_karyawan.php" class="btn btn-primary">Tambah</a>
            </div>
            <div class="card-body">
                <table id="karyawan" class="table table-striped" style="width:100%"></table>
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
      url: "../../services/karyawan?action=getAll",
      method: "get",
      success: function(response) {
        var res = JSON.parse(response);
        var kary = res.data;
        $.each(kary, function (index, value) {
          if(kary[index].foto === null) kary[index].foto = `<img src="../../dist/img/foto/avatar.png" height="100">`;
          else kary[index].foto = `<img src="../../dist/img/foto/${kary[index].foto}" height="100">`;

          kary[index].fungsi += `<button id="ubah-karyawan" class="btn btn-warning" data-karyawan="${kary[index].id}"><i class="fa fa-pen"></i></button> `;
          kary[index].fungsi += `<button id="hapus-karyawan" class="btn btn-danger" data-karyawan="${kary[index].id}"><i class="fa fa-trash"></i></button> `;
        });
        $('#karyawan').DataTable({
          "dom": '<"top"f>rt<"bottom"p><"clear">',
          "columns" : res.column,
          "data" : kary
        });
      }
    });
  });

  $(document).on('click','#hapus-karyawan',function(e){
    e.preventDefault();
    var conf = confirm("Apakah anda yakin untuk menghapus data?");
    var id = $(this).attr('data-karyawan');
    
    if(conf) {
      $.ajax({
        url: `../../services/karyawan?action=delete&id=${id}`,
        method: "get",
        success: function(response){
          var res = JSON.parse(response);
          console.log(res);
          alert(res.desc);
          location.reload(true);
        }
      });
    }
  });

  $(document).on('click','#ubah-karyawan',function(e){
    e.preventDefault();
    var id = $(this).attr('data-karyawan');
    window.location.href = `update_karyawan.php?id=${id}`;
  });
</script>

</body>
</html>