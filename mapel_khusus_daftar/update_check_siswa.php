<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");

    if(!empty($_POST["option_kelas"])) {

        $kelas_id = $_POST["option_kelas"];
        $mapel_k_m_id = $_POST["option_mapel"];

        $sql = "SELECT mapel_k_m_mapel_id
                FROM mapel_khusus_master
                WHERE mapel_k_m_id = $mapel_k_m_id";
        $result = mysqli_query($conn, $sql);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $mapel_k_m_mapel_id = $row['mapel_k_m_mapel_id'];
        }
        
        $query =    "SELECT siswa_id, siswa_nama_depan, siswa_nama_belakang 
                    FROM siswa
                    LEFT JOIN kelas
                    ON siswa_id_kelas = kelas_id
                    WHERE kelas_id = $kelas_id AND siswa_id 
                    NOT IN 
                    (SELECT d_m_k_siswa_id AS siswa_id 
                    FROM detail_mapel_khusus_master 
                    LEFT JOIN mapel_khusus_master
                    ON d_m_k_mapel_k_m_id = mapel_k_m_id
                    WHERE mapel_k_m_mapel_id = $mapel_k_m_mapel_id)";

        $query_info = mysqli_query($conn, $query);
        
        while ($row3 = mysqli_fetch_assoc($query_info)) {
            echo"<input type='checkbox' name='check_siswa_id[]' value={$row3['siswa_id']}> {$row3['siswa_nama_depan']} {$row3['siswa_nama_belakang']} <br>";
        }

        echo '<input type="hidden" id="mapel_k_m_id_hidden" name="mapel_k_m_id_hidden" value='.$mapel_k_m_id.'>';
    }
    
?>
