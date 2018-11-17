<?php
require_once("../includes/db_con.php");

        $sql = "SELECT mapel_id, mapel_nama FROM mapel, t_ajaran WHERE mapel_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
    
        $result2 = mysqli_query($conn, $sql);
        $options = "";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $options .= "<option value={$row2['mapel_id']}>{$row2['mapel_nama']}</option>";
        }
        echo $options;
    
?>