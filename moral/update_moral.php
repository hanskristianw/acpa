<?php
    if(isset($_POST['moral_b_id'])){
        include_once '../includes/db_con.php';
        $moral_b_id = $_POST['moral_b_id'];
        
        $moral_b_lo = $_POST['option_lo'];
        $moral_b_so = $_POST['option_so'];
        
        $sql_update_persen = "UPDATE moral_b SET moral_b_lo = CASE moral_b_id ";

        for ($i = 0; $i < count($moral_b_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$moral_b_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$moral_b_lo[$i] ";
            if($i == count($moral_b_id)-1)
            {$sql_update_persen .= "ELSE moral_b_lo END,";}
        }

        $sql_update_persen .= " moral_b_so = CASE moral_b_id ";

        for ($i = 0; $i < count($moral_b_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$moral_b_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$moral_b_so[$i] ";
            if($i == count($moral_b_id)-1)
            {$sql_update_persen .= "ELSE moral_b_so END";}
        }

        $sql_update_persen .= " WHERE moral_b_id in (";

        for ($i = 0; $i < count($moral_b_id); $i++)
        {
            $sql_update_persen .= $moral_b_id[$i];
            if($i != count($moral_b_id)-1)
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