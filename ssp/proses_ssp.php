<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['ssp_id'])){
        $ssp_id = mysqli_real_escape_string($conn, $_POST['ssp_id']);
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        
        $query = "SELECT * FROM ssp WHERE ssp_id = {$ssp_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete SSP</h4>";
        echo "<input type='text' placeholder='Masukkan nama SSP' rel='".$row['ssp_id']."' class='form-control ssp_nama mb-3' value='".$row['ssp_nama']."'>";
       
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT * FROM guru WHERE guru_active = 1";
        $result = mysqli_query($conn, $sql);
        $options = "<option value=0>Pilih Guru</option>";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['guru_id'] == $guru_id)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['guru_id']}>{$row2['guru_name']}</option>";
        }
        
        echo"<select class='form-control form-control mb-3 guru_option' name='guru_option'>";
        echo $options;
        echo"</select>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatessp'])){
        $ssp_id = mysqli_real_escape_string($conn, $_POST['ssp_id']);
        $ssp_nama = mysqli_real_escape_string($conn, $_POST['ssp_nama']);
        $ssp_a = mysqli_real_escape_string($conn, $_POST['ssp_a']);
        $ssp_b = mysqli_real_escape_string($conn, $_POST['ssp_b']);
        $ssp_c = mysqli_real_escape_string($conn, $_POST['ssp_c']);
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        
        
        $query_updatessp = "UPDATE ssp SET ssp_nama = '$ssp_nama', ssp_guru_id = $guru_id WHERE ssp_id = $ssp_id";
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
        var ssp_id;
        var ssp_nama;
        var ssp_a;
        var ssp_b;
        var ssp_c;
        var guru_id;
        var updatessp = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            ssp_id =$(".ssp_nama").attr("rel");
            ssp_nama =$(".ssp_nama").val();
            guru_id = $('.guru_option').val();


            if ($.trim(ssp_nama).length === 0){
                // string is invalid
                alert("Isi nama ssp dengan benar!");
            }else{
                //mengirim nilai ke halaman php tujuan
                $.post("ssp/proses_ssp.php",{ssp_id: ssp_id, ssp_nama:ssp_nama, guru_id:guru_id, updatessp: updatessp}, function(data){
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