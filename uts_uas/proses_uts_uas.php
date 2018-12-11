<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    //jika belum pernah input nilai uts uas
    if(isset($_POST['insertkpu'])){
        
        //dapatkan nilai dan siswa id
        $mapel_id = $_POST['mapel_id'];
        $siswa_id = $_POST['siswa_id'];
        $kelas_id = $_POST['kelas_id'];
        $kog_uts = $_POST['kog_uts'];
        $kog_uas = $_POST['kog_uas'];
        $psi_uts = $_POST['psi_uts'];
        $psi_uas = $_POST['psi_uas'];
        $persen_kmid = $_POST['persen_kmid'];
        $persen_kfinal = $_POST['persen_kfinal'];
        $persen_pmid = $_POST['persen_pmid'];
        $persen_pfinal = $_POST['persen_pfinal'];
        
        $sql = "INSERT INTO kog_psi_ujian(kog_psi_ujian_mapel_id, kog_psi_ujian_siswa_id, kog_uts, kog_uas, kog_uts_persen, kog_uas_persen, psi_uts, psi_uas, psi_uts_persen, psi_uas_persen) VALUES "; 
        
        for ($i = 0; $i < count($siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$mapel_id,";
            $sql .= "$siswa_id[$i],";
            $sql .= "$kog_uts[$i],";
            $sql .= "$kog_uas[$i],";
            $sql .= "$persen_kmid,";
            $sql .= "$persen_kfinal,";
            $sql .= "$psi_uts[$i],";
            $sql .= "$psi_uas[$i],";
            $sql .= "$persen_pmid,";
            $sql .= "$persen_pfinal";
            if($i<count($siswa_id)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        $query =    "SELECT *
                    from siswa
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN kog_psi_ujian 
                    ON siswa_id = kog_psi_ujian_siswa_id
                    WHERE siswa_id_kelas = $kelas_id AND kog_psi_ujian_mapel_id = $mapel_id";

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
                        <strong>Data pada topik dan kelas ini sudah ada</strong>
                    </div>';
        }
        
    }
    
    //jika sudah pernah
    if(isset($_POST['updatekpu'])){
        
        //mapel_id
        $kelas_id = $_POST['kelas_id'];
        $mapel_id = $_POST['mapel_id'];
        //dapatkan nilai dan siswa id
        $kog_psi_ujian_id = $_POST['siswa_id'];
        $kog_mid = $_POST['kog_mid'];
        $kog_final = $_POST['kog_final'];
        $psi_mid = $_POST['psi_mid'];
        $psi_final = $_POST['psi_final'];
        $persen_kmid = $_POST['persen_kmid'];
        $persen_kfinal = $_POST['persen_kfinal'];
        $persen_pmid = $_POST['persen_pmid'];
        $persen_pfinal = $_POST['persen_pfinal'];
        $alasan_update = $_POST['alasan_update'];
        
        $query =    "SELECT *
                    from siswa
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN kog_psi_ujian 
                    ON siswa_id = kog_psi_ujian_siswa_id
                    WHERE siswa_id_kelas = $kelas_id AND kog_psi_ujian_mapel_id = $mapel_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
        
//        echo "LAMA";
//        
//        echo "<br>id  koguts  koguas   psiuts   psiuas   persen_kmid   persenkfinal   persenpmid    persenpfinal<br>";
        $kogpsiid_lama = array();
        $koguts_lama = array();
        $koguas_lama = array();
        $psiuts_lama = array();
        $psiuas_lama = array();

        $kog_uts_persen_lama;
        $kog_uas_persen_lama;
        $psi_uts_persen_lama;
        $psi_uas_persen_lama;
            
        while($row = mysqli_fetch_array($query_afektif_info)){
            
            array_push($kogpsiid_lama,$row['kog_psi_ujian_id']);
            array_push($koguts_lama,$row['kog_uts']);
            array_push($koguas_lama,$row['kog_uas']);
            array_push($psiuts_lama,$row['psi_uts']);
            array_push($psiuas_lama,$row['psi_uas']);
            
            $kog_uts_persen_lama = $row['kog_uts_persen'];
            $kog_uas_persen_lama = $row['kog_uas_persen'];
            $psi_uts_persen_lama = $row['psi_uts_persen'];
            $psi_uas_persen_lama = $row['psi_uas_persen'];
            
        }
        
//        for ($i = 0; $i < count($kogpsiid_lama); $i++)
//        {
//            echo $kogpsiid_lama[$i];
//            echo " ";
//            echo $koguts_lama[$i];
//            echo " ";
//            echo $koguas_lama[$i];
//            echo " ";
//            echo $psiuts_lama[$i];
//            echo " ";
//            echo $psiuas_lama[$i];
//            echo " ";
//            echo $kog_uts_persen_lama;
//            echo " ";
//            echo $kog_uas_persen_lama;
//            echo " ";
//            echo $psi_uts_persen_lama;
//            echo " ";
//            echo $psi_uas_persen_lama;
//            echo "<br>";
//        }
        
        /////////////////////////////////////////////
//        echo "<br>BARU<br>kelas id: ".$kelas_id;
//        echo "<br>mapel id: ".$mapel_id;
//        
//        echo "<br>id  koguts  koguas   psiuts   psiuas   persen_kmid   persenkfinal   persenpmid    persenpfinal<br>";
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            echo $kog_psi_ujian_id[$i];
//            echo " ";
//            echo $kog_mid[$i];
//            echo " ";
//            echo $kog_final[$i];
//            echo " ";
//            echo $psi_mid[$i];
//            echo " ";
//            echo $psi_final[$i];
//            echo " ";
//            echo $persen_kmid;
//            echo " ";
//            echo $persen_kfinal;
//            echo " ";
//            echo $persen_pmid;
//            echo " ";
//            echo $persen_pfinal;
//            echo "<br>";
//        }
        
        $index_update = array();
        
//        echo "<br>UPDATE<br>";
        for ($i = 0; $i < count($kogpsiid_lama); $i++)
        {
            for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
            {
                if($koguts_lama[$i]!=$kog_mid[$i] || $koguas_lama[$i]!=$kog_final[$i] || $psiuts_lama[$i]!=$psi_mid[$i] || $psiuas_lama[$i]!=$psi_final[$i]){
                    array_push($index_update,$i);
                }
            }
        }
        
//        for ($i = 0; $i < count($index_update); $i++)
//        {
//            echo $kogpsiid_lama[$index_update[$i]];
//            echo " ";
//            echo $koguts_lama[$index_update[$i]];
//            echo " ";
//            echo $koguas_lama[$index_update[$i]];
//            echo " ";
//            echo $psiuts_lama[$index_update[$i]];
//            echo " ";
//            echo $psiuas_lama[$index_update[$i]];
//            echo "<br>";
//            echo $kog_psi_ujian_id[$index_update[$i]];
//            echo " ";
//            echo $kog_mid[$index_update[$i]];
//            echo " ";
//            echo $kog_final[$index_update[$i]];
//            echo " ";
//            echo $psi_mid[$index_update[$i]];
//            echo " ";
//            echo $psi_final[$index_update[$i]];
//            echo "<br>";
//        }
        
        
        //UPDATE PERSENTASE
        $tanda_persen = 0;
        if($kog_uts_persen_lama!=$persen_kmid || $kog_uas_persen_lama!=$persen_kfinal || $psi_uts_persen_lama!=$persen_pmid || $psi_uas_persen_lama!=$persen_pfinal){
            $tanda_persen = 1;
            $sql_update_persen = "UPDATE kog_psi_ujian SET kog_uts_persen = CASE kog_psi_ujian_id ";
        
            for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_ujian_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "'$persen_kmid' ";
                if($i == count($kog_psi_ujian_id)-1)
                {$sql_update_persen .= "ELSE kog_uts_persen END,";}
            }

            $sql_update_persen .= " kog_uas_persen = CASE kog_psi_ujian_id ";

            for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_ujian_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "'$persen_kfinal' ";
                if($i == count($kog_psi_ujian_id)-1)
                {$sql_update_persen .= "ELSE kog_uas_persen END,";}
            }

            $sql_update_persen .= " psi_uts_persen = CASE kog_psi_ujian_id ";

            for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_ujian_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "'$persen_pmid' ";
                if($i == count($kog_psi_ujian_id)-1)
                {$sql_update_persen .= "ELSE psi_uts_persen END,";}
            }

            $sql_update_persen .= " psi_uas_persen = CASE kog_psi_ujian_id ";

            for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_ujian_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "'$persen_pfinal' ";
                if($i == count($kog_psi_ujian_id)-1)
                {$sql_update_persen .= "ELSE psi_uas_persen END";}
            }

            $sql_update_persen .= " WHERE kog_psi_ujian_id in (";

            for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
            {
                $sql_update_persen .= $kog_psi_ujian_id[$i];
                if($i != count($kog_psi_ujian_id)-1)
                {$sql_update_persen .= ",";}
            }

            $sql_update_persen .= ")";
        }
        
        
        if($tanda_persen > 0){
            if (!mysqli_query($conn, $sql_update_persen))
            {
                echo("<br> Error description: " . mysqli_error($conn));
            }
            else{
                echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Berhasil merubah persentase</strong>
                    </div>';
            }
        }
        //masukkan ke tabel revisi
        if(count($index_update) > 0){
            
            $guru_name_rev = $_SESSION['guru_name'];
            
            $sql =  "INSERT INTO kog_psi_ujian_revisi
                    (kog_psi_ujian_id_fk, 
                    kog_uts_lama, kog_uts_rev, kog_uas_lama, kog_uas_rev,
                    psi_uts_lama, psi_uts_rev, psi_uas_lama, psi_uas_rev, 
                    ujian_revisi_tanggal, ujian_rev_status, ujian_rev_alasan, ujian_rev_mapel_id, ujian_rev_kelas_id, ujian_rev_guru_name) VALUES ";
            
            for ($i = 0; $i < count($index_update); $i++)
            {
                $sql .= "(";
                $sql .= $kog_psi_ujian_id[$index_update[$i]];
                $sql .= ",";
                $sql .= $koguts_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $kog_mid[$index_update[$i]];
                $sql .= ",";
                $sql .= $koguas_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $kog_final[$index_update[$i]];
                $sql .= ",";
                $sql .= $psiuts_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $psi_mid[$index_update[$i]];
                $sql .= ",";
                $sql .= $psiuas_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $psi_final[$index_update[$i]];
                $sql .= ",";
                $sql .= "CURDATE()";
                $sql .= ",";
                $sql .= "0";
                $sql .= ",";
                $sql .= "'$alasan_update'";
                $sql .= ",";
                $sql .= "'$mapel_id'";
                $sql .= ",";
                $sql .= "'$kelas_id'";
                $sql .= ",";
                $sql .= "'$guru_name_rev'";
                if($i<count($index_update)-1)
                {$sql .= "),";}
                else
                {$sql .= ")";}
            }
            
//            echo $sql;
//            echo "<br>";
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
                        <strong>Tidak ada perubahan nilai</strong>
                    </div>';
        }
        
        
//        $sql = "UPDATE kog_psi_ujian SET kog_uts = CASE kog_psi_ujian_id "; 
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$kog_mid[$i]' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE kog_uts END,";}
//        }
//        
//        $sql .= " kog_uas = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$kog_final[$i]' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE kog_uas END,";}
//        }
//        
//        
//        $sql .= " kog_uts_persen = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_kmid' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE kog_uts_persen END,";}
//        }
//        
//        $sql .= " kog_uas_persen = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_kfinal' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE kog_uas_persen END,";}
//        }
//        
//        $sql .= " psi_uts = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$psi_mid[$i]' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE psi_uts END,";}
//        }
//        
//        $sql .= " psi_uas = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$psi_final[$i]' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE psi_uas END,";}
//        }
//        
//        $sql .= " psi_uts_persen = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_pmid' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE psi_uts_persen END,";}
//        }
//        
//        $sql .= " psi_uas_persen = CASE kog_psi_ujian_id ";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_ujian_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_pfinal' ";
//            if($i == count($kog_psi_ujian_id)-1)
//            {$sql .= "ELSE psi_uas_persen END";}
//        }
//        
//        $sql .= " WHERE kog_psi_ujian_id in (";
//        
//        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
//        {
//            $sql .= $kog_psi_ujian_id[$i];
//            if($i != count($kog_psi_ujian_id)-1)
//            {$sql .= ",";}
//        }
//        
//        $sql .= ")";
//        
//        //echo $sql;
//        //mysqli_query($conn, $sql);
//        
//        if (!mysqli_query($conn, $sql))
//        {
//            echo("<br> Error description: " . mysqli_error($conn));
//        }
//        else{
//            echo '<div class="alert alert-success alert-dismissible fade show">
//                    <button class="close" data-dismiss="alert" type="button">
//                        <span>&times;</span>
//                    </button>
//                    <strong>Data berhasil diupdate</strong>
//                </div>';
//        }
//        mysqli_close($conn);
        mysqli_close($conn);
    }
?>

<script>
        
    $(document).ready(function(){
        
        
    });
</script>

