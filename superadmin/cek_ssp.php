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

    if(isset($_POST['ssp_id_option'])){
        
        
        include ("../includes/db_con.php");
        
        $ssp_id = $_POST['ssp_id_option'];
        $d_ssp_ssp_id = $_POST['option_topik'];
        
        if($ssp_id > 0 && $d_ssp_ssp_id>0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu

            echo "<b>SSP ID:</b> ".$ssp_id."<br>";
            echo "<b>Rubrik ID:</b> ".$d_ssp_ssp_id."<br>";
            
            $query2 =   "SELECT * from ssp_nilai
                        LEFT JOIN d_ssp
                        ON ssp_nilai_d_ssp_id =  d_ssp_id
                        LEFT JOIN siswa
                        ON ssp_nilai_siswa_id =  siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas =  kelas_id
                        WHERE d_ssp_id = $d_ssp_ssp_id
                        ORDER BY kelas_id, siswa_nama_depan";

            $query_info2 = mysqli_query($conn, $query2);
            $resultCheck = mysqli_num_rows($query_info2);
            
            
            $query3 =   "SELECT *
                        FROM ssp_daftar
                        WHERE ssp_daftar_ssp_id = $ssp_id";
            $query_info3 = mysqli_query($conn, $query3);
            $resultCheck2 = mysqli_num_rows($query_info3);
            
            echo "<b>Pada tabel ssp_nilai terdapat:</b> ".$resultCheck." nilai";
            echo "<br>";
            echo "<b>Di SSP terdaftar:</b> ".$resultCheck2." siswa";
            
            echo '<form method="POST" id="delete-kog-psi-form" action="superadmin/delete_nilai_ssp.php">';
            
                echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                         <tr>
                           <th>Delete</th>
                           <th>ssp_nilai_id</th>
                           <th>Nama siswa</th>
                           <th>Kelas</th>
                           <th>ssp_nilai_angka</th>
                         </tr>
                         ";

                while($row2 = mysqli_fetch_array($query_info2)){
                    echo "<tr>
                          <td><input type='checkbox' name='check_ssp_id[]' value={$row2['ssp_nilai_id']}></td>
                          <td>{$row2['ssp_nilai_id']}</td>
                          <td>{$row2['siswa_nama_depan']} {$row2['siswa_nama_belakang']}</td>
                          <td>{$row2['kelas_nama']}</td>
                          <td>{$row2['ssp_nilai_angka']}</td>
                         </tr>";
                }

                echo "</table>";
                echo '<input type="submit" name="submit_delete" class="btn btn-primary mt-3" value="Delete Nilai SSP">';
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