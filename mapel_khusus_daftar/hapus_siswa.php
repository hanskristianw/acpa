<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");

    if(!empty($_POST["option_siswa"])) {

        $d_m_k_id = $_POST["option_siswa"];
        
        $query =    "DELETE FROM detail_mapel_khusus_master
                     WHERE d_m_k_id= $d_m_k_id";

        
        if (!mysqli_query($conn, $query))
        {
            echo("<br> Error description: " . mysqli_error($conn));
        }
        else{
            echo '<div class="alert alert-success alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>Siswa berhasil dihapus dari pendaftaran</strong>
                </div>';
        }
    }
    
?>
