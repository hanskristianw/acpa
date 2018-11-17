<?php
require_once("../includes/db_con.php");

$a = 0;
if(!empty($_POST["mapel_nama"])) {
    
    $a = mysqli_real_escape_string($conn, $_POST['mapel_nama']);
    
    $result = mysqli_query($conn, "SELECT count(*) FROM mapel, t_ajaran WHERE mapel_nama='" . $_POST["mapel_nama"] . "' AND mapel_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1");
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