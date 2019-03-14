<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");

    if(!empty($_POST["mapel_id"])) {

        $mapel_k_m_id = $_POST["mapel_id"];
        
        $query =    "SELECT d_m_k_id, siswa_nama_depan, siswa_nama_belakang
                    FROM detail_mapel_khusus_master
                    LEFT JOIN siswa
                    ON d_m_k_siswa_id = siswa_id
                    WHERE d_m_k_mapel_k_m_id = $mapel_k_m_id";

        $query_info = mysqli_query($conn, $query);
        
        $options = "<option value=0>Pilih Siswa</option>";
        while ($row = mysqli_fetch_assoc($query_info)) {
            $options .= "<option value={$row['d_m_k_id']}>{$row['siswa_nama_depan']} {$row['siswa_nama_belakang']}</option>";
        }

        echo '<select class="form-control form-control-sm mt-2" name="option_siswa" id="option_siswa">'.$options.'</select>';
    }
    
?>
