<?php
    function return_alert($msg,$jenis){

        $pesan ='<div class="alert alert-'.$jenis.' alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>Info:</strong> '.$msg.'
                </div>';
        
        return $pesan;
    }

    function return_total_nilai_afektif_bulan($nilAfektifBulan){
        if(count($nilAfektifBulan)>0){
            $total_nilai_afektif_bulan = 0;
            for ($i=0;$i<count($nilAfektifBulan);$i++){
                $nilai_perminggu = explode('/', $nilAfektifBulan[$i]);
                $total_nilai_indikator_tiap_bulan =0;
                $minggu_aktif =0;
                $cek_minggu_aktif =0;
                for ($j=0;$j<count($nilai_perminggu);$j++){
                    $nilai_per_indikator = explode('_', $nilai_perminggu[$j]);
                    for ($k=0;$k<count($nilai_per_indikator);$k++){
                        if($nilai_per_indikator[$k] > 0){
                            $cek_minggu_aktif += 1;
                        }
                        $total_nilai_indikator_tiap_bulan += $nilai_per_indikator[$k];
                    }
                    if($cek_minggu_aktif == 3){
                        $minggu_aktif +=1;
                    }
                    $cek_minggu_aktif = 0;
                }
                $nilai_afektif_bulan = $total_nilai_indikator_tiap_bulan/$minggu_aktif;
                $total_nilai_afektif_bulan += $nilai_afektif_bulan;
            }
            return $total_nilai_afektif_bulan;
        }else{
            return 0;
        }
    }

    function return_total_nilai_perkarakter($nilmapel){
        if(count($nilmapel)>0){
            $total_nilai_karakter = 0;
            for($za=0;$za<sizeof($nilmapel);$za++){
                $nilai_perbulan = explode('.', $nilmapel[$za]);
                $total_nilai_karakter += return_total_nilai_afektif_bulan($nilai_perbulan);
            }
            return $total_nilai_karakter;
        }else{
            return 0;
        }
    }

    function return_combo_kelas($guru_id){
        include ("db_con.php");
        
        if($guru_id == 0){
            $query =    "SELECT *
                    FROM kelas
                    LEFT JOIN t_ajaran
                    ON kelas_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1";

            $query_info = mysqli_query($conn, $query);

            $options = "<option value= 0>Pilih Kelas</option>";
             while($row = mysqli_fetch_array($query_info)){
                $kelas_nama = $row['kelas_nama'];

                $options .= "<option value={$row['kelas_id']}>$kelas_nama</option>";
             }

            echo"<select class='form-control form-control-sm mb-2' name='option_kelas' id='option_kelas'>";
                echo $options;
            echo"</select>";
        }
    }

    function return_combo_tema_ce($nama){
        include ("db_con.php");
        
        $query =    "SELECT *
                    FROM ce
                    LEFT JOIN t_ajaran
                    ON ce_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1";

        $query_info = mysqli_query($conn, $query);

        $options = "<option value= 0>Pilih Topik</option>";
            while($row = mysqli_fetch_array($query_info)){
                $options .= "<option value={$row['ce_id']}>{$row['ce_aspek']}</option>";
            }

        echo"<select class='form-control form-control-sm mb-2' name='".$nama."' id='".$nama."'>";
            echo $options;
        echo"</select>";
    }

    function return_combo_jenjang($nama){
        include ("db_con.php");
        
        $query =    "SELECT *
                    FROM jenjang";

        $query_info = mysqli_query($conn, $query);

        $options = "<option value= 0>Pilih Jenjang</option>";
            while($row = mysqli_fetch_array($query_info)){
                $options .= "<option value={$row['jenjang_id']}>{$row['jenjang_nama']}</option>";
            }

        echo"<select class='form-control form-control-sm mb-2' name='".$nama."' id='".$nama."'>";
            echo $options;
        echo"</select>";
    }


    function return_combo_indikator_by_tema_id($ce_id, $nama){
        include ("db_con.php");
        
        $query =    "SELECT *
                    FROM d_ce
                    LEFT JOIN ce
                    ON d_ce_ce_id = ce_id
                    WHERE d_ce_ce_id = $ce_id";

        $query_info = mysqli_query($conn, $query);

        $options = "<option value= 0>Pilih Indikator</option>";
            while($row = mysqli_fetch_array($query_info)){
                $options .= "<option value={$row['d_ce_id']}>{$row['d_ce_nama']}</option>";
            }

        echo"<select class='form-control form-control-sm mb-2' name='".$nama."' id='".$nama."'>";
            echo $options;
        echo"</select>";
    }

    function cekSspGuruId($guru_id){
        include ("db_con.php");
        $query =    "SELECT * FROM ssp 
                    LEFT JOIN guru
                    ON ssp_guru_id = guru_id
                    LEFT JOIN t_ajaran
                    ON ssp_t_ajaran_id = t_ajaran_id
                    WHERE guru_id = $guru_id AND t_ajaran_active = 1";

        $result = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function cekGuruExistInMapel($guru_id){
        include ("db_con.php");
        $query =    "SELECT DISTINCT d_mapel_id_mapel, mapel_nama
                    FROM d_mapel
                    LEFT JOIN mapel
                    ON d_mapel_id_mapel = mapel_id
                    LEFT JOIN t_ajaran
                    ON mapel_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1 AND d_mapel_id_guru = $guru_id";

        $result = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function return_abjad_afek($nilai){
        if($nilai >=7.65){
            return "A";
        }elseif($nilai >=6.3){
            return "B";
        }elseif($nilai >=4.95){
            return "C";
        }else{
            return "D";
        }
    }


    function return_abjad_base4($nilai){
        if($nilai >3.25){
            return "A";
        }elseif($nilai >2.50){
            return "B";
        }elseif($nilai >1.75){
            return "C";
        }else{
            return "D";
        }
    }

    function return_info_abjad_base4(){
        echo return_alert("A>3.25  B>2.5  C>1.75", "info");
    }