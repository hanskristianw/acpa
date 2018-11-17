<?php
    if(isset($_POST['ss_id'])){
        include_once '../includes/db_con.php';
        $ss_id = $_POST['ss_id'];
        
        $ss_relationship = $_POST['option_relationship'];
        $ss_cooperation = $_POST['option_cooperation'];
        $ss_conflict = $_POST['option_conflict'];
        $ss_self_a = $_POST['option_self_appraisal'];
        
        $sql_update_persen = "UPDATE ss SET ss_relationship = CASE ss_id ";

        for ($i = 0; $i < count($ss_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$ss_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$ss_relationship[$i] ";
            if($i == count($ss_id)-1)
            {$sql_update_persen .= "ELSE ss_relationship END,";}
        }

        $sql_update_persen .= " ss_cooperation = CASE ss_id ";

        for ($i = 0; $i < count($ss_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$ss_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$ss_cooperation[$i] ";
            if($i == count($ss_id)-1)
            {$sql_update_persen .= "ELSE ss_cooperation END,";}
        }

        $sql_update_persen .= " ss_conflict = CASE ss_id ";

        for ($i = 0; $i < count($ss_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$ss_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$ss_conflict[$i] ";
            if($i == count($ss_id)-1)
            {$sql_update_persen .= "ELSE ss_conflict END,";}
        }

        $sql_update_persen .= " ss_self_a = CASE ss_id ";

        for ($i = 0; $i < count($ss_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$ss_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$ss_self_a[$i] ";
            if($i == count($ss_id)-1)
            {$sql_update_persen .= "ELSE ss_self_a END";}
        }
        
        $sql_update_persen .= " WHERE ss_id in (";

        for ($i = 0; $i < count($ss_id); $i++)
        {
            $sql_update_persen .= $ss_id[$i];
            if($i != count($ss_id)-1)
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