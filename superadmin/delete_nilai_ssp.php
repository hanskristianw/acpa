<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
    
    if($_POST['check_ssp_id']){
        $ssp_nilai_id = $_POST['check_ssp_id'];
        
        $sql = "DELETE from ssp_nilai WHERE ssp_nilai_id IN (";
        
        for($i=0;$i<count($ssp_nilai_id);$i++){
            $sql .= $ssp_nilai_id[$i];
            
            if($i < count($ssp_nilai_id) - 1){
                $sql .= ",";
            }
        }
        
        $sql .= ")";
        
        echo "<br>";
        
        echo $sql;

    }
