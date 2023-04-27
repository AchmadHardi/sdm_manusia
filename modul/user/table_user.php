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
            <h1 class="m-0">Table User</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item active">Table User</li>
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
                <a href="<?=$root?>modul/user/tambah_user.php" class="btn btn-primary">Tambah</a>
            </div>
            <div class="card-body">
                <table id="user" class="table table-striped table-bordered" style="width:100%"></table>
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
      url: "../../services/user?action=getAll",
      method: "get",
      success: function(response) {
        var res = JSON.parse(response);
        var users = res.data;
        $.each(users, function (index, value) {
          if(users[index].foto === null) users[index].foto = `<img src="../../dist/img/foto/avatar.png" height="100">`;
          else users[index].foto = `<img src="../../dist/img/foto/${users[index].foto}" height="100">`;

          users[index].fungsi += `<button id="ubah-user" class="btn btn-warning" data-user="${users[index].id}"><i class="fa fa-pen"></i></button> `;
          users[index].fungsi += `<button id="hapus-user" class="btn btn-danger" data-user="${users[index].id}"><i class="fa fa-trash"></i></button> `;
        });
        $('#user').DataTable({
          "dom": '<"top"f>rt<"bottom"p><"clear">',
          "columns" : res.column,
          "data" : users
        });
      }
    });
  });

  $(document).on('click','#hapus-user',function(e){
    e.preventDefault();
    var conf = confirm("Apakah anda yakin untuk menghapus data?");
    var id = $(this).attr('data-user');
    
    if(conf) {
      $.ajax({
        url: `../../services/user?action=delete&id=${id}`,
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

  $(document).on('click','#ubah-user',function(e){
    e.preventDefault();
    var id = $(this).attr('data-user');
    window.location.href = `update_user.php?id=${id}`;
  });
</script>

</body>
</html>