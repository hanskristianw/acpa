<?php
    if($_POST['kelas_id']){
        $kelas_id = $_POST['kelas_id'];

        //echo $kelas_id;
        include '../includes/db_con.php';
        $sql2 = "SELECT GROUP_CONCAT(siswa_id ORDER BY siswa_id) as siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang
                FROM siswa
                WHERE siswa_id_kelas IN ($kelas_id)
                GROUP BY siswa_no_induk
                ORDER BY siswa_nama_depan, siswa_no_induk";
        $result2 = mysqli_query($conn, $sql2);

        $options2 = "<option value= 0>Pilih Siswa</option>";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $options2 .= "<option value={$row2['siswa_id']}>{$row2['siswa_no_induk']} - {$row2['siswa_nama_depan']} {$row2['siswa_nama_belakang']}</option>";
        }
        echo '<select class="form-control form-control-sm mb-2" name="option_siswa" id="option_siswa">';
        echo $options2;
        echo '</select>';
    }

?>