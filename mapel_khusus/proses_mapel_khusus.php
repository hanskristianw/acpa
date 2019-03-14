<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['mapel_k_m_id'])){
        $mapel_k_m_id = mysqli_real_escape_string($conn, $_POST['mapel_k_m_id']);
        $mapel_k_m_mapel_id = mysqli_real_escape_string($conn, $_POST['mapel_k_m_mapel_id']);
        
        $query = "SELECT * FROM mapel_khusus_master WHERE mapel_k_m_id = {$mapel_k_m_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete SSP</h4>";
        
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT mapel_id, mapel_nama 
                FROM mapel
                LEFT JOIN t_ajaran
                ON mapel_t_ajaran_id = t_ajaran_id
                WHERE t_ajaran_active = 1";

        $result = mysqli_query($conn, $sql);
        $options = "<option value=0>Pilih Mapel</option>";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['mapel_id'] == $mapel_k_m_mapel_id)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['mapel_id']}>{$row2['mapel_nama']}</option>";
        }
        
        echo"<select class='form-control form-control mb-3 mapel_option' name='mapel_option'>";
        echo $options;
        echo"</select>";
        
        echo "<input type='text' placeholder='Masukkan nama mapel khusus' rel='".$row['mapel_k_m_id']."' class='form-control ssp_nama mb-3' value='".$row['mapel_k_m_nama']."'>";
       
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatessp'])){
        $mapel_k_m_id = mysqli_real_escape_string($conn, $_POST['mapel_k_m_id']);
        $mapel_k_m_nama = mysqli_real_escape_string($conn, $_POST['mapel_k_m_nama']);
        $mapel_k_m_mapel_id = mysqli_real_escape_string($conn, $_POST['mapel_k_m_mapel_id']);
        
        $query_updatessp = "UPDATE mapel_khusus_master SET mapel_k_m_nama = '$mapel_k_m_nama', mapel_k_m_mapel_id = $mapel_k_m_mapel_id WHERE mapel_k_m_id = $mapel_k_m_id";
        $result_setssp = mysqli_query($conn, $query_updatessp);
        
        if(!$result_setssp){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
    
    /************************Set guru menjadi tidak aktif************************/
    if(isset($_POST['deleteguru'])){
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        
        $query = "UPDATE guru SET guru_active = 0 WHERE guru_id = $guru_id";
        
        $result_set = mysqli_query($conn, $query);
        
        if(!$result_set){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
?>
<script>
    $(document).ready(function(){
        var mapel_k_m_id;
        var mapel_k_m_nama;
        var mapel_k_m_mapel_id;
        
        var updatessp = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            mapel_k_m_id =$(".ssp_nama").attr("rel");
            mapel_k_m_nama =$(".ssp_nama").val();
            mapel_k_m_mapel_id = $('.mapel_option').val();

            if ($.trim(mapel_k_m_nama).length === 0){
                // string is invalid
                alert("Isi nama dengan benar!");
            }else{
                //mengirim nilai ke halaman php tujuan
                $.post("mapel_khusus/proses_mapel_khusus.php",{mapel_k_m_id: mapel_k_m_id, mapel_k_m_nama:mapel_k_m_nama, mapel_k_m_mapel_id:mapel_k_m_mapel_id, updatessp: updatessp}, function(data){
                    $("#feedback").text("Data berhasil diupdate");
                    //alert(data);
                });
            }
            
        });
        
        /************************DELETE BUTTON FUNCTION************************/
        $(".delete").on('click', function(){
//            if(confirm('Are you sure you want to delete this')){
//                guru_id = $(".guru_name").attr('rel');
//                $.post("guru/proses_guru.php",{guru_id: guru_id, deleteguru: deleteguru}, function(data){
//                    $("#container-guru").hide();
//                });
//            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-ssp").hide();
        });
    });
</script>