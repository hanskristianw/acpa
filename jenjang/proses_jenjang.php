<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['id'])){
        //id kelas
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        
        $query = "SELECT * FROM jenjang WHERE jenjang_id= {$id}";
        $query_guru_info = mysqli_query($conn, $query);

        if(!$query_guru_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_guru_info);
        
        //container untuk menampilkan pesan sukses
        echo'<div id="feedback" class="alert alert-success">';
          echo'<p class="bg-success"></p>';
        echo'</div>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update Nama Jenjang</h4>";
        echo "<label>Nama Jenjang</label>";
        echo "<input type='text' rel='".$row['jenjang_id']."' class='form-control jenjang_nama mb-3' value='".$row['jenjang_nama']."'>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatejenjang'])){
        $jenjang_id = mysqli_real_escape_string($conn, $_POST['jenjang_id']);
        $jenjang_nama = mysqli_real_escape_string($conn, $_POST['jenjang_nama']);
        
        //update database guru
        $query_updatekelas = "UPDATE jenjang SET jenjang_nama = '$jenjang_nama' WHERE jenjang_id = $jenjang_id";
        $result_setkelas = mysqli_query($conn, $query_updatekelas);
        
        if(!$result_setkelas){
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
        var jenjang_id;
        var jenjang_nama;
        var guru_id;
        var updatejenjang = "update";
        var deletekelas = "delete";
        
        $("#feedback").hide();
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            jenjang_id =$(".jenjang_nama").attr("rel");
            jenjang_nama = $(".jenjang_nama").val();

            if( !$(".jenjang_nama").val() ) {
                $("#feedback").text("Nama tidak boleh kosong");
            }
            else{
                //mengirim nilai ke halaman php tujuan
                $.post("jenjang/proses_jenjang.php",{jenjang_id: jenjang_id, jenjang_nama:jenjang_nama, updatejenjang:updatejenjang}, function(data){
                    $("#feedback").text("Data berhasil diupdate");
                    $("#feedback").show();
                });
            }
        });
        
        /************************DELETE BUTTON FUNCTION************************/
        $(".delete").on('click', function(){
            if(confirm('Are you sure you want to delete this')){
                guru_id = $(".guru_name").attr('rel');
                $.post("guru/proses_guru.php",{guru_id: guru_id, deleteguru: deleteguru}, function(data){
                    $("#container-guru").hide();
                });
            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-jenjang").hide();
                $("#feedback").hide();
        });
    });
</script>