<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    if(isset($_POST['siswa_id'])){
        
        $kelas_id = $_POST['kelas_id'];
        
        //dapatkan nilai dan siswa id
        $spirit_siswa_id = $_POST['siswa_id'];
        $spirit_coping = $_POST['option_coping'];
        $spirit_emo = $_POST['option_emo'];
        $spirit_grate = $_POST['option_grate'];
        $spirit_ref = $_POST['option_ref'];
        
        $sql = "INSERT INTO spirit(spirit_coping, spirit_emo, spirit_grate, spirit_ref, spirit_siswa_id) VALUES "; 
        
        for ($i = 0; $i < count($spirit_siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$spirit_coping[$i],";
            $sql .= "$spirit_emo[$i],";
            $sql .= "$spirit_grate[$i],";
            $sql .= "$spirit_ref[$i],";
            $sql .= "$spirit_siswa_id[$i]";
            if($i<count($spirit_siswa_id)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        //cek sudah ada nilai apa belum
        $query =    "SELECT *
                    from spirit
                    LEFT JOIN siswa 
                    ON spirit_siswa_id = siswa_id
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    WHERE siswa_id_kelas = $kelas_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        if($resultCheck == 0){
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
        }else{
            echo '<div class="alert alert-danger alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Data sudah ada</strong>
                    </div>';
        }
        
    }
    
?>

