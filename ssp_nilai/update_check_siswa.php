<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");
   
    if(!empty($_POST["kelas_id"])) {

        $kelas_id = $_POST["kelas_id"];
        
        $query =    "SELECT siswa_id, siswa_nama_depan, siswa_nama_belakang 
                    FROM siswa
                    LEFT JOIN kelas
                    ON siswa_id_kelas = kelas_id
                    WHERE kelas_id = $kelas_id AND siswa_id NOT IN (select ssp_daftar_siswa_id as siswa_id from ssp_daftar)";

        $query_info = mysqli_query($conn, $query);
        
        while ($row3 = mysqli_fetch_assoc($query_info)) {
            echo"<input type='checkbox' name='check_siswa_id[]' value={$row3['siswa_id']}> {$row3['siswa_nama_depan']} {$row3['siswa_nama_belakang']} <br>";
        }
    }
    
?>
