<?php

    include_once '../includes/db_con.php';
    
    if(isset($_POST['option_mapel'])){
        
        $mapel_id = $_POST['option_mapel'];
        $mapel_persen_for = $_POST['persen_for']/100;
        $mapel_persen_sum = $_POST['persen_sum']/100;
        $mapel_persen_for_psi = $_POST['persen_for_psi']/100;
        $mapel_persen_sum_psi = $_POST['persen_sum_psi']/100;
        $mapel_persen_kog = $_POST['persen_kog']/100;
        $mapel_persen_psi = $_POST['persen_psi']/100;
        
        $query2 =   "UPDATE mapel
                    SET mapel_persen_for = $mapel_persen_for, 
                        mapel_persen_sum = $mapel_persen_sum,
                        mapel_persen_for_psi = $mapel_persen_for_psi, 
                        mapel_persen_sum_psi = $mapel_persen_sum_psi,
                        mapel_persen_kog = $mapel_persen_kog, 
                        mapel_persen_psi = $mapel_persen_psi
                    WHERE mapel_id = $mapel_id";
        
        $result_set2 = mysqli_query($conn, $query2);
        if(!$result_set2){
            die("QUERY FAILED".mysqli_error($conn));
        }else{
            echo "Persentase berhasil diupdate";
        }
    }
?>