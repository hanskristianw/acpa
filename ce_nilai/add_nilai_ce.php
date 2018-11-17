<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php
    
    if(!empty($_POST["option_nilai"])) {
        include ("../includes/db_con.php");
        
        $ce_id = $_POST["ce_id"];
        $siswa_id = $_POST["siswa_id"];
        $ce_nilai_angka = $_POST["option_nilai"];
        
        $sql = "INSERT INTO ce_nilai(ce_nilai_siswa_id, ce_nilai_ce_id, ce_nilai_angka) VALUES ";
        
        for ($i = 0; $i < count($siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$siswa_id[$i],";
            $sql .= "$ce_id,";
            $sql .= "$ce_nilai_angka[$i]";
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
