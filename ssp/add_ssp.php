<?php

    if(isset($_POST['ssp_nama'])){
        include_once '../includes/db_con.php';
        $ssp_nama = mysqli_real_escape_string($conn, $_POST['ssp_nama']);
        $guru_id = $_POST['guru_id_option'];

        //Error handlers
        //Cek apakah ada field yang kosong

        if(empty($ssp_nama) || $guru_id==0){
            //header("Location: guru.php?signup=empty");
            exit();
        }else{ 
            //cari tahun ajaran aktif
            $query = "SELECT * FROM t_ajaran where t_ajaran_active = 1";
            $query_t_ajaran_info = mysqli_query($conn, $query);

            if(!$query_t_ajaran_info){
                die("QUERY FAILED".mysqli_error($conn));
            }

            //tampilkan tabel pada container
            while($row = mysqli_fetch_array($query_t_ajaran_info)){
                $t_ajaran_id = $row['t_ajaran_id'];
            }
            
            $sql_insert = "INSERT INTO ssp(ssp_nama, ssp_guru_id, ssp_t_ajaran_id) VALUES('$ssp_nama',$guru_id, $t_ajaran_id)";
            mysqli_query($conn, $sql_insert);
        }
    }
    else{
        echo 'youre not supposed to be here :D';
    }