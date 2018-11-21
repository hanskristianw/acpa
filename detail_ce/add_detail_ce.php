<?php

    if(isset($_POST['indikator_nama'])){
        include_once '../includes/db_con.php';
        $ce_id = mysqli_real_escape_string($conn, $_POST['option_tema_ce1']);
        $indikator_nama = mysqli_real_escape_string($conn, $_POST['indikator_nama']);
        //Error handlers
        //Cek apakah ada field yang kosong

        if(empty($indikator_nama)){
            //header("Location: guru.php?signup=empty");
            exit();
        }else{ 
            $sql_insert = "INSERT INTO d_ce(d_ce_ce_id, d_ce_nama) 
                           VALUES($ce_id, '$indikator_nama')";
            mysqli_query($conn, $sql_insert);
        }
    }
    else{
        echo 'youre not supposed to be here :D';
    }