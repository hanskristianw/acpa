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

    if(isset($_POST['option_topik'])){
        
        
        include ("../includes/db_con.php");
        
        $mapel_id = $_POST['mapel_id_option'];
        $topik_id = $_POST['option_topik'];
        $kelas_id = $_POST['kelas_id_option'];
        
        if($mapel_id > 0 && $topik_id>0 && $kelas_id>0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu

            echo "<b>Topik ID:</b> ".$topik_id."<br>";
            echo "<b>Kelas ID:</b> ".$kelas_id."<br>";
            echo "<b>Mapel ID:</b> ".$mapel_id."<br>";
            
            $query2 =   "SELECT *
                        FROM kog_psi
                        LEFT JOIN siswa
                        ON kog_psi_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE kog_psi_topik_id= $topik_id AND siswa_id_kelas = $kelas_id";

            $query_info2 = mysqli_query($conn, $query2);
            $resultCheck = mysqli_num_rows($query_info2);
            
            
            $query3 =   "SELECT *
                        FROM siswa
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE siswa_id_kelas = $kelas_id ORDER BY siswa_no_induk";
            $query_info3 = mysqli_query($conn, $query3);
            $resultCheck2 = mysqli_num_rows($query_info3);
            
            echo "<b>Pada tabel kog_psi terdapat:</b> ".$resultCheck." nilai";
            echo "<br>";
            echo "<b>Di kelas terdapat:</b> ".$resultCheck2." siswa";
            
            echo '<form method="POST" id="delete-kog-psi-form" action="superadmin/delete_nilai_siswa.php">';
            
            
                echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                         <tr>
                           <th>Delete</th>
                           <th>Kog_psi_id</th>
                           <th>Nama siswa</th>
                           <th>kq</th>
                           <th>ka</th>
                           <th>kt</th>
                           <th>pq</th>
                           <th>pa</th>
                           <th>pt</th>
                         </tr>
                         ";

                while($row2 = mysqli_fetch_array($query_info2)){
                    echo "<tr>
                          <td><input type='checkbox' name='check_kog_psi_id[]' value={$row2['kog_psi_id']}></td>
                          <td>{$row2['kog_psi_id']}</td>
                          <td>{$row2['siswa_nama_depan']} {$row2['siswa_nama_belakang']}</td>
                          <td>{$row2['kog_quiz']}</td>
                          <td>{$row2['kog_ass']}</td>
                          <td>{$row2['kog_test']}</td>
                          <td>{$row2['psi_quiz']}</td>
                          <td>{$row2['psi_ass']}</td>
                          <td>{$row2['psi_test']}</td>
                         </tr>";
                }

                echo "</table>";
                echo '<input type="submit" name="submit_delete" class="btn btn-primary mt-3" value="Delete Nilai Topik">';
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