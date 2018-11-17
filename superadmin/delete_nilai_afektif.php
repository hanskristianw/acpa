<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
    
    if($_POST['check_afektif_id']){
        $afektif_id = $_POST['check_afektif_id'];
        
        $sql = "DELETE from afektif WHERE afektif_id IN (";
        
        for($i=0;$i<count($afektif_id);$i++){
            $sql .= $afektif_id[$i];
            
            if($i < count($afektif_id) - 1){
                $sql .= ",";
            }
        }
        
        $sql .= ")";
        
        echo "<br>";
        
        echo $sql;

    }
