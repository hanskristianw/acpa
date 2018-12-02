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
        
        //data lama
        $query_lama = "SELECT * from ssp_nilai
                    LEFT JOIN d_ssp
                    ON ssp_nilai_d_ssp_id =  d_ssp_id
                    LEFT JOIN siswa
                    ON ssp_nilai_siswa_id =  siswa_id
                    WHERE d_ssp_id = $d_ssp_ssp_id";
        
        $query_tes_lama = mysqli_query($conn, $query_lama);
        
        $ssp_nilai_id_lama = array();
        $ssp_nilai_angka_lama = array();
        
        while($row = mysqli_fetch_array($query_tes_lama)){
            
            array_push($ssp_nilai_id_lama,$row['ssp_nilai_id']);
            array_push($ssp_nilai_angka_lama,$row['ssp_nilai_angka']);
        }
        
        //data baru
        $ssp_nilai_id_baru = $_POST["ssp_nilai_id"];
        $siswa_id_baru = $_POST["siswa_id"];
        $ssp_nilai_angka_baru = $_POST["option_nilai"];
        
        //bandingkan data lama dengan baru
        $index_update = array();
        
        for ($i = 0; $i < count($ssp_nilai_id_lama); $i++)
        {
            for ($i = 0; $i < count($ssp_nilai_id_baru); $i++)
            {
                if($ssp_nilai_angka_lama[$i]!=$ssp_nilai_angka_baru[$i]){
                    array_push($index_update,$i);
                }
            }
        }
        
//        for ($i = 0; $i < count($index_update); $i++)
//        {
//            echo $ssp_nilai_id_baru[$index_update[$i]];
//            echo "<br>";
//        }
        
        $guru_name_rev = $_SESSION['guru_name'];
        $alasan_update = $_POST['alasan_update'];

        $sql =  "INSERT INTO ssp_revisi
                (ssp_rev_guru_name,ssp_rev_nilai_lama, ssp_rev_nilai_baru, ssp_rev_ssp_nilai_id, ssp_rev_alasan, ssp_rev_tanggal, ssp_rev_status) VALUES ";

        for ($i = 0; $i < count($index_update); $i++)
        {
            $sql .= "(";
            $sql .= "'".$guru_name_rev."'";
            $sql .= ",";
            $sql .= $ssp_nilai_angka_lama[$index_update[$i]];
            $sql .= ",";
            $sql .= $ssp_nilai_angka_baru[$index_update[$i]];
            $sql .= ",";
            $sql .= $ssp_nilai_id_baru[$index_update[$i]];
            $sql .= ",";
            $sql .= "'".$alasan_update."'";
            $sql .= ",";
            $sql .= "CURDATE()";
            $sql .= ",";
            $sql .= "0";
            if($i<count($index_update)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        //echo $sql;
        if(count($index_update)>0){
            if (!mysqli_query($conn, $sql))
            {
                echo("<br> Error description: " . mysqli_error($conn));
            }
            else{
                echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Update data akan diterukan ke wakakur</strong>
                    </div>';
            }
        }else{
            echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Tidak ada perubahan data</strong>
                    </div>';
        }
        mysqli_close($conn);
        
        
    }
    
?>
