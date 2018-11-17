<?php

    if(isset($_POST['siswa_no_induk'])){
    include_once '../includes/db_con.php';
    $siswa_no_induk = mysqli_real_escape_string($conn, $_POST['siswa_no_induk']);
    $siswa_nama_depan = mysqli_real_escape_string($conn, $_POST['siswa_nama_depan']);
    $siswa_nama_belakang = mysqli_real_escape_string($conn, $_POST['siswa_nama_belakang']);
    $temp_kelas_option = $_POST['kelas_nama_option'];
   
    
    //Error handlers
    //Cek apakah ada field yang kosong
    
    if(empty($siswa_no_induk) || empty($siswa_nama_depan)|| empty($siswa_nama_belakang)|| $temp_kelas_option==0){
        //header("Location: guru.php?signup=empty");
        exit();
    }else{ 
        echo $siswa_no_induk;
        echo $siswa_nama_depan;
        echo $siswa_nama_belakang;
        echo $temp_kelas_option;
        //insert the user into the database
        $sql_insert = "INSERT INTO siswa(siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, siswa_id_kelas)
                       VALUES($siswa_no_induk,'$siswa_nama_depan','$siswa_nama_belakang',$temp_kelas_option)";
        mysqli_query($conn, $sql_insert);

        //header("Location: guru.php?signup=success");
        exit();
    }
    }