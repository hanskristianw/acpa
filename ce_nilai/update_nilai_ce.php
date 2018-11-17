<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php
    if(isset($_POST['ce_nilai_id'])){
        include_once '../includes/db_con.php';
        $ce_nilai_id = $_POST['ce_nilai_id'];
        $ce_nilai_angka = $_POST['option_nilai'];
        
        $sql_update_persen = "UPDATE ce_nilai SET ce_nilai_angka = CASE ce_nilai_id ";

        for ($i = 0; $i < count($ce_nilai_id); $i++)
        {
            $sql_update_persen .= "WHEN ";
            $sql_update_persen .= "$ce_nilai_id[$i]";
            $sql_update_persen .= " THEN ";
            $sql_update_persen .= "$ce_nilai_angka[$i] ";
            if($i == count($ce_nilai_id)-1)
            {$sql_update_persen .= "ELSE ce_nilai_angka END";}
        }
        $sql_update_persen .= " WHERE ce_nilai_id in (";

        for ($i = 0; $i < count($ce_nilai_id); $i++)
        {
            $sql_update_persen .= $ce_nilai_id[$i];
            if($i != count($ce_nilai_id)-1)
            {$sql_update_persen .= ",";}
        }

        $sql_update_persen .= ")";
    }
    
    //echo $sql_update_persen;
    
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
