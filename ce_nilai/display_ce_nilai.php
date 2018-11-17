<?php

    include ("../includes/db_con.php");
    
    $ce_id = $_POST['option_aspek'];
    $kelas_id = $_POST['option_kelas'];
    
    $resultCheck = -1;
    if($kelas_id > 0 && $ce_id >0) {

        //cek pernah isi atau belum
        
        $query =    "SELECT * from ce_nilai
                    LEFT JOIN siswa
                    ON ce_nilai_siswa_id =  siswa_id
                    LEFT JOIN kelas
                    ON siswa_id_kelas =  kelas_id
                    WHERE kelas_id = $kelas_id AND ce_nilai_ce_id = $ce_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        $queryx =  "SELECT * from ce
                    WHERE ce_id = $ce_id";

        $query_infox = mysqli_query($conn, $queryx);
        
        while($row = mysqli_fetch_array($query_infox)){
            $nama_aspek = $row['ce_aspek'];
        }
        //jika belum pernah isi
        if($resultCheck == 0){
            
            $query ="SELECT *
                    FROM siswa
                    WHERE siswa_id_kelas = $kelas_id";

            $query_afektif_info = mysqli_query($conn, $query);
            
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai CE TEKAN SAVE untuk menyimpan nilai
                </div>';
            
            
            echo '<div class= "text-center"><h4>Nama Aspek: '.$nama_aspek.'</h4></div>';
            
            echo '<form method="POST" id="add-nilai-form" action="ce_nilai/add_nilai_ce.php">';
            
                echo"<input type='hidden' name='ce_id' id='ce_id' value=$ce_id>";
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

                echo '<input type = "submit" value="SAVE" class="btn btn-success mt-2">';
            
            echo '</form>';
        }
        elseif($resultCheck > 1){
            
           
            //sudah pernah isi nilai
            echo '<div class="alert alert-success alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Tekan update untuk melakukan update nilai
                </div>';

            $query_afektif_info = mysqli_query($conn, $query);
            
                
            echo '<div class= "text-center"><h4>Nama Aspek: '.$nama_aspek.'</h4></div>';
            
            echo '<form method="POST" id="add-nilai-form-update" action="ce_nilai/update_nilai_ce.php">';
            
                
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
                            <input type='hidden' name='ce_nilai_id[]' value={$row['ce_nilai_id']}>
                            </td>";
                            
                            echo'<td>';
                            if(strlen($nama_belakang) > 0){
                                echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                            }else{
                                echo"{$row['siswa_nama_depan']}</td>";
                            }

                            echo "<td><select class='form-control form-control-sm mb-2' name='option_nilai[]' id='option_nilai'>";
                            
                            $ssp_nilai_angka = $row['ce_nilai_angka'];
                            
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
                
                echo '<input type = "submit" value="UPDATE" class="btn btn-success mt-2">';
            
            echo '</form>';
        }

        
    }
?>

<script>
        
    $(document).ready(function(){
        $("#add-nilai-form").submit(function(evt){
            evt.preventDefault();

            //alert("pilihan benar");
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            //input rubrik
            $.post(url,postData, function(php_table_data){
                $("#kotak_utama").hide();
                //$("#kotak_utama2").show();
                $("#feedback").html(php_table_data);
            });
        });
        
        $("#add-nilai-form-update").submit(function(evt){
            evt.preventDefault();

            //alert("pilihan benar");
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            //input rubrik
            $.post(url,postData, function(php_table_data){
                $("#kotak_utama").hide();
                //$("#kotak_utama2").show();
                $("#feedback").html(php_table_data);
            });
        });
        
    });
</script>