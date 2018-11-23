<?php

    if(isset($_POST['aspek_nama'])){
        include_once '../includes/db_con.php';
        $aspek_nama = mysqli_real_escape_string($conn, $_POST['aspek_nama']);
        $ce_jenjang_id = $_POST['option_jenjang'];
        $aspek_a = mysqli_real_escape_string($conn, $_POST['aspek_a']);
        $aspek_b = mysqli_real_escape_string($conn, $_POST['aspek_b']);
        $aspek_c = mysqli_real_escape_string($conn, $_POST['aspek_c']);
        //Error handlers
        //Cek apakah ada field yang kosong

        if(empty($aspek_nama)){
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
            
            $sql_insert = "INSERT INTO ce(ce_aspek, ce_a, ce_b, ce_c, ce_jenjang_id, ce_t_ajaran_id) VALUES('$aspek_nama','$aspek_a','$aspek_b','$aspek_c', $ce_jenjang_id, $t_ajaran_id)";
            mysqli_query($conn, $sql_insert);
        }
    }
    else{
        echo 'youre not supposed to be here :D';
    }