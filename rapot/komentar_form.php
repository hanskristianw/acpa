<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] == 4 || $_SESSION['guru_jabatan'] == 3){
        header("Location: index.php");
    }
?>
<?php

    if(isset($_POST['siswa_id'])){
        $siswa_id = $_POST['siswa_id'];
        
        if($siswa_id > 0){
            include '../includes/db_con.php';
            $query_komentar = "SELECT siswa_komen, siswa_absenin, siswa_absenex, siswa_tardy, siswa_special_note
                            FROM siswa
                            WHERE siswa_id = {$siswa_id}";
                    
            $query_komentar_info = mysqli_query($conn, $query_komentar);        
            if(!$query_komentar_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row_mapel = mysqli_fetch_array($query_komentar_info)){
                $siswa_komen = $row_mapel['siswa_komen'];
                $siswa_absenin = $row_mapel['siswa_absenin'];
                $siswa_absenex = $row_mapel['siswa_absenex'];
                $siswa_tardy = $row_mapel['siswa_tardy'];
                $siswa_special_note = $row_mapel['siswa_special_note'];
                
                 
                echo '<label>Komentar:</label>';
                if($siswa_komen != ""){
                    echo '<textarea class="form-control mb-2" rows="5" name="siswa_komen" id="comment">'.$siswa_komen.'</textarea>';
                }else{
                    echo '<textarea class="form-control mb-2" rows="5" name="siswa_komen" id="comment" placeholder="Masukkan komentar"></textarea>';
                }
                
                echo '<label>Absen Including Excuse:</label>';
                echo '<input type="number" name="siswa_absenin" class="form-control form-control-sm mb-2" id="siswa_absenin" placeholder="Jumlah Absen Including Excuse" required value ='.$siswa_absenin.'>';
                echo '<label>Absen Excluding Excuse:</label>';
                echo '<input type="number" name="siswa_absenex" class="form-control form-control-sm mb-2" id="siswa_absenex" placeholder="Jumlah Absen Excluding Excuse" required value ='.$siswa_absenex.'>';
                echo '<label>Sick:</label>';
                echo '<input type="number" name="siswa_tardy" class="form-control form-control-sm mb-2" id="siswa_tardy" placeholder="Jumlah Sick" required value ='.$siswa_tardy.'>';
                echo '<label>Special Note (hanya untuk rapor akhir):</label>';
                echo '<input type="text" name="siswa_special_note" class="form-control form-control-sm mb-2" id="siswa_special_note" placeholder="Masukkan special note" value ='.$siswa_special_note.'>';
                
            }
        }
    }