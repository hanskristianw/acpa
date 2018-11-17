<?php
    if(isset($_POST['pf_hf_id'])){
        include_once '../includes/db_con.php';
        $pf_hf_id = $_POST['pf_hf_id'];
        
        $pf_hf_absent = $_POST['option_absent'];
        $pf_hf_uks = $_POST['option_uks'];
        $pf_hf_tardiness = $_POST['option_tardiness'];
        
        $sql_update_persen = "UPDATE pf_hf SET pf_hf_absent = CASE pf_hf_id ";

        for ($i = 0; $i < count($pf_hf_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$pf_hf_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$pf_hf_absent[$i] ";
            if($i == count($pf_hf_id)-1)
            {$sql_update_persen .= "ELSE pf_hf_absent END,";}
        }

        $sql_update_persen .= " pf_hf_uks = CASE pf_hf_id ";

        for ($i = 0; $i < count($pf_hf_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$pf_hf_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$pf_hf_uks[$i] ";
            if($i == count($pf_hf_id)-1)
            {$sql_update_persen .= "ELSE pf_hf_uks END,";}
        }

        $sql_update_persen .= " pf_hf_tardiness = CASE pf_hf_id ";

        for ($i = 0; $i < count($pf_hf_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$pf_hf_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$pf_hf_tardiness[$i] ";
            if($i == count($pf_hf_id)-1)
            {$sql_update_persen .= "ELSE pf_hf_tardiness END";}
        }

        $sql_update_persen .= " WHERE pf_hf_id in (";

        for ($i = 0; $i < count($pf_hf_id); $i++)
        {
            $sql_update_persen .= $pf_hf_id[$i];
            if($i != count($pf_hf_id)-1)
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