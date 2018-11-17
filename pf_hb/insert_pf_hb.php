<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    if(isset($_POST['siswa_id'])){
        
        $kelas_id = $_POST['kelas_id'];
        
        //dapatkan nilai dan siswa id
        $pf_hf_siswa_id = $_POST['siswa_id'];
        $pf_hf_absent = $_POST['option_absent'];
        $pf_hf_uks = $_POST['option_uks'];
        $pf_hf_tardiness = $_POST['option_tardiness'];
        
        $sql = "INSERT INTO pf_hf(pf_hf_absent, pf_hf_uks, pf_hf_tardiness, pf_hf_siswa_id) VALUES "; 
        
        for ($i = 0; $i < count($pf_hf_siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$pf_hf_absent[$i],";
            $sql .= "$pf_hf_uks[$i],";
            $sql .= "$pf_hf_tardiness[$i],";
            $sql .= "$pf_hf_siswa_id[$i]";
            if($i<count($pf_hf_siswa_id)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        //cek sudah ada nilai apa belum
        $query =    "SELECT *
                    from pf_hf
                    LEFT JOIN siswa 
                    ON pf_hf_siswa_id = siswa_id
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

