<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['id'])){
        //id d_mapel_id_mapel
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        //id d_mapel_id_kelas untuk set dropdown
        $id2 = mysqli_real_escape_string($conn, $_POST['id2']);
        //id d_mapel_id_guru untuk set dropdown
        $id3 = mysqli_real_escape_string($conn, $_POST['id3']);
        
        //cari nama mapel dan id mapel
        $query = "SELECT mapel_id, mapel_nama, mapel_nama_singkatan FROM mapel WHERE mapel_id = {$id}";
        $query_guru_info = mysqli_query($conn, $query);

        if(!$query_guru_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_guru_info);
        
        //container untuk menampilkan pesan sukses
        echo'<div id="feedback" class="alert alert-success">';
          echo'<p class="bg-success"></p>';
        echo'</div>';
        
        //menampilkan textbox ketika nama kelas diklik
        echo "<h4 class='mb-3'>Update Mapel</h4>";
        echo "<label>Nama Pelajaran</label>";
        echo "<input type='text' rel='".$row['mapel_id']."' rel2='".$id2."' class='form-control mapel_nama mb-3' value='".$row['mapel_nama']."'>";
        
        
        echo "<label>Singkatan Nama Pelajaran</label>";
        echo "<input type='text' class='form-control singkatan_mapel_nama mb-3' value='".$row['mapel_nama_singkatan']."'>";
        
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT guru_id, guru_name FROM guru WHERE guru_active = 1";
        $result = mysqli_query($conn, $sql);
        $options = "";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['guru_id'] == $id3)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['guru_id']}>{$row2['guru_name']}</option>";
        }
        echo "<label'>Guru Pengajar</label>";
        echo"<select class='form-control form-control mb-3 guru_id_option' name='guru_id_option'>";
        echo $options;
        echo"</select>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatemapel'])){
        $d_mapel_id_mapel = mysqli_real_escape_string($conn, $_POST['d_mapel_id_mapel']);
        $d_mapel_id_kelas = mysqli_real_escape_string($conn, $_POST['d_mapel_id_kelas']);
        $d_mapel_id_guru = mysqli_real_escape_string($conn, $_POST['d_mapel_id_guru']);
        
        $mapel_nama = mysqli_real_escape_string($conn, $_POST['mapel_nama']);
        $mapel_nama_singkatan = mysqli_real_escape_string($conn, $_POST['mapel_nama_singkatan']);
        
        //update detail mapel
        $query_update_detail_mapel = "UPDATE d_mapel SET d_mapel_id_guru = '$d_mapel_id_guru' WHERE d_mapel_id_mapel = $d_mapel_id_mapel AND d_mapel_id_kelas = $d_mapel_id_kelas";
        $result_setdmapel = mysqli_query($conn, $query_update_detail_mapel);
        
        //update mapel
        $query_update_mapel = "UPDATE mapel SET mapel_nama = '$mapel_nama', mapel_nama_singkatan = '$mapel_nama_singkatan' WHERE mapel_id = $d_mapel_id_mapel";
        $result_mapel = mysqli_query($conn, $query_update_mapel);
        
        if(!$result_setdmapel){
            die("QUERY FAILED".mysqli_error($conn));
        }
        
        if(!$result_mapel){
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
        var d_mapel_id_mapel;
        var d_mapel_id_kelas;
        var d_mapel_id_guru;
        var mapel_nama;
        var mapel_nama_singkatan;
        var guru_id;
        var updatemapel = "update";
        var deletekelas = "delete";
        
        $("#feedback").hide();
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            d_mapel_id_mapel =$(".mapel_nama").attr("rel");
            d_mapel_id_kelas =$(".mapel_nama").attr("rel2");
            d_mapel_id_guru = $('.guru_id_option').val();

            mapel_nama = $('.mapel_nama').val();
            mapel_nama_singkatan = $('.singkatan_mapel_nama').val();

            if( !$(".mapel_nama").val() ) {
                $("#feedback").text("Nama tidak boleh kosong");
            }
            else{
                //mengirim nilai ke halaman php tujuan
                $.post("mapel/proses_mapel.php",{d_mapel_id_mapel: d_mapel_id_mapel, mapel_nama:mapel_nama, mapel_nama_singkatan:mapel_nama_singkatan, d_mapel_id_guru:d_mapel_id_guru, d_mapel_id_kelas:d_mapel_id_kelas, updatemapel:updatemapel}, function(data){
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
                $("#container-mapel").hide();
                $("#feedback").hide();
        });
    });
</script>