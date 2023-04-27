<?php

    include "../config/koneksi.php";

    if(isset($_SESSION['username'])) {
        header("location: table_karyawan.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=$root?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=$root?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$root?>dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?=$root?>index2.html">Sistem Informasi <b>SDM</b></a>
  </div>
  <!-- /.login-logo -->
  <div id="login-success" class="alert alert-success alert-dismissible" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Login berhasil</h5>
  </div>
  <div id="login-fail" class="alert alert-danger alert-dismissible" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Login gagal</h5>
    <i id="login-fail-desc"></i>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post" id="form-login">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" id="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8"></div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=$root?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=$root?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=$root?>dist/js/adminlte.min.js"></script>
<script>
  $(document).ready(function() {
    
    $('#form-login').submit(function(e) {
      e.preventDefault();

      $('#login-success').hide();
      $('#login-fail').hide();

      if($('#username').val() === '' || $('#password').val() === ''){
        $('#login-fail-desc').html('Username & Password harus terisi');
        $('#login-fail').css('display','');
      } else {

        var form_data = new FormData();
        form_data.append('username',$('#username').val());
        form_data.append('password',$('#password').val());

        $.ajax({
          url: "../services/user/?action=login",
          method: "post",
          processData: false,
          contentType: false,
          enctype: 'multipart/form-data',
          data: form_data,
          success: function(response){
            var res = JSON.parse(response);

            if(res.code === 200) {
              $('#login-success').css('display','');
              window.location.href = "../";
            }
            
            if(res.code !== 200) {
              $('#login-fail-desc').html(res.desc);
              $('#login-fail').css('display','');
            }

          }
        });

      }

      
    });

  });
</script>
</body>
</html>