<?php

    include ("../includes/db_con.php");
    
    $ssp_id = $_POST['ssp_option'];
    $d_ssp_ssp_id = $_POST['option_rubrik'];
    
    $resultCheck = -1;
    if($ssp_id > 0 && $d_ssp_ssp_id >0) {
        //cek pernah isi atau belum
        
        $query =    "SELECT * from ssp_nilai
                    LEFT JOIN d_ssp
                    ON ssp_nilai_d_ssp_id =  d_ssp_id
                    LEFT JOIN siswa
                    ON ssp_nilai_siswa_id =  siswa_id
                    WHERE d_ssp_id = $d_ssp_ssp_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        $queryx =    "SELECT * from d_ssp
                    WHERE d_ssp_id = $d_ssp_ssp_id";

        $query_infox = mysqli_query($conn, $queryx);
        
        while($row = mysqli_fetch_array($query_infox)){
            $nama_rubrik = $row['d_ssp_kriteria'];
        }
        //jika belum pernah isi
        if($resultCheck == 0){
            
            $query ="SELECT *
                    FROM ssp_daftar
                    LEFT JOIN siswa
                    ON ssp_daftar_siswa_id = siswa_id
                    WHERE ssp_daftar_ssp_id = $ssp_id";

            $query_afektif_info = mysqli_query($conn, $query);
            
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai SSP TEKAN SAVE untuk menyimpan nilai
                </div>';
            
            
            echo '<div class= "text-center"><h4>Nama Rubrik: '.$nama_rubrik.'</h4></div>';
            
            echo '<form method="POST" id="add-nilai-form" action="ssp_nilai_input/proses_nilai_ssp.php">';
            
                echo"<input type='hidden' name='d_ssp_ssp_id' id='d_ssp_ssp_id' value=$d_ssp_ssp_id>";
                echo"<div style='overflow-x:auto;'>
                    <table class='table table-sm table-responsive table-striped table-bordered mt-3'><thead>";
                echo'<tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nilai</th>
                     </tr>
                </thead>
                <tbody>';
                    $absen = 1;
                    while($row = mysqli_fetch_array($query_afektif_info)){
                        $nama_belakang = $row['siswa_nama_belakang'];
                        
                        echo '<tr>';
                            echo'<td>';
                            echo"{$absen}
                            <input type='hidden' name='siswa_id[]' value={$row['siswa_id']}>
                            </td>";
                            
                            echo'<td>';
                            if(strlen($nama_belakang) > 0){
                                echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                            }else{
                                echo"{$row['siswa_nama_depan']}</td>";
                            }

                            echo "<td><select class='form-control form-control-sm mb-2' name='option_nilai[]' id='option_nilai'>
                                        <option value= 4>A</option>
                                        <option value= 3>B</option>
                                        <option value= 2>C</option>
                                        <option value= 1>D</option>
                                      </select>
                                  </td>";
                        echo '</tr>';

                        $absen++;
                    }
                echo'</tbody></table></div>';

                echo '<input type = "submit" value="SAVE" id="save_ssp_nilai" class="btn btn-success mt-2">';
            
            echo '</form>';
        }
        elseif($resultCheck > 1){
            
            $query_cek_rev =    "SELECT *
                                FROM ssp_revisi
                                LEFT JOIN ssp_nilai
                                ON ssp_rev_ssp_nilai_id = ssp_nilai_id
                                WHERE ssp_nilai_d_ssp_id = {$d_ssp_ssp_id} AND ssp_rev_status = 0";
            $query_jumlah_baris_rev = mysqli_query($conn, $query_cek_rev);
            $resultrev = mysqli_num_rows($query_jumlah_baris_rev);
            
            if($resultrev > 0){
                echo "<h4 class='text-center bg-danger'>Pengajuan revisi masih diproses</h2>";
            }elseif($resultrev == 0){
                
                //sudah pernah isi nilai
                echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>PERHATIAN:</strong> Proses UPDATE nilai harus disetujui wakakur
                    </div>';

                $query_afektif_info = mysqli_query($conn, $query);
            
                
            echo '<div class= "text-center"><h4>Nama Rubrik: '.$nama_rubrik.'</h4></div>';
            
            echo '<form method="POST" id="add-nilai-form-update" action="ssp_nilai_input/proses_nilai_ssp_update.php">';
            
                echo"<input type='hidden' name='d_ssp_ssp_id' id='d_ssp_ssp_id' value=$d_ssp_ssp_id>";
                echo"<div style='overflow-x:auto;'>
                    <table class='table table-sm table-responsive table-striped table-bordered mt-3'><thead>";
                echo'<tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nilai</th>
                     </tr>
                </thead>
                <tbody>';
                    $absen = 1;
                    while($row = mysqli_fetch_array($query_afektif_info)){
                        $nama_belakang = $row['siswa_nama_belakang'];
                        
                        echo '<tr>';
                            echo'<td>';
                            echo"{$absen}
                            <input type='hidden' name='siswa_id[]' value={$row['siswa_id']}>
                            <input type='hidden' name='ssp_nilai_id[]' value={$row['ssp_nilai_id']}>
                            </td>";
                            
                            echo'<td>';
                            if(strlen($nama_belakang) > 0){
                                echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                            }else{
                                echo"{$row['siswa_nama_depan']}</td>";
                            }

                            echo "<td><select class='form-control form-control-sm mb-2' name='option_nilai[]' id='option_nilai'>";
                            
                            $ssp_nilai_angka = $row['ssp_nilai_angka'];
                            
                            if($ssp_nilai_angka == 4){
                                echo"<option value= 4 selected>A</option>
                                     <option value= 3>B</option>
                                     <option value= 2>C</option>
                                     <option value= 1>D</option>";
                            }elseif($ssp_nilai_angka == 3){
                                echo"<option value= 4>A</option>
                                     <option value= 3 selected>B</option>
                                     <option value= 2>C</option>
                                     <option value= 1>D</option>";
                            }
                            elseif($ssp_nilai_angka == 2){
                                echo"<option value= 4>A</option>
                                     <option value= 3>B</option>
                                     <option value= 2 selected>C</option>
                                     <option value= 1>D</option>";
                            }
                            elseif($ssp_nilai_angka == 1){
                                echo"<option value= 4>A</option>
                                     <option value= 3>B</option>
                                     <option value= 2>C</option>
                                     <option value= 1 selected>D</option>";
                            }
                            echo "</select></td>";
                        echo '</tr>';

                        $absen++;
                    }
                echo'</tbody></table></div>';
                
                echo'<label><b>Alasan Update:</b></label>
                      <input type="text" name="alasan_update" id="alasan_update" placeholder="Masukkan Alasan Update (misal: remidial, salah input)" class="form-control form-control-sm mb-2" required>
                      ';
                
                echo '<input type = "submit" value="UPDATE" id="update_ssp_nilai" class="btn btn-success mt-2">';
            
            echo '</form>';
            }
        }

        
    }
?>

<script>
        
    $(document).ready(function(){
        $("#add-nilai-form").submit(function(evt){
            evt.preventDefault();

            $("#save_ssp_nilai").attr("disabled", true);
            //alert("pilihan benar");
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            //input rubrik
            $.post(url,postData, function(php_table_data){
                
                $("#kotak_utama").hide();
                $("#save_ssp_nilai").attr("disabled", false);
                //$("#kotak_utama2").show();
                $("#feedback").html(php_table_data);
            });
        });
        
        $("#add-nilai-form-update").submit(function(evt){
            evt.preventDefault();

            //alert("pilihan benar");
            $("#update_ssp_nilai").attr("disabled", true);
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            //input rubrik
            $.post(url,postData, function(php_table_data){
                $("#kotak_utama").hide();
                $("#update_ssp_nilai").attr("disabled", false);
                //$("#kotak_utama2").show();
                $("#feedback").html(php_table_data);
            });
        });
        
    });
</script>