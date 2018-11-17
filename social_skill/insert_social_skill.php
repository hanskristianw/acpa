<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    if(isset($_POST['siswa_id'])){
        
        $kelas_id = $_POST['kelas_id'];
        
        //dapatkan nilai dan siswa id
        $ss_relationship = $_POST['option_relationship'];
        $ss_cooperation = $_POST['option_cooperation'];
        $ss_conflict = $_POST['option_conflict'];
        $ss_self_a = $_POST['option_self_appraisal'];
        $ss_siswa_id = $_POST['siswa_id'];
        
        $sql = "INSERT INTO ss(ss_relationship, ss_cooperation, ss_conflict, ss_self_a, ss_siswa_id) VALUES "; 
        
        for ($i = 0; $i < count($ss_siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$ss_relationship[$i],";
            $sql .= "$ss_cooperation[$i],";
            $sql .= "$ss_conflict[$i],";
            $sql .= "$ss_self_a[$i],";
            $sql .= "$ss_siswa_id[$i]";
            if($i<count($ss_siswa_id)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        //cek sudah ada nilai apa belum
        $query =    "SELECT *
                    from ss
                    LEFT JOIN siswa 
                    ON ss_siswa_id = siswa_id
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

