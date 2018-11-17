<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }

    include_once '../includes/db_con.php';
    
    
    if(isset($_POST['ssp_revisi_id'])){
        
        $ssp_revisi_id = $_POST['ssp_revisi_id'];
        $pilihan = $_POST['pilihan'];
        
        //Revisi kog_psi_ujian
        $ssp_nilai_id = $_POST['ssp_nilai_id'];
        $ssp_rev_nilai_baru = $_POST['ssp_rev_nilai_baru'];
        
        // tampung yang diterima
        $ssp_nilai_id_a = array();
        $ssp_rev_nilai_baru_a = array();
        
        $tolak = 0;
        $pending = 0;
        $terima = 0;
        
        //Update tabel revisi
        $sql_update = "UPDATE ssp_revisi SET ssp_rev_status = CASE ssp_revisi_id ";
        
        for ($i = 0; $i < count($ssp_revisi_id); $i++)
        {
            $sql_update .= "WHEN ";
            $sql_update .= "$ssp_revisi_id[$i]";
            $sql_update .= " THEN ";
            $sql_update .= "'$pilihan[$i]' ";
            if($i == count($ssp_revisi_id)-1)
            {$sql_update .= "ELSE ssp_rev_status END";}
        }
        
        $sql_update .= " WHERE ssp_revisi_id in (";
        
        for ($i = 0; $i < count($ssp_revisi_id); $i++)
        {
            $sql_update .= $ssp_revisi_id[$i];
            if($i != count($ssp_revisi_id)-1)
            {$sql_update .= ",";}
        }
        
        $sql_update .= ")";
        
//        echo $sql_update;
//        echo "<br>";;
//        echo "<br>";
        
        //masukkan kedalam array id yang diterima
        for ($i = 0; $i < count($ssp_revisi_id); $i++)
        {
            if($pilihan[$i] ==1){
                array_push($ssp_nilai_id_a,$ssp_nilai_id[$i]);
                array_push($ssp_rev_nilai_baru_a,$ssp_rev_nilai_baru[$i]);
                $terima++;
            }
            elseif($pilihan[$i] ==2){
                $tolak++;
            }
            elseif($pilihan[$i] ==0){
                $pending++;
            }
        }
        
        //jika ada yang diterima
        if(count($ssp_nilai_id_a)>0){
            //update tabel asal
        
            $sql = "UPDATE ssp_nilai SET ssp_nilai_angka = CASE ssp_nilai_id "; 

            for ($i = 0; $i < count($ssp_nilai_id_a); $i++)
            {
                $sql .= "WHEN ";
                $sql .= "$ssp_nilai_id_a[$i]";
                $sql .= " THEN ";
                $sql .= "'$ssp_rev_nilai_baru_a[$i]' ";
                if($i == count($ssp_nilai_id_a)-1)
                {$sql .= "ELSE ssp_nilai_angka END";}
            }

            $sql .= " WHERE ssp_nilai_id in (";

            for ($i = 0; $i < count($ssp_nilai_id_a); $i++)
            {
                $sql .= $ssp_nilai_id_a[$i];
                if($i != count($ssp_nilai_id_a)-1)
                {$sql .= ",";}
            }

            $sql .= ")";
            
//            echo "UPDATE SSP NILAI: ".$sql."<br>";
        }
        
//        echo "<br>";
//        echo "UPDATE ssp_revisi: ".$sql_update."<br>";
//        echo "TOLAK: ".$tolak."<br>";
//        echo "PENDING: ".$pending."<br>";
//        echo "JUMLAH: ".count($ssp_revisi_id);
        if($tolak == count($ssp_revisi_id)){
            if (mysqli_query($conn, $sql_update))
            {   //jika semua data ditolak
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
        }elseif($pending == count($ssp_revisi_id)){
            //jika semua data dipending
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
        echo'<h6 class="text-center bg-success">Data Pengajuan Revisi SSP Tidak ada</h2>';
    }
?>

