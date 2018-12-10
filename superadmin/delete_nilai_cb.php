<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
    
    if($_POST['check_ce_id']){
        $ce_id = $_POST['check_ce_id'];
        
        $sql = "DELETE from ce_nilai WHERE ce_nilai_id IN (";
        
        for($i=0;$i<count($ce_id);$i++){
            $sql .= $ce_id[$i];
            
            if($i < count($ce_id) - 1){
                $sql .= ",";
            }
        }
        
        $sql .= ")";
        
        echo "<br>";
        
        echo $sql;

    }
