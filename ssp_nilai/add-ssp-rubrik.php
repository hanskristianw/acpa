<?php
    if(isset($_POST['ssp_id'])){
        include_once '../includes/db_con.php';
        
        $d_ssp_ssp_id = mysqli_real_escape_string($conn, $_POST['ssp_id']);
        $d_ssp_kriteria = mysqli_real_escape_string($conn, $_POST['d_ssp_kriteria']);
        $d_ssp_a = mysqli_real_escape_string($conn, $_POST['d_ssp_a']);
        $d_ssp_b = mysqli_real_escape_string($conn, $_POST['d_ssp_b']);
        $d_ssp_c = mysqli_real_escape_string($conn, $_POST['d_ssp_c']);
   
        $sql_insert = "INSERT INTO d_ssp(d_ssp_ssp_id, d_ssp_kriteria, d_ssp_a, d_ssp_b, d_ssp_c) VALUES($d_ssp_ssp_id,'$d_ssp_kriteria','$d_ssp_a','$d_ssp_b','$d_ssp_c')";
        mysqli_query($conn, $sql_insert);

        exit();
    }
    else{
        echo 'youre not supposed to be here :D';
    }