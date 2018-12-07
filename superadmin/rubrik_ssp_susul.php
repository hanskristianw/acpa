<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
?>

<?php

    if(isset($_POST['ssp_id'])){
        
        
        include ("../includes/db_con.php");
        
        $ssp_id = $_POST['ssp_id'];
        
        if($ssp_id > 0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu
            $ssp_id = $_POST['ssp_id'];

            $query2 =   "SELECT d_ssp_id, d_ssp_kriteria
                        FROM d_ssp
                        WHERE d_ssp_ssp_id = $ssp_id AND d_ssp_id IN 
                            (SELECT DISTINCT d_ssp_id 
                            FROM ssp_nilai 
                            LEFT JOIN d_ssp
                            ON ssp_nilai_d_ssp_id = d_ssp_id 
                            WHERE d_ssp_ssp_id = $ssp_id)";

            $query_info2 = mysqli_query($conn, $query2);
            
            while($row2 = mysqli_fetch_array($query_info2)){
                echo "<input type='hidden' name='d_ssp_id[]' value={$row2['d_ssp_id']}>";
                echo $row2['d_ssp_kriteria'];
                echo "<select class='form-control form-control-sm mb-2' name='option_nilai_ssp_susul[]' id='option_nilai_ssp_susul'>
                        <option value=4>A</option>
                        <option value=3>B</option>
                        <option value=2>C</option>
                        <option value=1>D</option>
                    </select>";
            }
            
            echo '<input type="submit" name="submit_kriteria" class="btn btn-primary" value="Input">';
        }
    }
?>

<script>
    $(document).ready(function(){
        
    });
</script>