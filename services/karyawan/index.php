<?php

    include "../../config/koneksi.php";

    $url_components = parse_url($_SERVER['REQUEST_URI']);

    if(!array_key_exists('query',$url_components)) {
        $error = array(
            "code" => 500,
            "status" => "false",
            "desc" => "Params is not defined"
        );
        echo json_encode($error);
        die();
    }

    parse_str($url_components['query'], $params);
    
    if($params['action']=="getAll") {
        $sql = "select id,nama,foto,jabatan,tahun_masuk,status from karyawan";
        $result = mysqli_query($conn, $sql);
        $users = array();
        $col = array(
            array(
                "title" => "No",
                "data" => "no",
            ),
            array(
                "title" => "Nama",
                "data" => "nama",
            ),
            array(
                "title" => "Foto",
                "data" => "foto",
            ),
            array(
                "title" => "Jabatan",
                "data" => "jabatan",
            ),
            array(
                "title" => "Tahun Masuk",
                "data" => "tahun_masuk",
            ),
            array(
                "title" => "Status",
                "data" => "status",
            ),
            array(
                "title" => "Fungsi",
                "data" => "fungsi",
                "searchable" => false
            ),
        );
        $i = 0; $j = 1;
        while($row=mysqli_fetch_assoc($result)){
            $users[$i]['no'] = $j++;
            $users[$i]['id'] = $row['id'];
            $users[$i]['nama'] = $row['nama'];
            $users[$i]['foto'] = $row['foto'];
            $users[$i]['jabatan'] = $row['jabatan'];
            $users[$i]['tahun_masuk'] = $row['tahun_masuk'];
            $users[$i]['status'] = $row['status'];
            $users[$i]['fungsi'] = "";
            $i++;
        }
        
        $data = array(
            "code" => 200,
            "status" => true,
            "data" => $users,
            "column" => $col
        );

        echo json_encode($data);
    }

    if($params['action']=="getById") {
        $sql = "select id,nama,foto,jabatan,tahun_masuk,status from karyawan where id='".$_GET['id']."'";
        $result = mysqli_query($conn, $sql);
        $karyawan=mysqli_fetch_assoc($result);
        
        $data = array(
            "code" => 200,
            "status" => true,
            "data" => $karyawan
        );

        echo json_encode($data);
    }

    if($params['action']=='create'){
        $lokasi_foto = $_FILES['foto']['tmp_name'];
        $nama_foto = $_FILES['foto']['name'];
        $ukuran_foto = $_FILES['foto']['size'];
        $tipe_foto = $_FILES['foto']['type'];

        $ext = explode('.', $nama_foto);
        $new_nama_foto = str_replace(" ",'_',$ext[0]) . date('Ymdhis') . "." . $ext[1];
        $folder_foto = "../../dist/img/foto/" . $new_nama_foto;

        // size maksimal
        $size_max = 5000000;

        // pembatasan tipe file
        $tipe_boleh = array("image/jpeg","image/png");

        // check error
        $error = false;

        // cek apakah file ada / tidak
        if(strlen($nama_foto < 1 )){
            $error = true;
            $msg = "Foto belum dipilih";
        } else if($ukuran_foto > $size_max){
            $error = true;
            $msg = "Ukuran foto harus dibawah 5 MB";
        } else if($tipe_foto=="" || empty($tipe_foto)){
            $error = true;
            $msg = "Foto yang anda upload rusak";
        } else if(!in_array($tipe_foto, $tipe_boleh)){
            $error = true;
            $msg = "Tipe foto harus jpg atau png, tipe foto anda ".$tipe_foto."";
        }

        move_uploaded_file($lokasi_foto, $folder_foto);

        $sql = "insert into karyawan(nama,jabatan,status,tahun_masuk,foto) values('".$_POST['nama']."','".$_POST['jabatan']."','".$_POST['status']."','".$_POST['tahun_masuk']."','".$new_nama_foto."')";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil disimpan"
        );

        echo json_encode($data);
    }

    if($params['action']=='delete'){
        $sql = "delete from karyawan where id='".$params['id']."'";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil dihapus"
        );

        echo json_encode($data);
    }

    if($params['action']=='update'){

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil disimpan"
        );
        
        // cek apakah file ada / tidak
        if(empty($_FILES['foto'])){
            $sql = "update karyawan set nama='".$_POST['nama']."', jabatan='".$_POST['jabatan']."', tahun_masuk='".$_POST['tahun_masuk']."', status='".$_POST['status']."' where id='".$_POST['id']."'";
            mysqli_query($conn, $sql);
        } else {
            $lokasi_foto = $_FILES['foto']['tmp_name'];
            $nama_foto = $_FILES['foto']['name'];
            $ukuran_foto = $_FILES['foto']['size'];
            $tipe_foto = $_FILES['foto']['type'];

            $ext = explode('.', $nama_foto);
            $new_nama_foto = str_replace(" ",'_',$ext[0]) . date('Ymdhis') . "." . $ext[1];
            $folder_foto = "../../dist/img/foto/" . $new_nama_foto;

            // size maksimal
            $size_max = 5000000;

            // pembatasan tipe file
            $tipe_boleh = array("image/jpeg","image/png");
            
            if($ukuran_foto > $size_max){
                $error = true;
                $msg = "Ukuran foto harus dibawah 5 MB";
            } else if($tipe_foto=="" || empty($tipe_foto)){
                $error = true;
                $msg = "Foto yang anda upload rusak";
            } else if(!in_array($tipe_foto, $tipe_boleh)){
                $error = true;
                $msg = "Tipe foto harus jpg atau png, tipe foto anda ".$tipe_foto."";
            }

            if($error) {
                $data = array(
                    "code" => 500,
                    "status" => false,
                    "desc" => $msg
                );
            } else {
                move_uploaded_file($lokasi_foto, $folder_foto);

                $sql = "update karyawan set nama='".$_POST['nama']."', jabatan='".$_POST['jabatan']."', tahun_masuk='".$_POST['tahun_masuk']."', status='".$_POST['status']."',foto='".$new_nama_foto."' where id='".$_POST['id']."'";
                mysqli_query($conn, $sql);
                
            }

        }

        echo json_encode($data);
    }

?>