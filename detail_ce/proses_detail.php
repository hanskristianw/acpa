<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['d_ce_id'])){
        $d_ce_id = mysqli_real_escape_string($conn, $_POST['d_ce_id']);
        
        $query = "SELECT * FROM d_ce WHERE d_ce_id = {$d_ce_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete INDIKATOR</h4>";
        echo "<input type='text' placeholder='Masukkan aspek CE' rel='".$row['d_ce_id']."' class='form-control ce_nama mb-3' value='".$row['d_ce_nama']."'>";
       
        echo '<textarea class="form-control form-control-sm mb-2 aspek_a" rows="5" name="aspek_a" placeholder="Aspek Jika A">'.$row['d_ce_a'].'</textarea>
              <textarea class="form-control form-control-sm mb-2 aspek_b" rows="5" name="aspek_b" placeholder="Aspek Jika B">'.$row['d_ce_b'].'</textarea>
              <textarea class="form-control form-control-sm mb-2 aspek_c" rows="5" name="aspek_c" placeholder="Aspek Jika C">'.$row['d_ce_c'].'</textarea>
              ';
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatessp'])){
        $d_ce_id = mysqli_real_escape_string($conn, $_POST['d_ce_id']);
        $d_ce_nama = mysqli_real_escape_string($conn, $_POST['d_ce_nama']);
        $d_ce_a = mysqli_real_escape_string($conn, $_POST['d_ce_a']);
        $d_ce_b = mysqli_real_escape_string($conn, $_POST['d_ce_b']);
        $d_ce_c = mysqli_real_escape_string($conn, $_POST['d_ce_c']);
        
        $query_updatessp = "UPDATE d_ce SET d_ce_nama = '$d_ce_nama', d_ce_a = '$d_ce_a', d_ce_b = '$d_ce_b', d_ce_c = '$d_ce_c' WHERE d_ce_id = $d_ce_id";
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
        var d_ce_id;
        var d_ce_nama;
        var d_ce_a;
        var d_ce_b;
        var d_ce_c;
        var updatessp = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            d_ce_id =$(".ce_nama").attr("rel");
            d_ce_nama =$(".ce_nama").val();
            d_ce_a =$(".aspek_a").val();
            d_ce_b =$(".aspek_b").val();
            d_ce_c =$(".aspek_c").val();


            if ($.trim(d_ce_nama).length === 0){
                // string is invalid
                alert("Isi nama ssp dengan benar!");
            }else{
                //mengirim nilai ke halaman php tujuan
                $.post("detail_ce/proses_detail.php",{d_ce_id: d_ce_id, d_ce_nama:d_ce_nama, d_ce_a:d_ce_a, d_ce_b:d_ce_b, d_ce_c:d_ce_c, updatessp: updatessp}, function(data){
                    $("#feedback").text("Data berhasil diupdate");
                    $('#option_tema_ce2').val(0).change();
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