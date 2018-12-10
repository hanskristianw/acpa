<?php

    include ("../includes/db_con.php");
    
    $kelas_id = $_POST['kelas_option'];
    
    $resultCheck = -1;
    if($kelas_id > 0) {

        //cek pernah isi atau belum
        
        $query =    "SELECT *
                    from scout_nilai
                    LEFT JOIN siswa
                    ON scout_nilai_siswa_id = siswa_id
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
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai scout TEKAN SAVE untuk menyimpan nilai
                </div>';
            
            echo "<form method='POST' id='add-scout_nilai-form' action='scout/insert_scout_nilai.php'>";
                echo"<input type='hidden' name='kelas_id' value={$kelas_id}>";
                echo"<table class='table table-responsive table-sm table-striped table-bordered mt-3'>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nilai Scout</th>
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
                            <select class='form-control form-control-sm mb-2' name='option_scout_nilai[]' id='option_scout_nilai'>
                                <option value=4>A</option>
                                <option value=3>B</option>
                                <option value=2>C</option>
                                <option value=1>D</option>
                            </select></td>";    

                        echo '</tr>';
                        $absen++;
                    }
                echo'</tbody></table>';

                echo '<input type = "submit" value="SAVE" id="submit-scout-nilai" class="btn btn-success mt-2">';
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

            echo "<form method='POST' id='update-scout_nilai-form' action='scout/update_scout_nilai.php'>";
                echo"<input type='hidden' name='kelas_id' value={$kelas_id}>";
                echo"<table class='table table-responsive table-sm table-striped table-bordered mt-3'>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nilai Scout</th>
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
                            
                        $scout_nilai_angka = $row['scout_nilai_angka'];
                        
                        echo"<td>
                            <input type='hidden' name='scout_nilai_id[]' value={$row['scout_nilai_id']}>
                            <select class='form-control form-control-sm mb-2' name='option_scout_nilai[]' id='option_scout_nilai'>";
                            if($scout_nilai_angka == 4){
                                echo "<option selected value=4>A</option>";
                            }else{
                                echo "<option value=4>A</option>";
                            }
                            
                            if($scout_nilai_angka == 3){
                                echo "<option selected value=3>B</option>";
                            }else{
                                echo "<option value=3>B</option>";
                            }
                                
                            if($scout_nilai_angka == 2){
                                echo "<option selected value=2>C</option>";
                            }else{
                                echo "<option value=2>C</option>";
                            }

                            if($scout_nilai_angka == 1){
                                echo "<option selected value=1>D</option>";
                            }else{
                                echo "<option value=1>D</option>";
                            }
                        echo"</select></td>";    

                        echo '</tr>';
                        $absen++;
                    }
                echo'</tbody></table>';

                echo '<input type = "submit" value="UPDATE" id="update-scout-nilai" class="btn btn-success mt-2">';
            echo "</form>";
            
        }
    }
?>

<script>     
    $(document).ready(function(){
        
        //ketika user menekan tombol submit
        $("#add-scout_nilai-form").submit(function(evt){
            evt.preventDefault();
            $("#submit-scout-nilai").attr("disabled", true);
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_notif").html(show);
                        $("#kotak_utama").hide();
                        $("#submit-scout-nilai").attr("disabled", false);
                    }
                }
            });
        });
        
        $("#update-scout_nilai-form").submit(function(evt){
            evt.preventDefault();
            $("#update-scout-nilai").attr("disabled", true);
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_notif").html(show);
                        $("#kotak_utama").hide();
                        $("#update-scout-nilai").attr("disabled", false);
                    }
                }
            });
        });
    });
</script>