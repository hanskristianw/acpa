<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['id'])){
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $id2 = mysqli_real_escape_string($conn, $_POST['id2']);
        
        $query = "SELECT * FROM topik WHERE topik_id = {$id}";
        $query_guru_info = mysqli_query($conn, $query);

        if(!$query_guru_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_guru_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika diklik
        echo "<h4 class='mb-3'>Update Topik</h4>";
        echo "<label>Deskripsi Topik</label>";
        echo "<input type='text' rel='".$row['topik_id']."' class='form-control topik_nama mb-3' value='".$row['topik_nama']."'>";
        
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT * FROM jenjang";
        $result = mysqli_query($conn, $sql);
        $options = "";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['jenjang_id'] == $id2)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['jenjang_id']}>{$row2['jenjang_nama']}</option>";
        }
        echo "<label'>Berada pada jenjang:</label>";
        echo"<select class='form-control form-control mb-3 jenjang_option' name='jenjang_option'>";
        echo $options;
        echo"</select>";
        echo "<label'>Urutan:</label>";
        echo "<input type='number' class='form-control topik_urutan mb-3' value='".$row['topik_urutan']."'>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close mr-3' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updateguru'])){
        $topik_id = mysqli_real_escape_string($conn, $_POST['topik_id']);
        $topik_nama = mysqli_real_escape_string($conn, $_POST['topik_nama']);
        $jenjang_id = mysqli_real_escape_string($conn, $_POST['jenjang_id']);
        $topik_urutan = mysqli_real_escape_string($conn, $_POST['topik_urutan']);
        
        //cek dulu apakah topik sudah ada nilai
        $query =    "SELECT *
                    from kog_psi
                    WHERE kog_psi_topik_id = $topik_id";

        $query_cek_nilai = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_cek_nilai);
    
        if($resultCheck == 0){
            //update database topik
            $query_updateguru = "UPDATE topik SET topik_nama = '$topik_nama', topik_jenjang_id = '$jenjang_id', topik_urutan = $topik_urutan WHERE topik_id = $topik_id";
            $result_setguru = mysqli_query($conn, $query_updateguru);

            if(!$result_set){
                die("QUERY FAILED".mysqli_error($conn));
            }
            mysqli_close($conn);
        }else{
            $query_updateguru = "UPDATE topik SET topik_nama = '$topik_nama', topik_urutan = $topik_urutan WHERE topik_id = $topik_id";
            $result_setguru = mysqli_query($conn, $query_updateguru);

            if(!$result_set){
                die("QUERY FAILED".mysqli_error($conn));
            }
            mysqli_close($conn);
        }
        
    }
    
    /************************delete topik************************/
    if(isset($_POST['deletetopik'])){
        
        $topik_id = mysqli_real_escape_string($conn, $_POST['topik_id']);
        
        $query =    "SELECT *
                    from kog_psi
                    WHERE kog_psi_topik_id = $topik_id";

        $query_cek_nilai = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_cek_nilai);
    
        if($resultCheck == 0){
            $query = "DELETE FROM topik
                  WHERE topik_id= $topik_id";
       
            $result_set = mysqli_query($conn, $query);

            if(!$result_set){
                die("QUERY FAILED".mysqli_error($conn));
            }
            mysqli_close($conn);
        }
        
    }
?>
<script>
    $(document).ready(function(){
        var topik_id;
        var topik_nama;
        var jenjang_id;
        var topik_urutan;
        var updateguru = "update";
        var deletetopik = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            topik_id =$(".topik_nama").attr("rel");
            topik_nama = $(".topik_nama").val();
            jenjang_id = $(".jenjang_option").val();
            topik_urutan = $(".topik_urutan").val();

            if ($.trim(topik_nama).length === 0){
                // string is invalid
                alert("Isi nama topik dengan benar!");
            } 
            else{
                //mengirim nilai ke halaman php tujuan
                $.post("kognitif/proses_kognitif.php",{topik_id: topik_id, topik_nama:topik_nama, jenjang_id:jenjang_id, topik_urutan:topik_urutan, updateguru: updateguru}, function(data){
                    $("#feedback").text("Berhasil diupdate, jika topik sudah mempunyai nilai, jenjang tidak dapat dirubah");
                    //alert(data);
                });
            }
            
        });
        
        /************************DELETE BUTTON FUNCTION************************/
        $(".delete").on('click', function(){
            if(confirm('Jika nilai sudah ada pada topik ini, maka topik tidak akan dapat dihapus, coba untuk menghapus topik?')){
                topik_id = $(".topik_nama").attr('rel');
                $.post("kognitif/proses_kognitif.php",{topik_id: topik_id, deletetopik: deletetopik}, function(data){
                    $("#container-topik").hide();
                });
            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-topik").hide();
        });
    });
</script>