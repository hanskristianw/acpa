<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }

    if(isset($_POST['kelas_id']) && isset($_POST['check_siswa_id'])){
        include_once '../includes/db_con.php';
        
        $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);
        $s_id = $_POST['check_siswa_id'];
        $ssp_id = mysqli_real_escape_string($conn, $_POST['ssp_id_hidden']);

//        //Error handlers
//        //Cek apakah ada field yang kosong
//
        if(empty($kelas_id) || empty($s_id)){
            //header("Location: guru.php?signup=empty");
            exit();
        }else{ 
            //cek apakah sudah pnya nilai
            $sql_cek = "SELECT count(*)
                        FROM ssp_nilai
                        LEFT JOIN d_ssp 
                        ON ssp_nilai_d_ssp_id = d_ssp_id 
                        LEFT JOIN ssp ON d_ssp_ssp_id = ssp_id 
                        WHERE ssp_id = $ssp_id";
            
            $result = mysqli_query($conn, $sql_cek);
            $row = mysqli_fetch_row($result);
            $nilai_count = $row[0];
            
            //echo $nilai_count;
            if($nilai_count>0){
                echo'<div class="alert alert-danger alert-dismissible fade show">
                <button class="close" data-dismiss="alert" type="button">
                    <span>&times;</span>
                </button>
                <strong>Info:</strong> ' .count($s_id).' Siswa gagal didaftarkan, tidak dapat menambah siswa ketika nilai sudah ada</div>';
            }
            else{
                //insert into the database
                $sql_insert = "INSERT INTO ssp_daftar(ssp_daftar_ssp_id, ssp_daftar_siswa_id)
                               VALUES";
                for($i=0;$i<count($s_id);$i++){
                    $sql_insert .= '('.$ssp_id.','.$s_id[$i].')';
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
            }
            exit();
        }
    }