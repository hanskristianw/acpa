<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }

    if($_POST['kelas_id_option']){
        include ("../includes/db_con.php");
        $kelas_id = $_POST['kelas_id_option'];
        $siswa_id = $_POST['siswa_id_option'];
        $ssp_asal = $_POST['ssp_asal_option'];
        $ssp_tujuan = $_POST['ssp_tujuan_option'];
        

        echo "Siswa ID: ".$siswa_id;
        echo "<br>SSP ASAL ID: ".$ssp_asal;
        echo "<br>SSP TUJUAN ID: ".$ssp_tujuan;
        //delete nilai ssp semua untuk siswa tersebut
        $sql_delete_nilai = "DELETE from ssp_nilai 
                            WHERE ssp_nilai_siswa_id = $siswa_id";
        echo "<br><br>";
        echo $sql_delete_nilai;

        //delete pendaftaran di ssp lama
        $sql_delete = "DELETE from ssp_daftar WHERE ssp_daftar_siswa_id = $siswa_id AND ssp_daftar_ssp_id = $ssp_asal";
        echo "<br><br>";
        echo $sql_delete;

        //insert pendaftaran sebagai ssp baru
        $sql_insert = "INSERT INTO ssp_daftar (ssp_daftar_ssp_id,ssp_daftar_siswa_id) VALUES ($ssp_tujuan, $siswa_id)";
        echo "<br><br>";
        echo $sql_insert;

        //insert nilai di ssp tujuan
            //cari topik di ssp tujuan yang sudah ada nilainya
        $sql_cek_topik =    "SELECT DISTINCT d_ssp_id, d_ssp_kriteria FROM ssp_nilai 
                            LEFT JOIN d_ssp 
                            ON ssp_nilai_d_ssp_id = d_ssp_id 
                            LEFT JOIN ssp 
                            ON d_ssp_ssp_id = ssp_id 
                            WHERE ssp_id = $ssp_tujuan";
        echo "<br><br>";
        
        
        $query_info2 = mysqli_query($conn, $sql_cek_topik);
        
        $d_id = array();

        while($row2 = mysqli_fetch_array($query_info2)){
            array_push($d_id, $row2['d_ssp_id']);
        }
        
        $sql_insert_baru = "INSERT INTO ssp_nilai(ssp_nilai_siswa_id, ssp_nilai_d_ssp_id, ssp_nilai_angka) VALUES ";

        for($i=0;$i<count($d_id);$i++){
            $sql_insert_baru .= "(".$siswa_id.",";
            $sql_insert_baru .= $d_id[$i];
            $sql_insert_baru .= ",4";
            $sql_insert_baru .= ")";
            if($i != count($d_id) - 1){
                $sql_insert_baru .= ",";
            }
        }
        echo $sql_insert_baru;
    }

?>