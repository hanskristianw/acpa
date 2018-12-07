<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    include_once '../includes/fungsi_lib.php';

    if($_POST['option_siswa']){
        $siswa_id = $_POST['option_siswa'];
        $ssp_id = $_POST['option_ssp'];
        $d_ssp_id = $_POST['d_ssp_id'];
        $ssp_nilai_angka = $_POST['option_nilai_ssp_susul'];
        
        $sql_daftar = "INSERT INTO ssp_daftar (ssp_daftar_ssp_id, ssp_daftar_siswa_id) VALUES ($ssp_id, $siswa_id)";

        if (!mysqli_query($conn, $sql_daftar))
        {
            echo return_alert(mysqli_error($conn),"danger");
        }
        else{
            echo return_alert("Siswa berhasil didaftarkan!","success");
        }

        $sql = "INSERT INTO ssp_nilai (ssp_nilai_siswa_id, ssp_nilai_d_ssp_id, ssp_nilai_angka) VALUES ";
        
        for($i=0;$i<count($d_ssp_id);$i++){
            $sql .= "(";
            $sql .= $siswa_id;
            $sql .= ",";
            $sql .= $d_ssp_id[$i];
            $sql .= ",";
            $sql .= $ssp_nilai_angka[$i];
            $sql .= ")";
            if($i < count($d_ssp_id) - 1){
                $sql .= ",";
            }
        }
        
        //echo $sql;

        if (!mysqli_query($conn, $sql))
        {
            echo return_alert(mysqli_error($conn),"danger");
        }
        else{
            echo return_alert("Nilai berhasil disimpan!","success");
        }
        mysqli_close($conn);
    }
