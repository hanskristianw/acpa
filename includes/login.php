<?php

session_start();

  if( isset($_POST['submit'])){
      include 'db_con.php';

      $uid = mysqli_real_escape_string($conn, $_POST['uid']);
      $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

      //Error handlers
      //Cek apakah input kosong
      if(empty($uid) || empty($pwd)){
        header("Location: ../index.php?login=empty");
        exit();
      }else{
        $sql = "SELECT * FROM guru, jabatan WHERE guru_username = '$uid' AND guru_jabatan = jabatan_id";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck < 1){
          header("Location: ../index.php?login=error");
          exit();
        }else{
          if($row = mysqli_fetch_assoc($result)){
            //De-hashing the Password
            $hashedPwdCheck = password_verify($pwd, $row['guru_password']);
            if($hashedPwdCheck ==  false){
              header("Location: ../index.php?login=error");
              exit();
            }elseif($hashedPwdCheck ==  true){
              //log in the user HERE
              $_SESSION['guru_id'] = $row['guru_id'];
              $_SESSION['guru_name'] = $row['guru_name'];
              $_SESSION['guru_jabatan'] = $row['guru_jabatan'];
              $_SESSION['jabatan_nama'] = $row['jabatan_nama'];
              header("Location: ../index.php?login=success");
              exit();
            }
          }
        }
      }
  } else {
    header("Location: ../index.php?login=error");
    exit();
  }

?>
