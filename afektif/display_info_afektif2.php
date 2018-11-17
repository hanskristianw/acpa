<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");

    
    
    
    if(!empty($_POST["option_bulan_afektif"])) {
        
        //nama bulan
        $bulan_afektif = $_POST["option_bulan_afektif"];
            
        $nama_bulan = "";
        if($bulan_afektif == 1){$nama_bulan = "Januari";}
        elseif($bulan_afektif == 2){$nama_bulan = "Februari";}
        elseif($bulan_afektif == 3){$nama_bulan = "Maret";}
        elseif($bulan_afektif == 4){$nama_bulan = "April";}
        elseif($bulan_afektif == 5){$nama_bulan = "Mei";}
        elseif($bulan_afektif == 6){$nama_bulan = "Juni";}
        elseif($bulan_afektif == 7){$nama_bulan = "Juli";}
        elseif($bulan_afektif == 8){$nama_bulan = "Agustus";}
        elseif($bulan_afektif == 9){$nama_bulan = "September";}
        elseif($bulan_afektif == 10){$nama_bulan = "Oktober";}
        elseif($bulan_afektif == 11){$nama_bulan = "November";}
        elseif($bulan_afektif == 12){$nama_bulan = "Desember";}
            
        //nama kelas dan wali kelas    
        $kelas_id = $_POST["option_kelas"];
        $query = "SELECT kelas_nama, guru_name FROM kelas, guru WHERE kelas_wali_guru_id = guru_id AND kelas_id = {$kelas_id}";

        $query_walkel = mysqli_query($conn, $query);

        if(!$query_walkel){
            die("QUERY FAILED".mysqli_error($conn));
        }
            
        //kriteria afektif
        $query = "SELECT k_afektif_1, k_afektif_2, k_afektif_3, k_afektif_id, k_afektif_bulan
                FROM k_afektif, t_ajaran 
                WHERE k_afektif_t_ajaran_id = t_ajaran_id AND
                t_ajaran_active = 1 AND 
                k_afektif_bulan = {$_POST["option_bulan_afektif"]}";

        $query_k_afektif_info = mysqli_query($conn, $query);

        if(!$query_k_afektif_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        //tampilan end user
        echo'<div class="d-flex justify-content-center">';
        echo"<h2><u>Rekapitulasi afektif bulan {$nama_bulan}</u></h2>";
        echo'</div>';
        
        while($row2 = mysqli_fetch_array($query_walkel)){
            echo'<div class="text-center">';
            echo"<h5>KELAS: {$row2['kelas_nama']}<br>WALI KELAS: {$row2['guru_name']}</h5>";
            echo'</div>';
        }
        echo'<div class="text-center">';
        echo"<h4><u>Kriteria Afektif:</u></h4>";
        while($row = mysqli_fetch_array($query_k_afektif_info)){
            echo"<h6>1.{$row['k_afektif_1']}<br><br>";
            echo"2.{$row['k_afektif_2']}<br><br>";
            echo"3.{$row['k_afektif_3']}</h6><br>";
        }
        echo'</div>';
    }
?>