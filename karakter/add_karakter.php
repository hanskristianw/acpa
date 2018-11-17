<?php

    if(isset($_POST['kar_des_a'])){
        include_once '../includes/db_con.php';
        $nama_karakter = mysqli_real_escape_string($conn, $_POST['nama_karakter']);
        $kar_des_a = mysqli_real_escape_string($conn, $_POST['kar_des_a']);
        $kar_des_b = mysqli_real_escape_string($conn, $_POST['kar_des_b']);
        $kar_des_c = mysqli_real_escape_string($conn, $_POST['kar_des_c']);
        $urutan_cetak = mysqli_real_escape_string($conn, $_POST['urutan_cetak']);

        //Error handlers
        //Cek apakah ada field yang kosong

        if(empty($kar_des_a) || empty($kar_des_b) || empty($kar_des_c)){
            //header("Location: guru.php?signup=empty");
            exit();
        }else{ 
                
            $sql_insert = "INSERT INTO karakter(karakter_nama, karakter_a, karakter_b, karakter_c, karakter_urutan) VALUES('$nama_karakter','$kar_des_a','$kar_des_b','$kar_des_c',$urutan_cetak)";
            mysqli_query($conn, $sql_insert);
        }
    }