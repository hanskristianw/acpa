<?php
    include_once '../includes/db_con.php';
    include_once '../includes/fungsi_lib.php';
    $ssp_id = $_POST["ssp_option"];
    $pil2 = $_POST["ssp_option2"];

    if($ssp_id > 0 && $pil2 > 0){
        if($pil2==1){
            //input kriteria
            $query = "SELECT * FROM ssp WHERE ssp_id = {$ssp_id}";
            $query_ssp_info = mysqli_query($conn, $query);

            if(!$query_ssp_info){
                die("QUERY FAILED".mysqli_error($conn));
            }

            $row = mysqli_fetch_array($query_ssp_info);
            
            echo '<form method="POST" id="ssp-rubrik-form" action="ssp_nilai/add-ssp-rubrik.php">';
                echo "<h4 class='text-center mb-3'><u>SSP {$row['ssp_nama']}</u></h4>";

                echo "<input type='hidden' name='ssp_id' value='".$ssp_id."'>";
                echo "<input type='text' placeholder='Masukkan rubrik/topik SSP' name='d_ssp_kriteria' class='form-control d_ssp_kriteria mb-3' required>";
                echo "<input type='text' placeholder='Masukkan deskripsi jika nilai A' name='d_ssp_a' class='form-control d_ssp_a mb-3' required>";
                echo "<input type='text' placeholder='Masukkan deskripsi jika nilai B' name='d_ssp_b' class='form-control d_ssp_b mb-3' required>";
                echo "<input type='text' placeholder='Masukkan deskripsi jika nilai C' name='d_ssp_c' class='form-control d_ssp_c mb-3' required>";
                
                echo'<input type="submit" name="submit_detail" class="btn btn-primary" value="Input Kriteria">
            </form>';
        }
        elseif($pil2==3) {
            
 
            $sql_cek_data_ssp = "SELECT * 
                                FROM ssp_nilai
                                LEFT JOIN d_ssp
                                ON ssp_nilai_d_ssp_id = d_ssp_id
                                LEFT JOIN ssp
                                ON d_ssp_ssp_id = ssp_id
                                WHERE ssp_id = $ssp_id";

            $result = mysqli_query($conn, $sql_cek_data_ssp);
            $resultCheck = mysqli_num_rows($result);

            if($resultCheck > 0){
                echo return_alert("Tidak dapat mendaftarkan siswa karena SSP sudah mempunyai nilai, hubungi kurikulum jika salah daftarkan siswa atau siswa pindah ssp", "danger");
            }
            else{
                //daftarkan siswa
                echo '<h4 class="text-center">Daftarkan Siswa</h4>';
                $sql3 = "SELECT kelas_id, kelas_nama FROM kelas,t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result3 = mysqli_query($conn, $sql3);
                $options3 = "<option value= 0>Pilih Kelas</option>";
                while ($row = mysqli_fetch_assoc($result3)) {
                    $options3 .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
                }
                
                echo'<div id="feedback_ssp"></div>';
                
                echo '<form method="POST" id="add-siswa-ssp-form" action="ssp_nilai/add-siswa-ssp.php">';
                    echo '<select class="form-control form-control-sm kelas_id mb-2" id="kelas_id" name="kelas_id">';
                    echo $options3;
                    echo '</select>';
                    
                    echo '<div id="container-daftar-siswa"></div>';
                    echo "<input type='hidden' id='ssp_id_hidden' name='ssp_id_hidden' class='ssp_id_hidden' value={$ssp_id}>";
                    
                    echo "<input type='submit' class='btn btn-primary mt-3' value='Daftarkan Siswa'>";
                echo '</form>';
            }
        }
    }

?>

<script>
    $(document).ready(function(){
        
        //ketika user menekan tombol submit
//        $("#ssp-detail-form").submit(function(evt){
//            evt.preventDefault();
//
//            //alert("pilihan benar");
//            var postData = $(this).serialize();
//            var url = $(this).attr('action');
//
//            $.post(url,postData, function(php_table_data){
//                $("#kotak_utama").hide();
//                $("#ssp-detail-form")[0].reset();
//                alert("data berhasil dirubah");
//            });
//
//        });
        
        $("#ssp-rubrik-form").submit(function(evt){
            evt.preventDefault();

            //alert("pilihan benar");
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#kotak_utama").hide();
                $("#kotak_utama2").hide();
                $("#ssp-rubrik-form")[0].reset();
                alert("data berhasil diinput");
            });

        });
        
        $("#kelas_id").change(function () {
            //alert("a");
            
            var kelas_id = $("#kelas_id").val();
            if(kelas_id != 0){
                $.ajax({
                    url: 'ssp_nilai/update_check_siswa.php',
                    data:'kelas_id='+ kelas_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#container-daftar-siswa").show();
                            $("#container-daftar-siswa").html(show);
                        }
                    }
                });
            }
        });
        
        $("#add-siswa-ssp-form").submit(function(evt){
            evt.preventDefault();

            var cek_pil1 = $("#kelas_id").val();

            if(cek_pil1>0){
                //alert("pilihan benar");
                var postData = $(this).serialize();
                var url = $(this).attr('action');
                
                $.ajax({
                    url: url,
                    data:$(this).serialize(),
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#feedback_ssp").html(show);
                            $("#add-siswa-ssp-form")[0].reset();
                            $("#container-daftar-siswa").hide();
                        }
                    }
                });
            }else{
                alert("pilihan harus benar");
            }

        });
        
        
    });
</script>