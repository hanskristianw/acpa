<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
    
    if($_POST['check_kog_psi_id']){
        $kog_psi_id = $_POST['check_kog_psi_id'];
        
        $sql = "DELETE from kog_psi WHERE kog_psi_id IN (";
        
        for($i=0;$i<count($kog_psi_id);$i++){
            $sql .= $kog_psi_id[$i];
            
            if($i < count($kog_psi_id) - 1){
                $sql .= ",";
            }
        }
        
        $sql .= ")";
        
        echo "<br>";
        
        echo $sql;

    }
