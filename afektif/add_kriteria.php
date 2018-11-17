<?php

    if(isset($_POST['kriteria_1_input'])){
        include_once '../includes/db_con.php';
        $k_afektif_topik_nama = mysqli_real_escape_string($conn, $_POST['judul_topik']);
        $kriteria1 = mysqli_real_escape_string($conn, $_POST['kriteria_1_input']);
        $kriteria2 = mysqli_real_escape_string($conn, $_POST['kriteria_2_input']);
        $kriteria3 = mysqli_real_escape_string($conn, $_POST['kriteria_3_input']);
        $bulan = mysqli_real_escape_string($conn, $_POST['kriteria_bulan_option']);

        //Error handlers
        //Cek apakah ada field yang kosong

        if(empty($kriteria1) || empty($kriteria2) || empty($kriteria3)){
            //header("Location: guru.php?signup=empty");
            exit();
        }else{ 
            
            $result = mysqli_query($conn, "SELECT count(*) FROM k_afektif, t_ajaran WHERE k_afektif_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1 AND k_afektif_bulan ='" . $bulan . "'");
            $row = mysqli_fetch_row($result);
            $user_count = $row[0];
            if($user_count>0) {
                $sql_cek_tahun = "SELECT * FROM t_ajaran WHERE t_ajaran_active = 1";
                $query_kelas_info = mysqli_query($conn, $sql_cek_tahun);
                $row = mysqli_fetch_array($query_kelas_info);
                $k_afektif_t_ajaran_id = $row['t_ajaran_id'];
                
                $sql_update = "UPDATE k_afektif SET k_afektif_topik_nama ='$k_afektif_topik_nama', k_afektif_1 = '$kriteria1', k_afektif_2 = '$kriteria2', k_afektif_3 = '$kriteria3' WHERE k_afektif_bulan = $bulan AND k_afektif_t_ajaran_id = $k_afektif_t_ajaran_id";
                mysqli_query($conn, $sql_update);

            }else{
                $sql_cek_tahun = "SELECT * FROM t_ajaran WHERE t_ajaran_active = 1";
                $query_kelas_info = mysqli_query($conn, $sql_cek_tahun);
                $row = mysqli_fetch_array($query_kelas_info);
                $k_afektif_t_ajaran_id = $row['t_ajaran_id'];
                
                $sql_insert = "INSERT INTO k_afektif(k_afektif_1, k_afektif_2, k_afektif_3, k_afektif_bulan, k_afektif_t_ajaran_id, k_afektif_topik_nama) VALUES('$kriteria1','$kriteria2','$kriteria3','$bulan','$k_afektif_t_ajaran_id','$k_afektif_topik_nama')";
                mysqli_query($conn, $sql_insert);
            }
        }
    }