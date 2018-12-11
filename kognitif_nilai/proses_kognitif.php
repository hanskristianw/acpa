<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    include_once '../includes/db_con.php';
    
    if(isset($_POST['insertkp'])){
        
        //dapatkan nilai dan siswa id
        $topik_id = $_POST['topik_id'];
        $siswa_id = $_POST['siswa_id'];
        $kelas_id = $_POST['kelas_id'];
        
        $kq = $_POST['kq'];
        $kt = $_POST['kt'];
        $ka = $_POST['ka'];
        $pq = $_POST['pq'];
        $pt = $_POST['pt'];
        $pa = $_POST['pa'];
        $persen_kquiz = $_POST['persen_kquiz'];
        $persen_ktest = $_POST['persen_ktest'];
        $persen_kass = $_POST['persen_kass'];
        $persen_pquiz = $_POST['persen_pquiz'];
        $persen_ptest = $_POST['persen_ptest'];
        $persen_pass = $_POST['persen_pass'];
        //$topik_id = $_POST['topik_id'];
        
        $sql = "INSERT INTO kog_psi(kog_psi_topik_id, kog_psi_siswa_id, kog_quiz, kog_ass, kog_test, kog_quiz_persen, kog_ass_persen, kog_test_persen, psi_quiz, psi_test, psi_ass, psi_quiz_persen, psi_ass_persen, psi_test_persen) VALUES "; 
        
        for ($i = 0; $i < count($siswa_id); $i++)
        {
            $sql .= "(";
            $sql .= "$topik_id,";
            $sql .= "'$siswa_id[$i]',";
            $sql .= "$kq[$i],";
            $sql .= "$ka[$i],";
            $sql .= "$kt[$i],";
            $sql .= "$persen_kquiz,";
            $sql .= "$persen_kass,";
            $sql .= "$persen_ktest,";
            $sql .= "$pq[$i],";
            $sql .= "$pt[$i],";
            $sql .= "$pa[$i],";
            $sql .= "$persen_pquiz,";
            $sql .= "$persen_pass,";
            $sql .= "$persen_ptest";
            if($i<count($siswa_id)-1)
            {$sql .= "),";}
            else
            {$sql .= ")";}
        }
        
        $query =    "SELECT *
                    from siswa
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN kog_psi 
                    ON siswa_id = kog_psi_siswa_id
                    LEFT JOIN topik
                    ON kog_psi_topik_id = topik_id
                    WHERE siswa_id_kelas = $kelas_id AND topik_id = $topik_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        
        //jika belum pernah isi
        if($resultCheck == 0){
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
                        <strong>Data di kelas ini dan topik ini sudah ada</strong>
                    </div>';
        }
        
        //echo $sql;
        
        
    }
    
    if(isset($_POST['updatekp'])){
        
        
        $mapel_id = $_POST['mapel_id'];
        $kelas_id = $_POST['kelas_id'];
        
        //dapatkan nilai dan siswa id
        $topik_id = $_POST['topik_id'];
        $kog_psi_id = $_POST['siswa_id'];
        $kq = $_POST['kq'];
        $kt = $_POST['kt'];
        $ka = $_POST['ka'];
        $pq = $_POST['pq'];
        $pt = $_POST['pt'];
        $pa = $_POST['pa'];
        $persen_kquiz = $_POST['persen_kquiz'];
        $persen_ktest = $_POST['persen_ktest'];
        $persen_kass = $_POST['persen_kass'];
        $persen_pquiz = $_POST['persen_pquiz'];
        $persen_ptest = $_POST['persen_ptest'];
        $persen_pass = $_POST['persen_pass'];
        
        //dapatkan data lama
        $query_lama = "SELECT *
                    from siswa
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN kog_psi 
                    ON siswa_id = kog_psi_siswa_id
                    LEFT JOIN topik
                    ON kog_psi_topik_id = topik_id
                    WHERE siswa_id_kelas = $kelas_id AND topik_id = $topik_id";
        
        $query_tes_lama = mysqli_query($conn, $query_lama);
        $resultteslama = mysqli_num_rows($query_tes_lama);
        
        $kogtesid_lama = array();
        $kogquiz_lama = array();
        $kogass_lama = array();
        $kogtest_lama = array();
        $psiquiz_lama = array();
        $psiass_lama = array();
        $psitest_lama = array();

        $kog_quiz_persen_lama;
        $kog_ass_persen_lama;
        $kog_test_persen_lama;
        $psi_quiz_persen_lama;
        $psi_ass_persen_lama;
        $psi_test_persen_lama;
        
        while($row = mysqli_fetch_array($query_tes_lama)){
            
            array_push($kogtesid_lama,$row['kog_psi_id']);
            array_push($kogquiz_lama,$row['kog_quiz']);
            array_push($kogass_lama,$row['kog_ass']);
            array_push($kogtest_lama,$row['kog_test']);
            array_push($psiquiz_lama,$row['psi_quiz']);
            array_push($psiass_lama,$row['psi_ass']);
            array_push($psitest_lama,$row['psi_test']);
            
            $kog_quiz_persen_lama = $row['kog_quiz_persen'];
            $kog_ass_persen_lama = $row['kog_ass_persen'];
            $kog_test_persen_lama = $row['kog_test_persen'];
            $psi_quiz_persen_lama = $row['psi_quiz_persen'];
            $psi_ass_persen_lama = $row['psi_ass_persen'];
            $psi_test_persen_lama = $row['psi_test_persen'];
            
        }
        
//        for ($i = 0; $i < count($kogtesid_lama); $i++)
//        {
//            echo $kogtesid_lama[$i];
//            echo " ";
//            echo $kogquiz_lama[$i];
//            echo " ";
//            echo $kogass_lama[$i];
//            echo " ";
//            echo $kogtest_lama[$i];
//            echo " ";
//            echo $psiquiz_lama[$i];
//            echo " ";
//            echo $psiass_lama[$i];
//            echo " ";
//            echo $psitest_lama[$i];
//            echo " ";
//            echo $kog_quiz_persen_lama;
//            echo " ";
//            echo $kog_ass_persen_lama;
//            echo " ";
//            echo $kog_test_persen_lama;
//            echo " ";
//            echo $psi_quiz_persen_lama;
//            echo " ";
//            echo $psi_ass_persen_lama;
//            echo " ";
//            echo $psi_test_persen_lama;
//            echo "<br>";
//        }
        
//        echo "<br>BARU<br>kelas id: ".$kelas_id;
//        
//        echo "<br>id  koguts  koguas   psiuts   psiuas   persen_kmid   persenkfinal   persenpmid    persenpfinal<br>";
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            echo $kog_psi_id[$i];
//            echo " ";
//            echo $kq[$i];
//            echo " ";
//            echo $ka[$i];
//            echo " ";
//            echo $kt[$i];
//            echo " ";
//            echo $pq[$i];
//            echo " ";
//            echo $pa[$i];
//            echo " ";
//            echo $pt[$i];
//            echo " ";
//            echo $persen_kquiz;
//            echo " ";
//            echo $persen_ktest;
//            echo " ";
//            echo $persen_kass;
//            echo " ";
//            echo $persen_pquiz;
//            echo " ";
//            echo $persen_ptest;
//            echo " ";
//            echo $persen_pass;
//            echo "<br>";
//        }
        
        $index_update = array();
        
        //echo "<br>UPDATE<br>";
        for ($i = 0; $i < count($kogtesid_lama); $i++)
        {
            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                if($kogquiz_lama[$i]!=$kq[$i] || $kogass_lama[$i]!=$ka[$i] || $kogtest_lama[$i]!=$kt[$i] || $psiquiz_lama[$i]!=$pq[$i] || $psiass_lama[$i]!=$pa[$i] || $psitest_lama[$i]!=$pt[$i]){
                    array_push($index_update,$i);
                }
            }
        }
        
//        for ($i = 0; $i < count($index_update); $i++)
//        {
//            echo $kogtesid_lama[$index_update[$i]];
//            echo " ";
//            echo $kogquiz_lama[$index_update[$i]];
//            echo " ";
//            echo $kogass_lama[$index_update[$i]];
//            echo " ";
//            echo $kogtest_lama[$index_update[$i]];
//            echo " ";
//            echo $psiquiz_lama[$index_update[$i]];
//            echo " ";
//            echo $psiass_lama[$index_update[$i]];
//            echo " ";
//            echo $psitest_lama[$index_update[$i]];
//            echo "||||";
//            echo $kq[$index_update[$i]];
//            echo " ";
//            echo $ka[$index_update[$i]];
//            echo " ";
//            echo $kt[$index_update[$i]];
//            echo " ";
//            echo $pq[$index_update[$i]];
//            echo " ";
//            echo $pa[$index_update[$i]];
//            echo " ";
//            echo $pt[$index_update[$i]];
//            echo "<br>";
//        }
        
        //UPDATE PERSENTASE
        $tanda_persen = 0;
        if($kog_quiz_persen_lama!=$persen_kquiz || $kog_test_persen_lama!=$persen_ktest || $kog_ass_persen_lama!=$persen_kass || $psi_quiz_persen_lama!=$persen_pquiz || $psi_test_persen_lama!=$persen_ptest || $psi_ass_persen_lama!=$persen_pass){
            $tanda_persen = 1;
            $sql_update_persen = "UPDATE kog_psi SET kog_quiz_persen = CASE kog_psi_id ";
        
            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "$persen_kquiz ";
                if($i == count($kog_psi_id)-1)
                {$sql_update_persen .= "ELSE kog_quiz_persen END,";}
            }

            $sql_update_persen .= " kog_ass_persen = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "$persen_kass ";
                if($i == count($kog_psi_id)-1)
                {$sql_update_persen .= "ELSE kog_ass_persen END,";}
            }
            
            $sql_update_persen .= " kog_test_persen = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "$persen_ktest ";
                if($i == count($kog_psi_id)-1)
                {$sql_update_persen .= "ELSE kog_test_persen END,";}
            }

            $sql_update_persen .= " psi_quiz_persen = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "$persen_pquiz ";
                if($i == count($kog_psi_id)-1)
                {$sql_update_persen .= "ELSE psi_quiz_persen END,";}
            }

            $sql_update_persen .= " psi_ass_persen = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "$persen_pass ";
                if($i == count($kog_psi_id)-1)
                {$sql_update_persen .= "ELSE psi_ass_persen END,";}
            }
            
            $sql_update_persen .= " psi_test_persen = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= "WHEN ";
                $sql_update_persen .= "$kog_psi_id[$i]";
                $sql_update_persen .= " THEN ";
                $sql_update_persen .= "$persen_ptest ";
                if($i == count($kog_psi_id)-1)
                {$sql_update_persen .= "ELSE psi_test_persen END";}
            }

            $sql_update_persen .= " WHERE kog_psi_id in (";

            for ($i = 0; $i < count($kog_psi_id); $i++)
            {
                $sql_update_persen .= $kog_psi_id[$i];
                if($i != count($kog_psi_id)-1)
                {$sql_update_persen .= ",";}
            }

            $sql_update_persen .= ")";
        }
        
        //echo $sql_update_persen;
        
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
            $alasan_update = $_POST['alasan_update'];
            
            $sql =  "INSERT INTO kog_psi_revisi
                    (kog_psi_id_fk, 
                    kog_quiz_lama, kog_quiz_rev, kog_ass_lama, kog_ass_rev, kog_test_lama, kog_test_rev,
                    psi_quiz_lama, psi_quiz_rev, psi_ass_lama, psi_ass_rev, psi_test_lama, psi_test_rev,
                    rev_tanggal, rev_status, rev_alasan, rev_guru_name) VALUES ";
            
            for ($i = 0; $i < count($index_update); $i++)
            {
                $sql .= "(";
                $sql .= $kog_psi_id[$index_update[$i]];
                $sql .= ",";
                $sql .= $kogquiz_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $kq[$index_update[$i]];
                $sql .= ",";
                $sql .= $kogass_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $ka[$index_update[$i]];
                $sql .= ",";
                $sql .= $kogtest_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $kt[$index_update[$i]];
                $sql .= ",";
                $sql .= $psiquiz_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $pq[$index_update[$i]];
                $sql .= ",";
                $sql .= $psiass_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $pa[$index_update[$i]];
                $sql .= ",";
                $sql .= $psitest_lama[$index_update[$i]];
                $sql .= ",";
                $sql .= $pt[$index_update[$i]];
                $sql .= ",";
                $sql .= "CURDATE()";
                $sql .= ",";
                $sql .= "0";
                $sql .= ",";
                $sql .= "'$alasan_update'";
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
        
        
//        UPDATE categories
//        SET display_order = CASE id
//            WHEN 1 THEN 32
//            WHEN 2 THEN 33
//            WHEN 3 THEN 34
//        END,
//        title = CASE id
//            WHEN 1 THEN 'New Title 1'
//            WHEN 2 THEN 'New Title 2'
//            WHEN 3 THEN 'New Title 3'
//        END
//        WHERE id IN (1,2,3)
        
        
//        $sql = "UPDATE kog_psi SET kog_quiz = CASE kog_psi_id "; 
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$kq[$i]' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE kog_quiz END,";}
//        }
//        
//        $sql .= " kog_ass = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$ka[$i]' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE kog_ass END,";}
//        }
//        
//        $sql .= " kog_test = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$kt[$i]' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE kog_test END,";}
//        }
//        
//        $sql .= " kog_quiz_persen = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_kquiz' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE kog_quiz_persen END,";}
//        }
//        
//        $sql .= " kog_ass_persen = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_kass' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE kog_ass_persen END,";}
//        }
//        
//        $sql .= " kog_test_persen = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_ktest' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE kog_test_persen END,";}
//        }
//        
//        $sql .= " psi_quiz = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$pq[$i]' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE psi_quiz END,";}
//        }
//        
//        $sql .= " psi_test = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$pt[$i]' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE psi_test END,";}
//        }
//        
//        $sql .= " psi_ass = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$pa[$i]' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE psi_ass END,";}
//        }
//        
//        $sql .= " psi_quiz_persen = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_pquiz' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE psi_quiz_persen END,";}
//        }
//        
//        $sql .= " psi_ass_persen = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_pass' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE psi_ass_persen END,";}
//        }
//        
//        $sql .= " psi_test_persen = CASE kog_psi_id ";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= "WHEN ";
//            $sql .= "$kog_psi_id[$i]";
//            $sql .= " THEN ";
//            $sql .= "'$persen_ptest' ";
//            if($i == count($kog_psi_id)-1)
//            {$sql .= "ELSE psi_test_persen END";}
//        }
//        
//        $sql .= " WHERE kog_psi_id in (";
//        
//        for ($i = 0; $i < count($kog_psi_id); $i++)
//        {
//            $sql .= $kog_psi_id[$i];
//            if($i != count($kog_psi_id)-1)
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

