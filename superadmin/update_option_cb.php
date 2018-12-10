<?php
    if(isset($_POST['kelas_id'])){
        $kelas_id = $_POST['kelas_id'];

        include '../includes/db_con.php';
        $sql2 = "SELECT ssp_id, ssp_nama FROM ssp_daftar
                LEFT JOIN ssp
                ON ssp_daftar_ssp_id = ssp_id 
                WHERE ssp_daftar_siswa_id = $siswa_id";
        $result2 = mysqli_query($conn, $sql2);
        
        $options3 = "<option value= 0>Pilih SSP asal</option>";
        while ($row = mysqli_fetch_assoc($result2)) {
            $options3 .= "<option value={$row['ssp_id']}>{$row['ssp_nama']}</option>";
        }
        echo '<select class="form-control form-control-sm mb-2" name="ssp_asal_option" id="ssp_asal_option">';
        echo $options3;
        echo'</select>';
    }
?>