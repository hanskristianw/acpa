<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    
    if(isset($_POST['kog_psi_rev_id'])){
        
        //dapatkan nilai dan siswa id
        $kog_psi_rev_id = $_POST['kog_psi_rev_id'];
        $pilihan = $_POST['pilihan'];
        
        //Revisi kog_psi_ujian
        $kog_psi_id = $_POST['kog_psi_id'];
        $kog_q_rev = $_POST['kog_q_rev'];
        $kog_a_rev = $_POST['kog_a_rev'];
        $kog_t_rev = $_POST['kog_t_rev'];
        $psi_q_rev = $_POST['psi_q_rev'];
        $psi_a_rev = $_POST['psi_a_rev'];
        $psi_t_rev = $_POST['psi_t_rev'];
        
        $kog_psi_id_a = array();
        $kog_q_rev_a = array();
        $kog_a_rev_a = array();
        $kog_t_rev_a = array();
        $psi_q_rev_a = array();
        $psi_a_rev_a = array();
        $psi_t_rev_a = array();
        
        
        $tolak = 0;
        $pending = 0;
        $terima = 0;
        
        //Update tabel revisi
        $sql_update = "UPDATE kog_psi_revisi SET rev_status = CASE kog_psi_rev_id ";
        
        for ($i = 0; $i < count($kog_psi_rev_id); $i++)
        {
            $sql_update .= "WHEN ";
            $sql_update .= "$kog_psi_rev_id[$i]";
            $sql_update .= " THEN ";
            $sql_update .= "'$pilihan[$i]' ";
            if($i == count($kog_psi_rev_id)-1)
            {$sql_update .= "ELSE rev_status END";}
        }
        
        $sql_update .= " WHERE kog_psi_rev_id in (";
        
        for ($i = 0; $i < count($kog_psi_rev_id); $i++)
        {
            $sql_update .= $kog_psi_rev_id[$i];
            if($i != count($kog_psi_rev_id)-1)
            {$sql_update .= ",";}
        }
        
        $sql_update .= ")";
        
//        echo $sql_update;
//        echo "<br>";;
//        echo "<br>";
        
        for ($i = 0; $i < count($kog_psi_id); $i++)
        {
            if($pilihan[$i] ==1){
                array_push($kog_psi_id_a,$kog_psi_id[$i]);
                array_push($kog_q_rev_a,$kog_q_rev[$i]);
                array_push($kog_a_rev_a,$kog_a_rev[$i]);
                array_push($kog_t_rev_a,$kog_t_rev[$i]);
                array_push($psi_q_rev_a,$psi_q_rev[$i]);
                array_push($psi_a_rev_a,$psi_a_rev[$i]);
                array_push($psi_t_rev_a,$psi_t_rev[$i]);
                $terima++;
            }
            elseif($pilihan[$i] ==2){
                $tolak++;
            }
            elseif($pilihan[$i] ==0){
                $pending++;
            }
        }
        
        if(count($kog_psi_id_a)>0){
            //update tabel asal
        
            $sql = "UPDATE kog_psi SET kog_quiz = CASE kog_psi_id "; 

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$kog_q_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE kog_quiz END,";}
            }

            $sql .= " kog_ass = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$kog_a_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE kog_ass END,";}
            }

            $sql .= " kog_test = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$kog_t_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE kog_test END,";}
            }

            $sql .= " kog_quiz = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$kog_q_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE kog_quiz END,";}
            }
            
            $sql .= " psi_ass = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$psi_a_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE psi_ass END,";}
            }

            $sql .= " psi_test = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$psi_t_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE psi_test END,";}
            }

            $sql .= " psi_quiz = CASE kog_psi_id ";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$kog_psi_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$psi_q_rev_a[$i]' ";
                if($i == count($kog_psi_id_a)-1)
                {$sql .= "ELSE psi_quiz END";}
            }

            $sql .= " WHERE kog_psi_id in (";

            for ($i = 0; $i < count($kog_psi_id_a); $i++)
            {
                $sql .= $kog_psi_id_a[$i];
                if($i != count($kog_psi_id_a)-1)
                {$sql .= ",";}
            }

            $sql .= ")";

            //echo $sql;
        }
//        echo $sql;
//        echo "<br>";
//        echo $sql_update;
        
        
        if($tolak == count($kog_psi_rev_id)){
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
        }elseif($pending == count($kog_psi_rev_id)){
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
                            $pesan1 = count($kog_psi_rev_id);
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
        echo'<h6 class="text-center bg-success">Data Pengajuan Revisi Tes Tidak ada</h2>';
    }
?>

