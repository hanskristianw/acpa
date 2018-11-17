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
        
        $query =    "SELECT siswa_id, siswa_nama_belakang, siswa_nama_depan
                    FROM siswa
                    LEFT JOIN kelas
                    ON siswa_id_kelas = kelas_id
                    WHERE kelas_id = $kelas_id";

        $query_info = mysqli_query($conn, $query);
        
        $options = "<option value= 0>Pilih Siswa</option>";
         while($row = mysqli_fetch_array($query_info)){
            $siswa_nama = $row['siswa_nama_depan'].' '.$row['siswa_nama_belakang'];

            $options .= "<option value={$row['siswa_id']}>$siswa_nama</option>";
         }
         
        echo"<select class='form-control form-control-sm mb-2' name='option_siswa' id='option_siswa'>";
            echo $options;
        echo"</select>";
         
    }
    
?>
