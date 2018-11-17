<?php

    if(isset($_POST['jenjang_nama_input'])){
        include_once '../includes/db_con.php';
        $jenjang_nama = mysqli_real_escape_string($conn, $_POST['jenjang_nama_input']);

        $sql_insert = "INSERT INTO jenjang(jenjang_nama) VALUES('$jenjang_nama')";
        mysqli_query($conn, $sql_insert);
    }