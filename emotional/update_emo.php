<?php
    if(isset($_POST['emo_aware_id'])){
        include_once '../includes/db_con.php';
        $emo_aware_id = $_POST['emo_aware_id'];
        
        $emo_aware_ex = $_POST['option_ex'];
        $emo_aware_so = $_POST['option_so'];
        $emo_aware_ne = $_POST['option_ne'];
        
        $sql_update_persen = "UPDATE emo_aware SET emo_aware_ex = CASE emo_aware_id ";

        for ($i = 0; $i < count($emo_aware_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$emo_aware_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$emo_aware_ex[$i] ";
            if($i == count($emo_aware_id)-1)
            {$sql_update_persen .= "ELSE emo_aware_ex END,";}
        }

        $sql_update_persen .= " emo_aware_so = CASE emo_aware_id ";

        for ($i = 0; $i < count($emo_aware_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$emo_aware_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$emo_aware_so[$i] ";
            if($i == count($emo_aware_id)-1)
            {$sql_update_persen .= "ELSE emo_aware_so END,";}
        }

        $sql_update_persen .= " emo_aware_ne = CASE emo_aware_id ";

        for ($i = 0; $i < count($emo_aware_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$emo_aware_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$emo_aware_ne[$i] ";
            if($i == count($emo_aware_id)-1)
            {$sql_update_persen .= "ELSE emo_aware_ne END";}
        }

        $sql_update_persen .= " WHERE emo_aware_id in (";

        for ($i = 0; $i < count($emo_aware_id); $i++)
        {
            $sql_update_persen .= $emo_aware_id[$i];
            if($i != count($emo_aware_id)-1)
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