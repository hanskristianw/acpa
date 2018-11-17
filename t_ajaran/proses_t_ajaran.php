<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['t_ajaran_nama_option'])){
        
        $t_ajaran_nama = $_POST['t_ajaran_nama_option'];
        $t_ajaran_semester = $_POST['t_ajaran_semester_option'];
        $t_ajaran_tanggal_rapot = $_POST['tanggal_rapot'];
        $t_ajaran_kepsek_id_guru = $_POST['guru_id_option'];
        
        //$query = "UPDATE t_ajaran SET t_ajaran_active = IF (t_ajaran_id = $t_ajaran_id,1,0) WHERE t_ajaran_semester = $t_ajaran_semester";
        
        $query = "UPDATE t_ajaran
                SET t_ajaran_active = CASE
                    WHEN t_ajaran_nama = '$t_ajaran_nama' AND t_ajaran_semester = '$t_ajaran_semester' THEN 1
                    ELSE 0 END";
        
        $result_set = mysqli_query($conn, $query);
        if(!$result_set){
            die("QUERY FAILED".mysqli_error($conn));
        }
        
        $query2 = "UPDATE t_ajaran
                SET t_ajaran_kepsek_id_guru = $t_ajaran_kepsek_id_guru, t_ajaran_tanggal_input = $t_ajaran_tanggal_rapot
                WHERE t_ajaran_active = 1";
        
        $result_set2 = mysqli_query($conn, $query2);
        if(!$result_set2){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
?>