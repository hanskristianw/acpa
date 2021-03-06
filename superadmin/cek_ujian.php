<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
?>

<?php

    if(isset($_POST['mapel_id_option'])){
        
        
        include ("../includes/db_con.php");
        
        $mapel_id = $_POST['mapel_id_option'];
        $kelas_id = $_POST['kelas_id_option'];
        
        if($mapel_id > 0 && $kelas_id>0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu

            echo "<b>Kelas ID:</b> ".$kelas_id."<br>";
            echo "<b>Mapel ID:</b> ".$mapel_id."<br>";
            
            $query2 =   "SELECT *
                        FROM kog_psi_ujian
                        LEFT JOIN siswa
                        ON kog_psi_ujian_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE kog_psi_ujian_mapel_id= $mapel_id AND siswa_id_kelas = $kelas_id";

            $query_info2 = mysqli_query($conn, $query2);
            $resultCheck = mysqli_num_rows($query_info2);
            
            
            $query3 =   "SELECT *
                        FROM siswa
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE siswa_id_kelas = $kelas_id ORDER BY siswa_no_induk";
            $query_info3 = mysqli_query($conn, $query3);
            $resultCheck2 = mysqli_num_rows($query_info3);
            
            echo "<b>Pada tabel kog_psi_ujian terdapat:</b> ".$resultCheck." nilai";
            echo "<br>";
            echo "<b>Di kelas terdapat:</b> ".$resultCheck2." siswa";
            
            echo '<form method="POST" id="delete-kog-psi-form" action="superadmin/delete_nilai_uts_uas.php">';
            
            
                echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                         <tr>
                           <th>Delete</th>
                           <th>Kog_psi_ujian_id</th>
                           <th>Nama siswa</th>
                           <th>kog_uts</th>
                           <th>kog_uas</th>
                           <th>psi_uts</th>
                           <th>psi_uas</th>
                         </tr>
                         ";

                while($row2 = mysqli_fetch_array($query_info2)){
                    echo "<tr>
                          <td><input type='checkbox' name='check_kog_psi_ujian_id[]' value={$row2['kog_psi_ujian_id']}></td>
                          <td>{$row2['kog_psi_ujian_id']}</td>
                          <td>{$row2['siswa_nama_depan']} {$row2['siswa_nama_belakang']}</td>
                          <td>{$row2['kog_uts']}</td>
                          <td>{$row2['kog_uas']}</td>
                          <td>{$row2['psi_uts']}</td>
                          <td>{$row2['psi_uas']}</td>
                         </tr>";
                }

                echo "</table>";
                echo '<input type="submit" name="submit_delete" class="btn btn-primary mt-3" value="Delete Nilai UTS UAS">';
            echo '</form>';
        }
    }
?>

<script> 
    $(document).ready(function(){
        //ketika user menekan tombol submit
        $("#delete-kog-psi-form").submit(function(evt){
            evt.preventDefault();
            
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#feedback").html(show);
                    }
                }
            });
        });
    });
</script>