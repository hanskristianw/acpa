<?php

    include_once '../includes/db_con.php';
    
    if(isset($_POST['guru_id_option'])){
        
        //$t_ajaran_tanggal_rapot = $_POST['tanggal_rapot'];
        $t_ajaran_kepsek_id_guru = $_POST['guru_id_option'];
        
        $rawdate = htmlentities($_POST['tanggal_rapot']);
        $date = date('Y-m-d', strtotime($rawdate));
        
        $query2 = "UPDATE t_ajaran
                SET t_ajaran_kepsek_id_guru = $t_ajaran_kepsek_id_guru, t_ajaran_tanggal_rapot_sisipan = '$date'
                WHERE t_ajaran_active = 1";
        
        $result_set2 = mysqli_query($conn, $query2);
        if(!$result_set2){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
?>