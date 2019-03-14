<?php

    if(isset($_POST['mapel_khusus_nama'])){
        include_once '../includes/db_con.php';
        $mapel_k_m_nama = mysqli_real_escape_string($conn, $_POST['mapel_khusus_nama']);
        $mapel_k_m_mapel_id = $_POST['mapel_id_option'];

        //Error handlers
        //Cek apakah ada field yang kosong

        if(empty($mapel_k_m_nama) || $mapel_k_m_mapel_id==0){
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
            
            $sql_insert = "INSERT INTO mapel_khusus_master(mapel_k_m_nama, mapel_k_m_t_ajaran_id, mapel_k_m_mapel_id) VALUES('$mapel_k_m_nama',$t_ajaran_id, $mapel_k_m_mapel_id)";
            mysqli_query($conn, $sql_insert);
        }
    }
    else{
        echo 'youre not supposed to be here :D';
    }