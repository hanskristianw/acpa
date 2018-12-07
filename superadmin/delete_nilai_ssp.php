<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
    
    include_once '../includes/db_con.php';
    include_once '../includes/fungsi_lib.php';
    
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
        
        //echo "<br>";
        
        //echo $sql;

        if (!mysqli_query($conn, $sql))
        {
            echo return_alert(mysqli_error($conn),"danger");
        }
        else{
            echo return_alert("Data berhasil dihapus!","success");
        }
        mysqli_close($conn);

    }
