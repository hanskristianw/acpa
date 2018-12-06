<?php

    include_once '../includes/db_con.php';
    
    if(isset($_POST['option_mapel'])){
        
        $mapel_id = $_POST['option_mapel'];
        $mapel_persen_for = $_POST['persen_for']/100;
        $mapel_persen_sum = $_POST['persen_sum']/100;
        
        $query2 =   "UPDATE mapel
                    SET mapel_persen_for = $mapel_persen_for, 
                        mapel_persen_sum = $mapel_persen_sum
                    WHERE mapel_id = $mapel_id";
        
        $result_set2 = mysqli_query($conn, $query2);
        if(!$result_set2){
            die("QUERY FAILED".mysqli_error($conn));
        }else{
            echo "Persentase berhasil diupdate";
        }
    }
?>