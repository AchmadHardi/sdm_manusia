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

    if($params['action']=='login') {
        
        $sql = "select username, password from user where username='".$_POST['username']."'";
        $result = mysqli_query($conn,$sql);

        $data = array(
            "code" => 500,
            "status" => false,
            "desc" => "Username tidak terdaftar"
        );

        if(mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            $cek_password = password_verify($_POST['password'],$row['password']);

            $data = array(
                "code" => 500,
                "status" => false,
                "desc" => "Password anda salah"
            );

            if($cek_password) {
                $_SESSION['username'] = $row['username'];
                $data = array(
                    "code" => 200,
                    "status" => true,
                    "desc" => "Anda berhasil login"
                );
            }

            

        }

        echo json_encode($data);
    }

    if($params['action']=='logout') {
        
        session_destroy();
        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Anda berhasil logout"
        );

        echo json_encode($data);
    }

    if($params['action']=="getAll") {
        $sql = "select id,username,foto from user";
        $result = mysqli_query($conn, $sql);
        $users = array();
        $col = array(
            array(
                "title" => "ID",
                "data" => "id",
            ),
            array(
                "title" => "Username",
                "data" => "username",
            ),
            array(
                "title" => "Foto",
                "data" => "foto",
            ),
            array(
                "title" => "Fungsi",
                "data" => "fungsi",
                "searchable" => false
            ),
        );
        $i = 0;
        while($row=mysqli_fetch_assoc($result)){
            $users[$i]['id'] = $row['id'];
            $users[$i]['username'] = $row['username'];
            $users[$i]['foto'] = $row['foto'];
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
        $sql = "select id,username,foto from user where id='".$_GET['id']."'";
        $result = mysqli_query($conn, $sql);
        $users=mysqli_fetch_assoc($result);
        
        $data = array(
            "code" => 200,
            "status" => true,
            "data" => $users
        );

        echo json_encode($data);
    }

    if($params['action']=="create") {

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

        $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "insert into user values(null,'".$_POST['username']."','".$password_hash."','".$new_nama_foto."')";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil disimpan"
        );

        echo json_encode($data);

    }

    if($params['action']=='delete'){
        $sql = "delete from user where id='".$params['id']."'";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil dihapus"
        );

        echo json_encode($data);
    }

    if($params['action']=="update") {

        $pass_var = "";
        $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        if(isset($_POST['password'])) $pass_var = ",password='".$password_hash."'";

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil disimpan"
        );

        // cek apakah file ada / tidak
        if(empty($_FILES['foto'])){
            $sql = "update user set username='".$_POST['username']."' ".$pass_var." where id='".$_POST['id']."'";
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

            // check error
            $error = false;

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
    
                $sql = "update user set username='".$_POST['username']."',foto='".$new_nama_foto."' ".$pass_var." where id='".$_POST['id']."'";
                mysqli_query($conn, $sql);
            }            
        }

        echo json_encode($data);

    }

?>