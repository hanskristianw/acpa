<?php
    if($_POST['t_ajaran_id']){
        $t_ajaran_id = $_POST['t_ajaran_id'];

        include '../includes/db_con.php';
        $sql2 = "SELECT kelas_id, kelas_nama 
                FROM kelas 
                LEFT JOIN t_ajaran 
                ON kelas_t_ajaran_id = t_ajaran_id 
                WHERE t_ajaran_id = $t_ajaran_id";

        $result2 = mysqli_query($conn, $sql2);

        $options2 = "<option value= 0>Pilih Kelas</option>";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $options2 .= "<option value={$row2['kelas_id']}>{$row2['kelas_nama']}</option>";
        }
        echo '<select class="form-control form-control-sm mb-2" name="option_kelas" id="option_kelas">';
        echo $options2;
        echo '</select>';
    }

?>