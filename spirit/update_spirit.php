<?php
    if(isset($_POST['spirit_id'])){
        include_once '../includes/db_con.php';
        $spirit_id = $_POST['spirit_id'];
        
        $spirit_coping = $_POST['option_coping'];
        $spirit_emo = $_POST['option_emo'];
        $spirit_grate = $_POST['option_grate'];
        $spirit_ref = $_POST['option_ref'];
        
        $sql_update_persen = "UPDATE spirit SET spirit_coping = CASE spirit_id ";

        for ($i = 0; $i < count($spirit_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$spirit_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$spirit_coping[$i] ";
            if($i == count($spirit_id)-1)
            {$sql_update_persen .= "ELSE spirit_coping END,";}
        }

        $sql_update_persen .= " spirit_emo = CASE spirit_id ";

        for ($i = 0; $i < count($spirit_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$spirit_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$spirit_emo[$i] ";
            if($i == count($spirit_id)-1)
            {$sql_update_persen .= "ELSE spirit_emo END,";}
        }

        $sql_update_persen .= " spirit_grate = CASE spirit_id ";

        for ($i = 0; $i < count($spirit_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$spirit_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$spirit_grate[$i] ";
            if($i == count($spirit_id)-1)
            {$sql_update_persen .= "ELSE spirit_grate END,";}
        }

        $sql_update_persen .= " spirit_ref = CASE spirit_id ";

        for ($i = 0; $i < count($spirit_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$spirit_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$spirit_ref[$i] ";
            if($i == count($spirit_id)-1)
            {$sql_update_persen .= "ELSE spirit_ref END";}
        }
        
        $sql_update_persen .= " WHERE spirit_id in (";

        for ($i = 0; $i < count($spirit_id); $i++)
        {
            $sql_update_persen .= $spirit_id[$i];
            if($i != count($spirit_id)-1)
            {$sql_update_persen .= ",";}
        }

        $sql_update_persen .= ")";
    }
    
    if (!mysqli_query($conn, $sql_update_persen))
    {
        echo("<br> Error description: " . mysqli_error($conn));
    }
    else{
        echo '<div class="alert alert-success alert-dismissible fade show">
                <button class="close" data-dismiss="alert" type="button">
                    <span>&times;</span>
                </button>
                <strong>Data berhasil diupdate</strong>
            </div>';
    }