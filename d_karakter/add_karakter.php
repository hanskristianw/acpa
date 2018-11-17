<?php

    if(isset($_POST['option_mapel'])){
        include_once '../includes/db_con.php';
        $mapel_id = mysqli_real_escape_string($conn, $_POST['option_mapel']);
        $karakter_id = mysqli_real_escape_string($conn, $_POST['option_karakter']);

        //insert the user into the database
        $sql_insert = "INSERT INTO d_karakter(d_karakter_mapel_id, d_karakter_k_id)
                       VALUES($mapel_id,$karakter_id)";
        
        if(mysqli_query($conn, $sql_insert)){
            echo '<div class="alert alert-success">
                    <strong>Berhasil Ditambahkan</strong> .
                  </div>';
        }else{
            echo '<div class="alert alert-success">
                    <strong>Gagal Ditambahkan</strong> .
                  </div>';
        }
        
        exit();
        
    }