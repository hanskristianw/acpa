<?php
    if(isset($_POST['kelas_id'])){
        $kelas_id = $_POST['kelas_id'];

        include '../includes/db_con.php';
        $sql2 = "SELECT * FROM siswa WHERE siswa_id_kelas = $kelas_id";
        $result2 = mysqli_query($conn, $sql2);
        
        $options3 = "<option value= 0>Pilih Siswa</option>";
        while ($row = mysqli_fetch_assoc($result2)) {
            $options3 .= "<option value={$row['siswa_id']}>{$row['siswa_nama_depan']} {$row['siswa_nama_belakang']}</option>";
        }

       echo '<select class="form-control form-control-sm mb-2 siswa_id_option" name="siswa_id_option" id="siswa_id_option">';
       echo $options3;
       echo '</select>';
    }
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#siswa_id_option").change(function () {
            var siswa_id = $("#siswa_id_option").val();
            $.ajax({
                url: 'superadmin/update_option_ssp.php',
                data:'siswa_id='+ siswa_id,
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_ssp_siswa").html(show);
                    }
                }
            });

        });

    });
</script>