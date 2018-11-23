<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['ce_id'])){
        $ce_id = mysqli_real_escape_string($conn, $_POST['ce_id']);
        $jenjang_id = mysqli_real_escape_string($conn, $_POST['jenjang_id']);
        
        $query = "SELECT * FROM ce WHERE ce_id = {$ce_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete TOPIK</h4>";
        echo "<input type='text' placeholder='Masukkan topik CB' rel='".$row['ce_id']."' class='form-control ce_nama mb-3' value='".$row['ce_aspek']."'>";
        
        $sql = "SELECT * FROM jenjang";
        $result = mysqli_query($conn, $sql);
        $options = "<option value=0>Pilih Jenjang</option>";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['jenjang_id'] == $jenjang_id)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['jenjang_id']}>{$row2['jenjang_nama']}</option>";
        }
        echo"<select class='form-control form-control-sm mb-3 jenjang_option' name='jenjang_option'>";
        echo $options;
        echo"</select>";
        echo '<textarea class="form-control form-control-sm mb-2 aspek_a" rows="5" name="aspek_a" placeholder="Aspek Jika A">'.$row['ce_a'].'</textarea>
              <textarea class="form-control form-control-sm mb-2 aspek_b" rows="5" name="aspek_b" placeholder="Aspek Jika B">'.$row['ce_b'].'</textarea>
              <textarea class="form-control form-control-sm mb-2 aspek_c" rows="5" name="aspek_c" placeholder="Aspek Jika C">'.$row['ce_c'].'</textarea>
              ';
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatessp'])){
        $ce_id = mysqli_real_escape_string($conn, $_POST['ce_id']);
        $ce_nama = mysqli_real_escape_string($conn, $_POST['ce_nama']);
        $ce_jenjang_id = mysqli_real_escape_string($conn, $_POST['ce_jenjang_id']);
        $ce_a = mysqli_real_escape_string($conn, $_POST['ce_a']);
        $ce_b = mysqli_real_escape_string($conn, $_POST['ce_b']);
        $ce_c = mysqli_real_escape_string($conn, $_POST['ce_c']);
        
        $query_updatessp = "UPDATE ce SET ce_aspek = '$ce_nama', ce_jenjang_id = '$ce_jenjang_id', ce_a = '$ce_a', ce_b = '$ce_b', ce_c = '$ce_c' WHERE ce_id = $ce_id";
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
        var ce_id;
        var ce_nama;
        var ce_a;
        var ce_b;
        var ce_c;
        var updatessp = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            ce_id =$(".ce_nama").attr("rel");
            ce_nama =$(".ce_nama").val();
            ce_jenjang_id =$(".jenjang_option").val();
            ce_a =$(".aspek_a").val();
            ce_b =$(".aspek_b").val();
            ce_c =$(".aspek_c").val();


            if ($.trim(ce_nama).length === 0){
                // string is invalid
                alert("Isi nama ssp dengan benar!");
            }else{
                //mengirim nilai ke halaman php tujuan
                $.post("aspek_ce/proses_aspek_ce.php",{ce_id: ce_id, ce_nama:ce_nama, ce_jenjang_id:ce_jenjang_id, ce_a:ce_a, ce_b:ce_b, ce_c:ce_c, updatessp: updatessp}, function(data){
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