<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['id'])){
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $id2 = mysqli_real_escape_string($conn, $_POST['id2']);
        
        //Tampilkan kolom dimana guru id = guru yang diklik
        //dan jabatan id pada kolom jabatan = id jabatan pada kolom guru
        //dan guru merupakan guru yang active
        $query = "SELECT guru_id, guru_name, guru_username, guru_password, jabatan_nama FROM guru, jabatan WHERE guru_id = {$id} AND jabatan_id = guru_jabatan AND guru_active = 1";
        $query_guru_info = mysqli_query($conn, $query);

        if(!$query_guru_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_guru_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete Guru</h4>";
        echo "<label>Nama Guru</label>";
        echo "<input type='text' rel='".$row['guru_id']."' class='form-control guru_name mb-3' value='".$row['guru_name']."'>";
        echo "<label>Username Guru</label>";
        echo "<input type='text' class='form-control guru_username mb-3' value='".$row['guru_username']."'>";
        echo "<label>New Password</label>";
        echo "<input type='text' class='form-control guru_password mb-3' value='default'>";
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT * FROM jabatan WHERE jabatan_active = 1";
        $result = mysqli_query($conn, $sql);
        $options = "<option value=0>Pilih Jabatan</option>";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['jabatan_id'] == $id2)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['jabatan_id']}>{$row2['jabatan_nama']}</option>";
        }
        echo "<label'>Jabatan Guru</label>";
        echo"<select class='form-control form-control mb-3 guru_jabatan_option' name='guru_jabatan_option'>";
        echo $options;
        echo"</select>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updateguru'])){
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        $guru_name = mysqli_real_escape_string($conn, $_POST['guru_name']);
        $guru_username = mysqli_real_escape_string($conn, $_POST['guru_username']);
        $guru_password = mysqli_real_escape_string($conn, $_POST['guru_password']);
        $guru_jabatan = mysqli_real_escape_string($conn, $_POST['guru_jabatan']);
        
        //hashing password
        $hashPwd = password_hash($guru_password, PASSWORD_DEFAULT);
        
        //update database guru
        $query_updateguru = "UPDATE guru SET guru_name = '$guru_name', guru_username = '$guru_username', guru_password = '$hashPwd', guru_jabatan = $guru_jabatan WHERE guru_id = $guru_id";
        $result_setguru = mysqli_query($conn, $query_updateguru);
        
        if(!$result_setguru){
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
        var guru_id;
        var guru_username;
        var guru_password;
        var guru_name;
        var guru_jabatan;
        var updateguru = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            guru_id =$(".guru_name").attr("rel");
            guru_name = $(".guru_name").val();
            guru_username = $(".guru_username").val();
            guru_password = $(".guru_password").val();
            guru_jabatan = $('.guru_jabatan_option').val();

            //mengirim nilai ke halaman php tujuan
            $.post("guru/proses_guru.php",{guru_id: guru_id, guru_name:guru_name, guru_username:guru_username, guru_password:guru_password, guru_jabatan:guru_jabatan, updateguru: updateguru}, function(data){
                $("#feedback").text("Data berhasil diupdate");
                //alert(data);
            });
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
                $("#container-guru").hide();
        });
    });
</script>