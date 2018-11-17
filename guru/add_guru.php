<?php

    if(isset($_POST['guru_nama_input'])){
    include_once '../includes/db_con.php';
    $guru_name = mysqli_real_escape_string($conn, $_POST['guru_nama_input']);
    $guru_username = mysqli_real_escape_string($conn, $_POST['guru_username_input']);
    $guru_password = mysqli_real_escape_string($conn, $_POST['guru_password_input']);
    $temp_guru_option = $_POST['guru_jabatan_option'];
   
    //Error handlers
    //Cek apakah ada field yang kosong
    
    if(empty($guru_name) || empty($guru_name)|| empty($guru_username)|| empty($guru_password)|| $temp_guru_option==0){
        //header("Location: guru.php?signup=empty");
        exit();
    }else{ 
        //cek apakah username sudah ada
        $sql_cek = "SELECT * FROM guru WHERE guru_username='$guru_username'";
        $result = mysqli_query($conn, $sql_cek);
        $resultcheck = mysqli_num_rows($result);

        if($resultcheck>0){
            //header("Location: guru.php?signup=usertaken");
            exit();
        }else{
            //Hashing the password
            $hashPwd = password_hash($guru_password, PASSWORD_DEFAULT);
            //insert the user into the database
            $sql_insert = "INSERT INTO guru(guru_name, guru_username, guru_password, guru_jabatan, guru_active) VALUES('$guru_name','$guru_username','$hashPwd','$temp_guru_option','1')";
            mysqli_query($conn, $sql_insert);
            
            //header("Location: guru.php?signup=success");
            exit();
        }
    }
    }
    else{
        echo 'youre not supposed to be here :D';
    }