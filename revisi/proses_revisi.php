<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    
    if(isset($_POST['kog_psi_ujian_rev_id'])){
        
        //dapatkan nilai dan siswa id
        $kog_psi_ujian_rev_id = $_POST['kog_psi_ujian_rev_id'];
        $pilihan = $_POST['pilihan'];
        
        //Revisi kog_psi_ujian
        $kog_psi_ujian_id = $_POST['kog_psi_ujian_id'];
        $kog_mid = $_POST['kog_uts_rev'];
        $kog_final = $_POST['kog_uas_rev'];
        $psi_mid = $_POST['psi_uts_rev'];
        $psi_final = $_POST['psi_uas_rev'];
        
        $kog_psi_ujian_id_a = array();
        $kog_mid_a = array();
        $kog_final_a = array();
        $psi_mid_a = array();
        $psi_final_a = array();
        
        
        $tolak = 0;
        $pending = 0;
        $terima = 0;
        //Update tabel revisi
        $sql_update = "UPDATE kog_psi_ujian_revisi SET ujian_rev_status = CASE kog_psi_ujian_rev_id ";
        
        for ($i = 0; $i < count($kog_psi_ujian_rev_id); $i++)
        {
            $sql_update .= "WHEN ";
            $sql_update .= "$kog_psi_ujian_rev_id[$i]";
            $sql_update .= " THEN ";
            $sql_update .= "'$pilihan[$i]' ";
            if($i == count($kog_psi_ujian_rev_id)-1)
            {$sql_update .= "ELSE ujian_rev_status END";}
        }
        
        $sql_update .= " WHERE kog_psi_ujian_rev_id in (";
        
        for ($i = 0; $i < count($kog_psi_ujian_rev_id); $i++)
        {
            $sql_update .= $kog_psi_ujian_rev_id[$i];
            if($i != count($kog_psi_ujian_rev_id)-1)
            {$sql_update .= ",";}
        }
        
        $sql_update .= ")";
        
//        echo $sql_update;
//        echo "<br>";;
//        echo "<br>";
        
        for ($i = 0; $i < count($kog_psi_ujian_id); $i++)
        {
            if($pilihan[$i] ==1){
                array_push($kog_psi_ujian_id_a,$kog_psi_ujian_id[$i]);
                array_push($kog_mid_a,$kog_mid[$i]);
                array_push($kog_final_a,$kog_final[$i]);
                array_push($psi_mid_a,$psi_mid[$i]);
                array_push($psi_final_a,$psi_final[$i]);
                $terima++;
            }
            elseif($pilihan[$i] ==2){
                $tolak++;
            }
            elseif($pilihan[$i] ==0){
                $pending++;
            }
        }
        
        if(count($kog_psi_ujian_id_a)>0){
            //update tabel asal
        
            $sql = "UPDATE kog_psi_ujian SET kog_uts = CASE kog_psi_ujian_id "; 

            for ($i = 0; $i < count($kog_psi_ujian_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_ujian_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$kog_mid_a[$i]' ";
                if($i == count($kog_psi_ujian_id_a)-1)
                {$sql .= "ELSE kog_uts END,";}
            }

            $sql .= " kog_uas = CASE kog_psi_ujian_id ";

            for ($i = 0; $i < count($kog_psi_ujian_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_ujian_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$kog_final_a[$i]' ";
                if($i == count($kog_psi_ujian_id_a)-1)
                {$sql .= "ELSE kog_uas END,";}
            }

            $sql .= " psi_uts = CASE kog_psi_ujian_id ";

            for ($i = 0; $i < count($kog_psi_ujian_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_ujian_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$psi_mid_a[$i]' ";
                if($i == count($kog_psi_ujian_id_a)-1)
                {$sql .= "ELSE psi_uts END,";}
            }

            $sql .= " psi_uas = CASE kog_psi_ujian_id ";

            for ($i = 0; $i < count($kog_psi_ujian_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_ujian_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$psi_final_a[$i]' ";
                if($i == count($kog_psi_ujian_id_a)-1)
                {$sql .= "ELSE psi_uas END";}
            }

            $sql .= " WHERE kog_psi_ujian_id in (";

            for ($i = 0; $i < count($kog_psi_ujian_id_a); $i++)
            {
                $sql .= $kog_psi_ujian_id_a[$i];
                if($i != count($kog_psi_ujian_id_a)-1)
                {$sql .= ",";}
            }

            $sql .= ")";

            //echo $sql;
        }
        
        if($tolak == count($kog_psi_ujian_rev_id)){
            if (mysqli_query($conn, $sql_update))
            {
                //jika ada data yang ditolak dan data yang diterima
                echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button><strong>';
                            if($tolak > 0){
                                echo $tolak.' pengajuan revisi ditolak';
                            }
                echo '</strong>
                      </div>';
            }
        }elseif($pending == count($kog_psi_ujian_rev_id)){
            echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button><strong>';
                            echo 'Pengajuan revisi dipending, data tidak berubah<br>';
                echo '</strong>
                      </div>';
        }
        else{
            if (mysqli_query($conn, $sql_update) && mysqli_query($conn, $sql))
            {
                //jika ada data yang ditolak dan data yang diterima
                echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button><strong>';
                            $pesan1 = count($kog_psi_ujian_id_a);
                            echo $terima.' pengajuan revisi diterima<br>';
                            if($tolak > 0){
                                echo $tolak.' pengajuan revisi ditolak';
                            }
                echo '</strong>
                      </div>';
            }
        }
        
        mysqli_close($conn);
    }
    else{
        echo'<h2 class="text-center">Data Pengajuan Revisi Ujian Tidak ada</h2>';
    }
?>

