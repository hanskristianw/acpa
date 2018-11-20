<?php
    if(isset($_POST['kelas_id'])){
        include '../includes/db_con.php';
        
        $kelas_id = $_POST['kelas_id'];
        
        $sql3 = "SELECT siswa_id, siswa_nama_depan, siswa_nama_belakang from siswa,kelas
                 where siswa_id_kelas=kelas_id AND kelas_id = {$kelas_id}";
        $result3 = mysqli_query($conn, $sql3);
        
        echo '<input type="checkbox" id="checkAll" class="checkAll"> <b>PILIH SEMUA</b><hr/>';
        while ($row3 = mysqli_fetch_assoc($result3)) {
            echo"<input type='checkbox' name='check_siswa_id[]' value={$row3['siswa_id']}> {$row3['siswa_nama_depan']} {$row3['siswa_nama_belakang']} <br>";
        }
    }
?>

<script>
    $(".checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>