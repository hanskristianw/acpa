<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php
    
    if(!empty($_POST["option_nilai"])) {
        include ("../includes/db_con.php");
        
        $d_ssp_ssp_id = $_POST["d_ssp_ssp_id"];
        $siswa_id = $_POST["siswa_id"];
        $nilai_ssp = $_POST["option_nilai"];
        
        $sql = "INSERT INTO ssp_nilai(ssp_nilai_siswa_id, ssp_nilai_d_ssp_id, ssp_nilai_angka) VALUES ";
        
        for ($i = 0; $i < count($siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$siswa_id[$i],";
            $sql .= "$d_ssp_ssp_id,";
            $sql .= "$nilai_ssp[$i]";
            if($i<count($siswa_id)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        //echo $sql;
        
        if (!mysqli_query($conn, $sql))
        {
            echo("<br> Error description: " . mysqli_error($conn));
        }
        else{
            echo '<div class="alert alert-success alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>Data berhasil disimpan</strong>
                </div>';
        }
        mysqli_close($conn);
         
    }
    
?>
