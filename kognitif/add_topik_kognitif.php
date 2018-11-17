<?php

    if(isset($_POST['topik_nama_input'])){
        include_once '../includes/db_con.php';
        $topik_nama = mysqli_real_escape_string($conn, $_POST['topik_nama_input']);
        $topik_mapel_id = $_POST['option_mapel'];
        $topik_jenjang_id = $_POST['jenjang_id_option'];
        $topik_urutan = $_POST['topik_urutan'];
   
        $sql_insert = "INSERT INTO topik(topik_nama, topik_mapel_id, topik_jenjang_id, topik_urutan) VALUES('$topik_nama',$topik_mapel_id, $topik_jenjang_id, $topik_urutan)";
        mysqli_query($conn, $sql_insert);

        exit();
    }
    else{
        echo 'youre not supposed to be here :D';
    }