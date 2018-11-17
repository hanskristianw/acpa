<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['id'])){
        //id kelas
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        //id guru untuk set dropdown
        $id2 = mysqli_real_escape_string($conn, $_POST['id2']);
        $id3 = mysqli_real_escape_string($conn, $_POST['id3']);
        
        //Tampilkan kolom dimana guru id = guru yang diklik
        //dan jabatan id pada kolom jabatan = id jabatan pada kolom guru
        //dan guru merupakan guru yang active
        $query = "SELECT kelas_id, kelas_nama, kelas_jenjang_id, guru_id FROM guru, kelas WHERE kelas_id = {$id} AND kelas_wali_guru_id = guru_id";
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
        echo "<h4 class='mb-3'>Update/Delete Guru</h4>";
        echo "<label>Nama Kelas</label>";
        echo "<input type='text' rel='".$row['kelas_id']."' class='form-control kelas_nama mb-3' value='".$row['kelas_nama']."'>";
        
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT * FROM jenjang";
        $result = mysqli_query($conn, $sql);
        $options2 = "";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['jenjang_id'] == $id3)
                $selected = "selected";
            else
                $selected = "";
            $options2 .= "<option ".$selected." value={$row2['jenjang_id']}>{$row2['jenjang_nama']}</option>";
        }
        echo "<label>Jenjang</label>";
        echo"<select class='form-control form-control mb-3 jenjang_id_option' name='jenjang_id_option'>";
        echo $options2;
        echo"</select>";
        
        
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT * FROM guru WHERE guru_jabatan = 2";
        $result = mysqli_query($conn, $sql);
        $options = "";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['guru_id'] == $id2)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['guru_id']}>{$row2['guru_name']}</option>";
        }
        
        
        echo "<label'>Wali Kelas</label>";
        echo"<select class='form-control form-control mb-3 guru_id_option' name='guru_id_option'>";
        echo $options;
        echo"</select>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatekelas'])){
        $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);
        $kelas_nama = mysqli_real_escape_string($conn, $_POST['kelas_nama']);
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        $jenjang_id = mysqli_real_escape_string($conn, $_POST['jenjang_id']);
        
        //update database guru
        $query_updatekelas = "UPDATE kelas SET kelas_nama = '$kelas_nama', kelas_wali_guru_id = '$guru_id', kelas_jenjang_id = '$jenjang_id' WHERE kelas_id = $kelas_id";
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
        var kelas_id;
        var kelas_nama;
        var guru_id;
        var jenjang_id;
        var updatekelas = "update";
        var deletekelas = "delete";
        
        $("#feedback").hide();
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            kelas_id =$(".kelas_nama").attr("rel");
            kelas_nama = $(".kelas_nama").val();
            guru_id = $('.guru_id_option').val();
            jenjang_id = $('.jenjang_id_option').val();

            if( !$(".kelas_nama").val() ) {
                $("#feedback").text("Nama tidak boleh kosong");
            }
            else{
                //mengirim nilai ke halaman php tujuan
                $.post("kelas/proses_kelas.php",{kelas_id: kelas_id, kelas_nama:kelas_nama, guru_id:guru_id, jenjang_id:jenjang_id, updatekelas:updatekelas}, function(data){
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
                $("#container-kelas").hide();
                $("#feedback").hide();
        });
    });
</script>