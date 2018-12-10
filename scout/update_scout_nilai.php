<?php
    if(isset($_POST['scout_nilai_id'])){
        include_once '../includes/db_con.php';
        $scout_nilai_id = $_POST['scout_nilai_id'];
        $scout_nilai_angka = $_POST['option_scout_nilai'];
        
        $sql_update_persen = "UPDATE scout_nilai SET scout_nilai_angka = CASE scout_nilai_id ";

        for ($i = 0; $i < count($scout_nilai_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$scout_nilai_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$scout_nilai_angka[$i] ";
            if($i == count($scout_nilai_id)-1)
            {$sql_update_persen .= "ELSE scout_nilai_angka END";}
        }
        
        $sql_update_persen .= " WHERE scout_nilai_id in (";

        for ($i = 0; $i < count($scout_nilai_id); $i++)
        {
            $sql_update_persen .= $scout_nilai_id[$i];
            if($i != count($scout_nilai_id)-1)
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