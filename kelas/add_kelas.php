<?php

    if(isset($_POST['kelas_nama_input'])){
    include_once '../includes/db_con.php';
    $kelas_nama = mysqli_real_escape_string($conn, $_POST['kelas_nama_input']);
    $kelas_wali_guru_id = mysqli_real_escape_string($conn, $_POST['guru_id_option']);
    $kelas_jenjang_id = mysqli_real_escape_string($conn, $_POST['jenjang_id_option']);
    
    //Error handlers
    //Cek apakah ada field yang kosong
    
    if(empty($kelas_nama) || empty($kelas_wali_guru_id)){
        //header("Location: guru.php?signup=empty");
        exit();
    }else{ 
        $sql_cek_tahun = "SELECT * FROM t_ajaran WHERE t_ajaran_active = 1";
        $query_kelas_info = mysqli_query($conn, $sql_cek_tahun);
        $row = mysqli_fetch_array($query_kelas_info);
        $kelas_t_ajaran_id = $row['t_ajaran_id'];
        $sql_insert = "INSERT INTO kelas(kelas_nama, kelas_wali_guru_id, kelas_t_ajaran_id, kelas_jenjang_id) VALUES('$kelas_nama','$kelas_wali_guru_id',$kelas_t_ajaran_id, $kelas_jenjang_id)";
        mysqli_query($conn, $sql_insert);
    }
    }