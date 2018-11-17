<?php
require_once("../includes/db_con.php");

$a = 0;
if(!empty($_POST["guru_username"])) {
    
    $a = mysqli_real_escape_string($conn, $_POST['guru_username']);
    
    $result = mysqli_query($conn, "SELECT count(*) FROM guru WHERE guru_username='" . $_POST["guru_username"] . "'");
    $row = mysqli_fetch_row($result);
    $user_count = $row[0];
    if($user_count>0) {
      $response = array(
        'status' => '1'
      );
    }else{
      $response = array(
        'status' => '0'
      );
    }
    
    echo(json_encode($response));
    exit();
}
?>