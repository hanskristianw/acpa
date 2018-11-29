<?php

    include ("../includes/db_con.php");
    
    $kelas_id = $_POST['option_kelas'];
    
    $resultCheck = -1;
    if($kelas_id > 0) {

        //cek pernah isi atau belum
        
        $query =    "SELECT *
                    from moral_b
                    LEFT JOIN siswa 
                    ON moral_b_siswa_id = siswa_id
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    WHERE siswa_id_kelas = $kelas_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        if($resultCheck == 0){
            //jika belum pernah isi
            $query =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang
                        FROM siswa
                        WHERE siswa_id_kelas = {$kelas_id}";

            $query_afektif_info = mysqli_query($conn, $query);
            
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai TEKAN SAVE untuk menyimpan nilai
                </div>';
            
            echo "<form method='POST' id='add-pf_hb-form' action='moral/insert_moral.php'>";
                echo"<input type='hidden' name='kelas_id' value={$kelas_id}>";
                echo"<table class='table table-responsive table-sm table-striped table-bordered mt-3'>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Light Offences</th>
                        <th>Severe Offences</th>
                      </tr>
                    </thead>
                <tbody>";
                $absen = 1;
                    while($row = mysqli_fetch_array($query_afektif_info)){
                        $nama_belakang = $row['siswa_nama_belakang'];

                        echo'<tr>';
                            echo"<td>{$absen}</td>";
                            echo'<td>';
                            if(strlen($nama_belakang) > 0){
                                echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                            }else{
                                echo"{$row['siswa_nama_depan']}</td>";
                            }
                            
                        echo"<td>
                            <input type='hidden' name='siswa_id[]' value={$row['siswa_id']}>
                            <select class='form-control form-control-sm mb-2' name='option_lo[]' id='option_lo'>
                                <option value=4>A</option>
                                <option value=3>B</option>
                                <option value=2>C</option>
                                <option value=1>D</option>
                            </select></td>";    
                        
                        echo'<td><select class="form-control form-control-sm mb-2" name="option_so[]" id="option_so">
                                <option value=4>A</option>
                                <option value=3>B</option>
                                <option value=2>C</option>
                                <option value=1>D</option>
                            </select></td>';   
                        
                        echo '</tr>';
                        $absen++;
                    }
                echo'</tbody></table>';

                echo '<input type = "submit" value="SAVE" class="btn btn-success mt-2">';
            echo "</form>";
        }
        elseif($resultCheck > 1){
            //sudah pernah isi
            
            echo '<div class="alert alert-success alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>PERHATIAN:</strong> Tekan Update untuk melakukan update.
            </div>';

            echo "<form method='POST' id='update-emo-form' action='moral/update_moral.php'>";
                echo"<input type='hidden' name='kelas_id' value={$kelas_id}>";
                echo"<table class='table table-responsive table-sm table-striped table-bordered mt-3'>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Light Offences</th>
                        <th>Severe Offences</th>
                      </tr>
                    </thead>
                <tbody>";
                $absen = 1;
                    while($row = mysqli_fetch_array($query_afektif_info)){
                        $nama_belakang = $row['siswa_nama_belakang'];

                        echo'<tr>';
                            echo"<td>{$absen}</td>";
                            echo'<td>';
                            if(strlen($nama_belakang) > 0){
                                echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                            }else{
                                echo"{$row['siswa_nama_depan']}</td>";
                            }
                            
                        $moral_b_siswa_id = $row['moral_b_siswa_id'];
                        $moral_b_lo = $row['moral_b_lo'];
                        $moral_b_so = $row['moral_b_so'];
                        
                        echo"<td>
                            <input type='hidden' name='moral_b_id[]' value={$row['moral_b_id']}>
                            <select class='form-control form-control-sm mb-2' name='option_lo[]' id='option_lo'>";
                            if($moral_b_lo == 4){
                                echo "<option selected value=4>A</option>";
                            }else{
                                echo "<option value=4>A</option>";
                            }
                            
                            if($moral_b_lo == 3){
                                echo "<option selected value=3>B</option>";
                            }else{
                                echo "<option value=3>B</option>";
                            }
                                
                            if($moral_b_lo == 2){
                                echo "<option selected value=2>C</option>";
                            }else{
                                echo "<option value=2>C</option>";
                            }

                            if($moral_b_lo == 1){
                                echo "<option selected value=1>D</option>";
                            }else{
                                echo "<option value=1>D</option>";
                            }
                        echo"</select></td>";    
                        
                        echo'<td><select class="form-control form-control-sm mb-2" name="option_so[]" id="option_so">';
                            if($moral_b_so == 4){
                                echo "<option selected value=4>A</option>";
                            }else{
                                echo "<option value=4>A</option>";
                            }
                            
                            if($moral_b_so == 3){
                                echo "<option selected value=3>B</option>";
                            }else{
                                echo "<option value=3>B</option>";
                            }
                                
                            if($moral_b_so == 2){
                                echo "<option selected value=2>C</option>";
                            }else{
                                echo "<option value=2>C</option>";
                            }

                            if($moral_b_so == 1){
                                echo "<option selected value=1>D</option>";
                            }else{
                                echo "<option value=1>D</option>";
                            }
                        echo'</select></td>';   
                        
                        echo '</tr>';
                        $absen++;
                    }
                echo'</tbody></table>';

                echo '<input type = "submit" value="UPDATE" class="btn btn-success mt-2">';
            echo "</form>";
            
        }
    }
?>

<script>     
    $(document).ready(function(){
        
        //ketika user menekan tombol submit
        $("#add-pf_hb-form").submit(function(evt){
            evt.preventDefault();
            
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_notif").html(show);
                        $("#kotak").hide();
                    }
                }
            });
        });
        
        $("#update-emo-form").submit(function(evt){
            evt.preventDefault();
            
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_notif").html(show);
                        $("#kotak").hide();
                    }
                }
            });
        });
    });
</script>