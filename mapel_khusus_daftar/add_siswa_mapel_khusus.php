<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }

    if(isset($_POST['check_siswa_id'])){
        include_once '../includes/db_con.php';
        
        $s_id = $_POST['check_siswa_id'];
        $mapel_k_m_id = mysqli_real_escape_string($conn, $_POST['mapel_k_m_id_hidden']);

//        //Error handlers
//        //Cek apakah ada field yang kosong
//
        if(empty($s_id)){
            //header("Location: guru.php?signup=empty");
            exit();
        }else{ 
            
            //insert into the database
            $sql_insert = "INSERT INTO detail_mapel_khusus_master(d_m_k_siswa_id, d_m_k_mapel_k_m_id)
                            VALUES";
            for($i=0;$i<count($s_id);$i++){
                $sql_insert .= '('.$s_id[$i].','.$mapel_k_m_id.')';
                if($i!=count($s_id)-1){
                    $sql_insert .= ",";
                }
            }

            //echo $sql_insert;

            if(mysqli_query($conn, $sql_insert)){
                echo'<div class="alert alert-success alert-dismissible fade show">
                <button class="close" data-dismiss="alert" type="button">
                    <span>&times;</span>
                </button>
                <strong>Info:</strong> ' .count($s_id).' Siswa berhasil didaftarkan</div>';
            }else{
                echo'<div class="alert alert-danger alert-dismissible fade show">
                <button class="close" data-dismiss="alert" type="button">
                    <span>&times;</span>
                </button>
                <strong>Info:</strong> ' .count($s_id).' Gagal didaftarkan</div>';
            }
            
            exit();
        }
    }