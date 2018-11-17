<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['id'])){
        //siswa id yang terpilih
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        //kelas id yang terpilih
        $id2 = mysqli_real_escape_string($conn, $_POST['id2']);
        
        $query = "SELECT siswa_id, siswa_nama_depan, siswa_nama_belakang, siswa_no_induk, siswa_id_kelas
                  FROM siswa
                  WHERE siswa_id = {$id}";
        $query_siswa_info = mysqli_query($conn, $query);

        if(!$query_siswa_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_siswa_info);
        
        //container untuk menampilkan pesan sukses
        echo'<div id="feedback" class="alert alert-success">';
          echo'<p class="bg-success"></p>';
        echo'</div>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update Siswa</h4>";
        echo "<label>No Induk Siswa</label>";
        echo "<input type='text' class='form-control siswa_no_induk mb-3' value='".$row['siswa_no_induk']."'>";
        
        echo "<label>Nama Depan Siswa</label>";
        echo "<input type='text' rel='".$row['siswa_id']."' class='form-control siswa_nama_depan mb-3' value='".$row['siswa_nama_depan']."'>";
        
        echo "<label>Nama Belakang Siswa</label>";
        echo "<input type='text' class='form-control siswa_nama_belakang mb-3' value='".$row['siswa_nama_belakang']."'>";
        
        //menampilkan option sesuai dengan pilihan user sebelumnya
        $sql = "SELECT kelas_id, kelas_nama
                FROM kelas,t_ajaran 
                WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
        $result = mysqli_query($conn, $sql);
        $options = "";
        while ($row2 = mysqli_fetch_assoc($result)) {
            if($row2['kelas_id'] == $id2)
                $selected = "selected";
            else
                $selected = "";
            $options .= "<option ".$selected." value={$row2['kelas_id']}>{$row2['kelas_nama']}</option>";
        }
        echo "<label'>Pilih Kelas</label>";
        echo"<select class='form-control form-control mb-3 kelas_nama_option' name='kelas_nama_option'>";
        echo $options;
        echo"</select>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatesiswa'])){
        $siswa_id = mysqli_real_escape_string($conn, $_POST['siswa_id']);
        $siswa_no_induk = mysqli_real_escape_string($conn, $_POST['siswa_no_induk']);
        $siswa_nama_depan = mysqli_real_escape_string($conn, $_POST['siswa_nama_depan']);
        $siswa_nama_belakang = mysqli_real_escape_string($conn, $_POST['siswa_nama_belakang']);
        $siswa_id_kelas = mysqli_real_escape_string($conn, $_POST['siswa_id_kelas']);
        
        //update database siswa
        $query_updatesiswa = "UPDATE siswa SET siswa_no_induk = '$siswa_no_induk', siswa_nama_depan = '$siswa_nama_depan', siswa_nama_belakang = '$siswa_nama_belakang', siswa_id_kelas = $siswa_id_kelas WHERE siswa_id = $siswa_id";
        $result_setsiswa = mysqli_query($conn, $query_updatesiswa);
        
        if(!$result_setsiswa){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
    
    /************************Set guru menjadi tidak aktif************************/
//    if(isset($_POST['deleteguru'])){
//        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
//        
//        $query = "UPDATE guru SET guru_active = 0 WHERE guru_id = $guru_id";
//        
//        $result_set = mysqli_query($conn, $query);
//        
//        if(!$result_set){
//            die("QUERY FAILED".mysqli_error($conn));
//        }
//    }
?>
<script>
    $(document).ready(function(){
        var siswa_id;
        var siswa_no_induk;
        var siswa_nama_depan;
        var siswa_nama_belakang;
        var siswa_id_kelas;
        var updatesiswa = "update";
        var deletesiswa = "delete";
        
        $("#feedback").hide();
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            siswa_id =$(".siswa_nama_depan").attr("rel");
            siswa_no_induk = $(".siswa_no_induk").val();
            siswa_nama_depan = $(".siswa_nama_depan").val();
            siswa_nama_belakang = $(".siswa_nama_belakang").val();
            siswa_id_kelas = $('.kelas_nama_option').val();

            //mengirim nilai ke halaman php tujuan
            $.post("siswa/proses_siswa.php",{siswa_id: siswa_id, siswa_no_induk:siswa_no_induk, siswa_nama_depan:siswa_nama_depan, siswa_nama_belakang:siswa_nama_belakang, siswa_id_kelas:siswa_id_kelas, updatesiswa: updatesiswa}, function(data){
                $("#feedback").show();
                $("#feedback").text("Data berhasil diupdate");
                //alert(data);
            });
        });
        
        /************************DELETE BUTTON FUNCTION************************/
//        $(".delete").on('click', function(){
//            if(confirm('Are you sure you want to delete this')){
//                guru_id = $(".guru_name").attr('rel');
//                $.post("guru/proses_guru.php",{guru_id: guru_id, deleteguru: deleteguru}, function(data){
//                    $("#container-guru").hide();
//                });
//            }
//        });
//        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-siswa").hide();
        });
    });
</script>