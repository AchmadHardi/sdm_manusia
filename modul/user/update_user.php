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
            <h1 class="m-0">Table User</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item active">Tambah User</li>
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
                <form id="form-user" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <div class="input-group"><img id="thumbnail" src="" alt="" height="100"></div>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" id="foto" name="foto">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
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
  $(document).ready(function() {
    $.ajax({
      url: `../../services/user/index.php?action=getById&id=${id}`,
      method: "get",
      success: function(response){
        var res = JSON.parse(response);
        var user = res.data;

        $('#username').val(user.username);
        var foto_thumb = user.foto || "avatar.png";
        $('#thumbnail').attr('src',`../../dist/img/foto/${foto_thumb}`);
      }
    });
  });

  $(document).on('submit','#form-user',function(e) {
      e.preventDefault();
      
      var username = $('#username').val();
      var password = $('#password').val();
      var foto = $('#foto').prop('files')[0];

      var form_data = new FormData(); 
      form_data.append('username', username);
      form_data.append('password', password);
      form_data.append('foto', foto);
      form_data.append('id', id);

      $.ajax({
        url: "../../services/user/index.php?action=update",
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
            window.location.href = "table_user.php";
          }
          
          if(res.code !== 200) {
            $('#tambah-fail-desc').html(res.desc);
            $('#tambah-fail').css('display','');
          }
        }
      });

    });
</script>

</body>
</html>