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

    if(isset($_POST['option_kelas'])){
        
        
        include ("../includes/db_con.php");
        
        $kelas_id = $_POST['option_kelas'];
        $d_ce_id = $_POST['option_indikator'];
        
        if($kelas_id>0 && $d_ce_id>0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu

            echo "<b>Kelas ID:</b> ".$kelas_id."<br>";
            echo "<b>D_ce_ID ID:</b> ".$kelas_id."<br>";
            
            $query2 =   "SELECT * from ce_nilai
                        LEFT JOIN d_ce
                        ON ce_nilai_d_ce_id =  d_ce_id
                        LEFT JOIN siswa
                        ON ce_nilai_siswa_id =  siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas =  kelas_id
                        WHERE kelas_id = $kelas_id AND ce_nilai_d_ce_id = $d_ce_id";

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
            
            echo '<form method="POST" id="delete-kog-psi-form" action="superadmin/delete_nilai_cb.php">';
            
            
                echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                         <tr>
                           <th>Delete</th>
                           <th>ce_nilai_id</th>
                           <th>Nama siswa</th>
                           <th>Nilai CB</th>
                         </tr>
                         ";

                while($row2 = mysqli_fetch_array($query_info2)){
                    echo "<tr>
                          <td><input type='checkbox' name='check_ce_id[]' value={$row2['ce_nilai_id']}></td>
                          <td>{$row2['ce_nilai_id']}</td>
                          <td>{$row2['siswa_nama_depan']} {$row2['siswa_nama_belakang']}</td>
                          <td>{$row2['ce_nilai_angka']}</td>
                         </tr>";
                }

                echo "</table>";
                echo '<input type="submit" name="submit_delete" class="btn btn-primary mt-3" value="Delete CB">';
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