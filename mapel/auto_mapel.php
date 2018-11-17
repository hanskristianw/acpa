<?php

    include_once '../includes/db_con.php';
    $request = mysqli_real_escape_string($conn, $_POST["query"]);
    $query = "SELECT * FROM mapel, t_ajaran WHERE mapel_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1 AND mapel_nama LIKE '%".$request."%'";
    
    $result = mysqli_query($conn, $query);
    
    $data = array();
    
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row["mapel_nama"];
        }
        echo (json_encode($data));
    }
    exit();
?>