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
            <h1 class="m-0">Table Karyawan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
              <li class="breadcrumb-item active">Ubah Karyawan</li>
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
                <form id="form-karyawan" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
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
                        <label>Jabatan</label>
                        <select class="form-control" id="jabatan" name="jabatan" required>
                            <option value="">Pilih Jabatan...</option>
                            <option value="staf">Staff</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="manager">Manager</option>
                            <option value="direktur">Direktur</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Pilih Status...</option>
                            <option value="tk">Tidak Kawin</option>
                            <option value="k0">Kawin</option>
                            <option value="k1">Kawin Anak 1</option>
                            <option value="k2">Kawin Anak 2</option>
                        </select>
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
      url: `../../services/karyawan/index.php?action=getById&id=${id}`,
      method: "get",
      success: function(response){
        var res = JSON.parse(response);
        var karyawan = res.data;

        $('#nama').val(karyawan.nama);
        $('#jabatan').val(karyawan.jabatan);
        $('#tahun_masuk').val(karyawan.tahun_masuk);
        $('#status').val(karyawan.status);
        var foto_thumb = karyawan.foto || "avatar.png";
        $('#thumbnail').attr('src',`../../dist/img/foto/${foto_thumb}`);
      }
    });

    
  });

  $(document).on('submit','#form-karyawan',function(e) {
      e.preventDefault();
      
      var nama = $('#nama').val();
      var jabatan = $('#jabatan').val();
      var tahun_masuk = $('#tahun_masuk').val();
      var status = $('#status').val();
      var foto = $('#foto').prop('files')[0];

      var form_data = new FormData(); 
      form_data.append('nama', nama);
      form_data.append('jabatan', jabatan);
      form_data.append('tahun_masuk', tahun_masuk);
      form_data.append('status', status);
      form_data.append('foto', foto);
      form_data.append('id', id);

      $.ajax({
        url: "../../services/karyawan/index.php?action=update",
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
            window.location.href = "table_karyawan.php";
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