<?php

    if(isset($_POST['mapel_nama_input'])){
    include_once '../includes/db_con.php';
    $mapel_nama = mysqli_real_escape_string($conn, $_POST['mapel_nama_input']);
    $mapel_nama_singkatan = mysqli_real_escape_string($conn, $_POST['mapel_singkat_nama_input']);
    
    $d_mapel_id_kelas = mysqli_real_escape_string($conn, $_POST['kelas_id_option']);
    $d_mapel_id_guru = mysqli_real_escape_string($conn, $_POST['guru_id_option']);
    
    //Error handlers
    //Cek apakah ada field yang kosong
    
    if(empty($mapel_nama) || empty($d_mapel_id_kelas) || empty($d_mapel_id_guru)){
        //header("Location: guru.php?signup=empty");
        exit();
    }else{
        //mendapat tahun ajaran yang active
        $sql_cek_tahun = "SELECT * FROM t_ajaran WHERE t_ajaran_active = 1";
        $query_mapel_info = mysqli_query($conn, $sql_cek_tahun);
        $row = mysqli_fetch_array($query_mapel_info);
        $mapel_t_ajaran_id = $row['t_ajaran_id'];
        
        $sql_cek_mapel_id = "SELECT mapel_id from mapel WHERE mapel_nama = '$mapel_nama' AND mapel_t_ajaran_id = '$mapel_t_ajaran_id'";
        $query_d_mapel_info = mysqli_query($conn, $sql_cek_mapel_id);
        
        if(mysqli_num_rows($query_d_mapel_info)>0){
            //dapatkan id mapel
            $row2 = mysqli_fetch_array($query_d_mapel_info);
            $d_mapel_id_mapel = $row2['mapel_id'];
            
            //sudah ada nama mapel, cukup tambahkan detail di kelas mana dan diajar siapa
            $sql_insert2 = "INSERT INTO d_mapel(d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru) VALUES('$d_mapel_id_mapel','$d_mapel_id_kelas','$d_mapel_id_guru')";
            mysqli_query($conn, $sql_insert2);
        }
        else{
            //belum ada nama mapel
            $sql_insert = "INSERT INTO mapel(mapel_nama, mapel_nama_singkatan, mapel_t_ajaran_id) VALUES('$mapel_nama','$mapel_nama_singkatan','$mapel_t_ajaran_id')";
            mysqli_query($conn, $sql_insert);

            $d_mapel_id_mapel = mysqli_insert_id($conn);
            
            $sql_insert2 = "INSERT INTO d_mapel(d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru) VALUES('$d_mapel_id_mapel','$d_mapel_id_kelas','$d_mapel_id_guru')";
            mysqli_query($conn, $sql_insert2);
        }
        
        
    }
    }