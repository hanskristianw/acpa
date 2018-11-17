<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['guru_id'])){
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        $guru_name = mysqli_real_escape_string($conn, $_POST['guru_name']);
        $guru_username = mysqli_real_escape_string($conn, $_POST['guru_username']);
        $guru_password = mysqli_real_escape_string($conn, $_POST['guru_password']);
        $guru_password_baru = mysqli_real_escape_string($conn, $_POST['guru_password_baru']);
        $guru_password_baru2 = mysqli_real_escape_string($conn, $_POST['guru_password_baru2']);
        
        if($guru_password_baru != $guru_password_baru2){
            echo "Password baru tidak sama!";
        }
        else{
            $sql = "SELECT * FROM guru WHERE guru_id =$guru_id";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);
            if($resultCheck < 1){
              echo "guru tidak ada!";
            }else{
              if($row = mysqli_fetch_assoc($result)){
                //De-hashing the Password
                $hashedPwdCheck = password_verify($guru_password, $row['guru_password']);
                if($hashedPwdCheck ==  false){
                    echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Password lama salah!
                    </div>';
                }elseif($hashedPwdCheck ==  true){
                    $hashPwd = password_hash($guru_password_baru, PASSWORD_DEFAULT);
                    //update database guru
                    $query_updateguru = "UPDATE guru SET guru_name = '$guru_name', guru_password = '$hashPwd' WHERE guru_id = $guru_id";
                    $result_setguru = mysqli_query($conn, $query_updateguru);
                    
                    if(!$result_setguru){
                        die("QUERY FAILED".mysqli_error($conn));
                    }
                    else{
                        echo '<div class="alert alert-primary alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>PERHATIAN:</strong> Password berhasil dirubah!
                        </div>';
                    }
                }
              }
            }
        }
    }
    
?>
<script>
    
</script>