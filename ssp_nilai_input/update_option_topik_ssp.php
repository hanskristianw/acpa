<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");
   
    if(!empty($_POST["ssp_id"])) {

        $ssp_id = $_POST["ssp_id"];
        
        $query =    "SELECT *
                    FROM d_ssp
                    WHERE d_ssp_ssp_id = $ssp_id";

        $query_info = mysqli_query($conn, $query);
        
        $options = "<option value= 0>Pilih Kriteria/Rubrik</option>";
         while($row = mysqli_fetch_array($query_info)){
            $options .= "<option value={$row['d_ssp_id']}>{$row['d_ssp_kriteria']}</option>";
         }
         
        echo"<select class='form-control form-control-sm mb-2 option_rubrik' name='option_rubrik' id='option_rubrik'>";
            echo $options;
        echo"</select>";
         
    }
    
?>
