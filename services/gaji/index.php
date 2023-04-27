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
        $sql = "select gaji.id,gaji.gaji,karyawan.nama,karyawan.jabatan,karyawan.tahun_masuk,karyawan.status from gaji inner join karyawan on gaji.id_karyawan=karyawan.id";
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
                "title" => "Gaji",
                "data" => "gaji",
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
            $users[$i]['jabatan'] = $row['jabatan'];
            $users[$i]['tahun_masuk'] = $row['tahun_masuk'];
            $users[$i]['status'] = $row['status'];
            $users[$i]['gaji'] = $row['gaji'];
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
        $sql = "select gaji.id,gaji.gaji,karyawan.nama,karyawan.jabatan,karyawan.tahun_masuk,karyawan.status from gaji inner join karyawan on gaji.id_karyawan=karyawan.id where gaji.id='".$_GET['id']."'";
        $result = mysqli_query($conn, $sql);
        $users=mysqli_fetch_assoc($result);
        
        $data = array(
            "code" => 200,
            "status" => true,
            "data" => $users
        );

        echo json_encode($data);
    }

    if($params['action']=='getPunyaGaji') {
        $sql = "select karyawan.id,karyawan.nama from gaji right join karyawan on gaji.id_karyawan=karyawan.id where gaji.id_karyawan is null";
        $result = mysqli_query($conn,$sql);
        $karyawan = array();
        $i = 0;
        while($row = mysqli_fetch_assoc($result)){
            $karyawan[$i]['id'] = $row['id'];
            $karyawan[$i]['nama'] = $row['nama'];
            $i++;
        }

        $data = array(
            "code" => 200,
            "status" => true,
            "data" => $karyawan
        );

        echo json_encode($data);
    }

    if($params['action']=='create'){
        $sql = "insert into gaji(id_karyawan,gaji) values('".$_POST['id_karyawan']."','".$_POST['gaji']."')";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil disimpan"
        );

        echo json_encode($data);
    }

    if($params['action']=='delete'){
        $sql = "delete from gaji where id='".$params['id']."'";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil dihapus"
        );

        echo json_encode($data);
    }

    if($params['action']=='update'){
        $sql = "update gaji set gaji='".$_POST['gaji']."' where id='".$_POST['id']."'";
        mysqli_query($conn, $sql);

        $data = array(
            "code" => 200,
            "status" => true,
            "desc" => "Data berhasil diubah"
        );

        echo json_encode($data);
    }

?>