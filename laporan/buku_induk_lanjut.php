<?php
    session_start();
    error_reporting(E_ALL ^ E_WARNING);
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        
        if($_POST['option_siswa']){

            include ("../includes/db_con.php");
            include ("../includes/fungsi_lib.php");
    
            
            $t_ajaran = $_POST['option_t_ajaran'];
            $kelas = $_POST['option_kelas'];

            // echo $t_ajaran."<br>";
            // echo $kelas."<br>";

            $siswa_id = explode(",",$_POST['option_siswa']);
            $siswa_id_string = $_POST['option_siswa'];

            //jika ada siswa di semester ganjil
            if(array_key_exists(0,$siswa_id)){
                $siswa_id1 = $siswa_id[0];
                //echo $siswa_id1."<br>";

                $query_siswa = "SELECT t_ajaran_kepsek_id_guru, siswa_no_induk, guru_name, siswa_nama_depan, siswa_nama_belakang, kelas_nama, t_ajaran_nama
                                FROM siswa
                                LEFT JOIN kelas
                                ON siswa_id_kelas = kelas_id
                                LEFT JOIN guru
                                ON kelas_wali_guru_id = guru_id
                                LEFT JOIN t_ajaran
                                ON kelas_t_ajaran_id = t_ajaran_id
                                WHERE siswa_id IN ($siswa_id_string)";

                $query_siswa_info = mysqli_query($conn, $query_siswa);              
                if(!$query_siswa_info){
                    die("QUERY FAILED".mysqli_error($conn));
                }
                $kepsek_id = array();
                while($row = mysqli_fetch_array($query_siswa_info)){
                    $nama_siswa = $row['siswa_nama_depan']." ".$row['siswa_nama_belakang'];
                    $kelas_nama = $row['kelas_nama'];
                    $no_induk = $row['siswa_no_induk'];
                    $t_ajaran_nama = $row['t_ajaran_nama'];
                    $wali_kelas = $row['guru_name'];
                    array_push($kepsek_id,$row['t_ajaran_kepsek_id_guru']);
                    //$kepsek_id = $row['t_ajaran_kepsek_id_guru'];
                }

                //echo $kepsek_id;
                //cari kepsek
                $query_kepsek = "SELECT guru_name
                                FROM guru
                                WHERE guru_id = $kepsek_id[0]";

                $query_kepsek_info = mysqli_query($conn, $query_kepsek);              
                if(!$query_kepsek_info){
                    die("QUERY FAILED".mysqli_error($conn));
                }
                
                while($row = mysqli_fetch_array($query_kepsek_info)){
                    $kepsek_nama = $row['guru_name'];
                }
                
                //cari nama program
                $program_nama = explode(" ", $kelas_nama);
                mysqli_query($conn, "SET group_concat_max_len=15000"); 

                //laporan nilai akhir raport
                $query_buku_besar =
                    "SELECT t_for.mapel_nama, mapel_kkm,t_afek.afektif_total,t_afek.total_bulan,
                    for_kog,mapel_persen_for,sum_kog,mapel_persen_sum,
                    for_psi,mapel_persen_for_psi,sum_psi,mapel_persen_sum_psi,
                    mapel_persen_kog, mapel_persen_psi
                    FROM
                        (SELECT mapel_id, mapel_nama,COUNT(DISTINCT kog_psi_topik_id),
                        ROUND(SUM(ROUND(kog_quiz*kog_quiz_persen/100 + kog_ass*kog_ass_persen/100 + kog_test*kog_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
                        AS for_kog,
                        ROUND(SUM(ROUND(psi_quiz*psi_quiz_persen/100 + psi_ass*psi_ass_persen/100 + psi_test*psi_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
                        AS for_psi
                        FROM kog_psi 
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        LEFT JOIN mapel
                        ON topik_mapel_id = mapel_id
                        WHERE kog_psi_siswa_id = $siswa_id1
                        GROUP BY mapel_nama
                        ORDER BY mapel_urutan) AS t_for
                    JOIN
                        (SELECT mapel_id, mapel_nama, mapel_kkm, 
                        mapel_persen_for, mapel_persen_sum, 
                        mapel_persen_for_psi, mapel_persen_sum_psi,
                        mapel_persen_kog, mapel_persen_psi,
                        ROUND((kog_uts * kog_uts_persen + kog_uas * kog_uas_persen) /100,0) as sum_kog,
                        ROUND((psi_uts * psi_uts_persen + psi_uas * psi_uas_persen) /100,0) as sum_psi
                        FROM kog_psi_ujian
                        LEFT JOIN mapel
                        ON kog_psi_ujian_mapel_id = mapel_id
                        WHERE kog_psi_ujian_siswa_id = $siswa_id1
                        GROUP BY mapel_nama
                        ORDER BY mapel_urutan) AS t_sum ON t_for.mapel_id = t_sum.mapel_id 
                    JOIN
                        (SELECT mapel_id, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                        FROM afektif
                        LEFT JOIN mapel
                        ON afektif_mapel_id = mapel_id
                        WHERE afektif_siswa_id = $siswa_id1
                        GROUP BY mapel_id
                        ORDER BY mapel_urutan)AS t_afek ON t_afek.mapel_id = t_sum.mapel_id";

                $query_buku_besar_info = mysqli_query($conn, $query_buku_besar);

                if(!$query_buku_besar_info){
                    die("QUERY FAILED".mysqli_error($conn));
                }
                $mapel_nama = array();
                $mapel_kkm = array();

                $for_kog = array();
                $mapel_persen_for = array();
                $for_psi = array();
                $mapel_persen_for_psi = array();

                $sum_kog = array();
                $mapel_persen_sum = array();
                $sum_psi = array();
                $mapel_persen_sum_psi = array();
                
                $mapel_persen_kog = array();
                $mapel_persen_psi = array();

                $afektif_total = array();
                $total_bulan = array();

                while($row = mysqli_fetch_array($query_buku_besar_info)){
                    array_push($mapel_nama,$row['mapel_nama']);
                    array_push($mapel_kkm,$row['mapel_kkm']);

                    array_push($for_kog,$row['for_kog']);
                    array_push($mapel_persen_for,$row['mapel_persen_for']);
                    array_push($for_psi,$row['for_psi']);
                    array_push($mapel_persen_for_psi,$row['mapel_persen_for_psi']);
                    
                    array_push($sum_kog,$row['sum_kog']);
                    array_push($mapel_persen_sum,$row['mapel_persen_sum']);
                    array_push($sum_psi,$row['sum_psi']);
                    array_push($mapel_persen_sum_psi,$row['mapel_persen_sum_psi']);
                    
                    array_push($mapel_persen_kog,$row['mapel_persen_kog']);
                    array_push($mapel_persen_psi,$row['mapel_persen_psi']);

                    array_push($afektif_total,$row['afektif_total']);
                    array_push($total_bulan,$row['total_bulan']);
                }

                $query_ssp = "SELECT ssp_nilai_siswa_id, ssp_nama,ssp_nilai_angka,d_ssp_kriteria,d_ssp_a,d_ssp_b,d_ssp_c, guru_name
                        FROM ssp_nilai
                        LEFT JOIN d_ssp
                        ON ssp_nilai_d_ssp_id = d_ssp_id
                        LEFT JOIN ssp
                        ON d_ssp_ssp_id = ssp_id
                        LEFT JOIN guru
                        ON ssp_guru_id = guru_id
                        WHERE ssp_nilai_siswa_id = $siswa_id1";

                $query_ssp_info = mysqli_query($conn, $query_ssp);  
                $rowss = mysqli_fetch_row($query_ssp_info);
                $nama_ssp = $rowss[1];
                
                mysqli_data_seek($query_ssp_info, 0);
                
                if(!$query_ssp_info){
                    die("QUERY FAILED".mysqli_error($conn));
                }
                $total_ssp = 0;
                $nomor_ssp = 1;
                while($row_mapel = mysqli_fetch_array($query_ssp_info)){
                    $nilai_angka_ssp = $row_mapel['ssp_nilai_angka'];
                    $total_ssp += $nilai_angka_ssp;
                    $nomor_ssp++;
                }
                $final_score = $total_ssp/($nomor_ssp - 1);

                $scout_nilai_angka = 0;
                $sql_scout = "SELECT *
                            FROM scout_nilai
                            WHERE scout_nilai_siswa_id = $siswa_id1";
                $sql_v_scout = mysqli_query($conn, $sql_scout); 
                
                while($row_scout = mysqli_fetch_array($sql_v_scout)){
                    $scout_nilai_angka = $row_scout['scout_nilai_angka'];
                }

                $sql_lifeskill = "SELECT ifnull(pf_hf_absent,0) + ifnull(pf_hf_uks,0) + ifnull(pf_hf_tardiness,0) as jumlah_pf_hf,
                            ifnull(ss_relationship,0) + ifnull(ss_cooperation,0) + ifnull(ss_conflict,0) + ifnull(ss_self_a,0) as jumlah_ss,
                            ifnull(spirit_coping,0) + ifnull(spirit_emo,0) + ifnull(spirit_grate,0) as jumlah_spirit,
                            ifnull(moral_b_lo,0) + ifnull(moral_b_so,0) as jumlah_moral_b,
                            ifnull(emo_aware_ex,0) + ifnull(emo_aware_so,0) + ifnull(emo_aware_ne,0) as jumlah_emo_aware, 
                            siswa_komen_akhir, siswa_absenin, siswa_absenex, siswa_tardy, siswa_special_note
                            FROM(
                                SELECT * FROM siswa
                                LEFT join pf_hf
                                ON siswa_id = pf_hf_siswa_id
                                LEFT join ss
                                ON siswa_id = ss_siswa_id
                                LEFT join spirit
                                ON siswa_id = spirit_siswa_id
                                LEFT join moral_b
                                ON siswa_id = moral_b_siswa_id
                                LEFT join emo_aware
                                ON siswa_id = emo_aware_siswa_id
                                WHERE siswa_id = $siswa_id1
                            ) as life_skill";
                            
                mysqli_query($conn, "SET SQL_BIG_SELECTS=1"); 
                $sql_v_lifeskill = mysqli_query($conn, $sql_lifeskill); 
                
                while($row_life = mysqli_fetch_array($sql_v_lifeskill)){
                    $jumlah_pf_hf = $row_life['jumlah_pf_hf'];
                    $jumlah_moral_b = $row_life['jumlah_moral_b'];
                    $jumlah_emo_aware = $row_life['jumlah_emo_aware'];
                    $jumlah_spirit = $row_life['jumlah_spirit'];
                    $jumlah_ss = $row_life['jumlah_ss'];
                    $siswa_komen_akhir = $row_life['siswa_komen_akhir'];
                    $siswa_absenin = $row_life['siswa_absenin'];
                    $siswa_absenex = $row_life['siswa_absenex'];
                    $siswa_tardy = $row_life['siswa_tardy'];
                    $siswa_special_note = $row_life['siswa_special_note'];
                }
                
                $jumlah_pf_hf = return_abjad_base4($jumlah_pf_hf /= 3);
                $jumlah_moral_b = return_abjad_base4($jumlah_moral_b /= 2);
                $jumlah_emo_aware = return_abjad_base4($jumlah_emo_aware /= 3);
                $jumlah_spirit = return_abjad_base4($jumlah_spirit /= 3);
                $jumlah_ss = return_abjad_base4($jumlah_ss /= 4);

                mysqli_query($conn, "SET group_concat_max_len=15000"); 
                $sql_cek_karakter = "SELECT karakter_id,karakter_nama,karakter_a,karakter_b,karakter_c,GROUP_CONCAT(mapel_id),COUNT(mapel_id) as jum_mapel,GROUP_CONCAT(mapel_nama) as mapel_nama_total, GROUP_CONCAT(total_bulan) as total_bulan_total, GROUP_CONCAT(afektif_total SEPARATOR '#')as karakter_afektif FROM 
                                    (
                                            SELECT d_karakter_mapel_id, karakter_id, karakter_urutan, karakter_nama,karakter_a,karakter_b,karakter_c  FROM `d_karakter`
                                            LEFT JOIN karakter
                                            ON d_karakter_k_id = karakter_id
                                    )AS a
                                    LEFT JOIN
                                    (
                                            SELECT mapel_id, mapel_nama, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                                            FROM afektif
                                            LEFT JOIN mapel
                                            ON afektif_mapel_id = mapel_id
                                            WHERE afektif_siswa_id = $siswa_id1
                                            GROUP BY mapel_id
                                            ORDER BY mapel_urutan
                                    )AS b
                                    ON a.d_karakter_mapel_id = b.mapel_id
                                    GROUP BY karakter_id
                                    ORDER BY karakter_urutan";
                
                $sql_karakter = mysqli_query($conn, $sql_cek_karakter); 

                $karakter_nama = array();
                $nilai_karakter = array();
                while($row_mapel = mysqli_fetch_array($sql_karakter)){
                    array_push($karakter_nama,$row_mapel['karakter_nama']);

                    $mapel_nama_kar = $row_mapel['mapel_nama_total'];
                        
                    $afektif_total_akhir = $row_mapel['karakter_afektif'];
                    
                    //array_push($nilai_karakter,$afektif_total_akhir);
                    $total_nilai_karakter = 0;
                    //echo $mapel_nama_kar."<br>";
                    $nilai_permapel = explode('#', $afektif_total_akhir);
                    for($x=0;$x<count($nilai_permapel);$x++){
                        
                        $nilai_permapel_bulan = explode('.', $nilai_permapel[$x]);
                        // for($y=0;$y<count($nilai_permapel_bulan);$y++){
                        //     echo $nilai_permapel_bulan[$y]."<br>";
                        // }
                        //echo "TOTAL NILAI: ".return_total_nilai_afektif_bulan($nilai_permapel_bulan)/count($nilai_permapel_bulan);
                        $total_nilai_karakter += return_total_nilai_afektif_bulan($nilai_permapel_bulan)/count($nilai_permapel_bulan);
                        // echo $nilai_permapel[$x].": ";
                        // echo return_total_nilai_perkarakter($nilai_permapel[$x]);
                        //echo "<br>";
                    }
                    //echo "$total_nilai_karakter"."<br>";
                    $rata_rata_karakter = $total_nilai_karakter/count($nilai_permapel);
                    //echo "$rata_rata_karakter"."<br>";

                    array_push($nilai_karakter,return_abjad_afek($rata_rata_karakter));
                }

                

            }

            if(array_key_exists(1,$siswa_id)){
                $siswa_id2 = $siswa_id[1];
                //echo $siswa_id2."<br>";
                $query_buku_besar2 =
                    "SELECT t_for.mapel_nama, mapel_kkm,t_afek.afektif_total,t_afek.total_bulan,
                    for_kog,mapel_persen_for,sum_kog,mapel_persen_sum,
                    for_psi,mapel_persen_for_psi,sum_psi,mapel_persen_sum_psi,
                    mapel_persen_kog, mapel_persen_psi
                    FROM
                        (SELECT mapel_id, mapel_nama,COUNT(DISTINCT kog_psi_topik_id),
                        ROUND(SUM(ROUND(kog_quiz*kog_quiz_persen/100 + kog_ass*kog_ass_persen/100 + kog_test*kog_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
                        AS for_kog,
                        ROUND(SUM(ROUND(psi_quiz*psi_quiz_persen/100 + psi_ass*psi_ass_persen/100 + psi_test*psi_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
                        AS for_psi
                        FROM kog_psi 
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        LEFT JOIN mapel
                        ON topik_mapel_id = mapel_id
                        WHERE kog_psi_siswa_id = $siswa_id2
                        GROUP BY mapel_nama
                        ORDER BY mapel_urutan) AS t_for
                    JOIN
                        (SELECT mapel_id, mapel_nama, mapel_kkm, 
                        mapel_persen_for, mapel_persen_sum, 
                        mapel_persen_for_psi, mapel_persen_sum_psi,
                        mapel_persen_kog, mapel_persen_psi,
                        ROUND((kog_uts * kog_uts_persen + kog_uas * kog_uas_persen) /100,0) as sum_kog,
                        ROUND((psi_uts * psi_uts_persen + psi_uas * psi_uas_persen) /100,0) as sum_psi
                        FROM kog_psi_ujian
                        LEFT JOIN mapel
                        ON kog_psi_ujian_mapel_id = mapel_id
                        WHERE kog_psi_ujian_siswa_id = $siswa_id2
                        GROUP BY mapel_nama
                        ORDER BY mapel_urutan) AS t_sum ON t_for.mapel_id = t_sum.mapel_id 
                    JOIN
                        (SELECT mapel_id, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                        FROM afektif
                        LEFT JOIN mapel
                        ON afektif_mapel_id = mapel_id
                        WHERE afektif_siswa_id = $siswa_id2
                        GROUP BY mapel_id
                        ORDER BY mapel_urutan)AS t_afek ON t_afek.mapel_id = t_sum.mapel_id";

                $query_buku_besar_info2 = mysqli_query($conn, $query_buku_besar2);

                if(!$query_buku_besar_info2){
                    die("QUERY FAILED".mysqli_error($conn));
                }
                $mapel_nama2 = array();
                $mapel_kkm2 = array();

                $for_kog2 = array();
                $mapel_persen_for2 = array();
                $for_psi2 = array();
                $mapel_persen_for_psi2 = array();

                $sum_kog2= array();
                $mapel_persen_sum2 = array();
                $sum_psi2 = array();
                $mapel_persen_sum_psi2 = array();
                
                $mapel_persen_kog2 = array();
                $mapel_persen_psi2 = array();

                $afektif_total2 = array();
                $total_bulan2 = array();

                while($row = mysqli_fetch_array($query_buku_besar_info2)){
                    array_push($mapel_nama2,$row['mapel_nama']);
                    array_push($mapel_kkm2,$row['mapel_kkm']);

                    array_push($for_kog2,$row['for_kog']);
                    array_push($mapel_persen_for2,$row['mapel_persen_for']);
                    array_push($for_psi2,$row['for_psi']);
                    array_push($mapel_persen_for_psi2,$row['mapel_persen_for_psi']);
                    
                    array_push($sum_kog2,$row['sum_kog']);
                    array_push($mapel_persen_sum2,$row['mapel_persen_sum']);
                    array_push($sum_psi2,$row['sum_psi']);
                    array_push($mapel_persen_sum_psi2,$row['mapel_persen_sum_psi']);
                    
                    array_push($mapel_persen_kog2,$row['mapel_persen_kog']);
                    array_push($mapel_persen_psi2,$row['mapel_persen_psi']);

                    array_push($afektif_total2,$row['afektif_total']);
                    array_push($total_bulan2,$row['total_bulan']);
                }

                $query_ssp2 = "SELECT ssp_nilai_siswa_id, ssp_nama,ssp_nilai_angka,d_ssp_kriteria,d_ssp_a,d_ssp_b,d_ssp_c, guru_name
                        FROM ssp_nilai
                        LEFT JOIN d_ssp
                        ON ssp_nilai_d_ssp_id = d_ssp_id
                        LEFT JOIN ssp
                        ON d_ssp_ssp_id = ssp_id
                        LEFT JOIN guru
                        ON ssp_guru_id = guru_id
                        WHERE ssp_nilai_siswa_id = $siswa_id2";

                $query_ssp_info2 = mysqli_query($conn, $query_ssp2);  
                $rowss2 = mysqli_fetch_row($query_ssp_info2);
                $nama_ssp2 = $rowss2[1];
                
                mysqli_data_seek($query_ssp_info2, 0);
                
                if(!$query_ssp_info2){
                    die("QUERY FAILED".mysqli_error($conn));
                }
                $total_ssp2 = 0;
                $nomor_ssp2 = 1;
                while($row_mape2l = mysqli_fetch_array($query_ssp_info2)){
                    $nilai_angka_ssp2 = $row_mapel2['ssp_nilai_angka'];
                    $total_ssp2 += $nilai_angka_ssp2;
                    $nomor_ssp2++;
                }
                if($nomor_ssp2!=1){
                    $final_score2 = $total_ssp2/($nomor_ssp2 - 1);
                }else{
                    $final_score2 = 0;
                }

                $scout_nilai_angka2 = 0;
                $sql_scout2 = "SELECT *
                            FROM scout_nilai
                            WHERE scout_nilai_siswa_id = $siswa_id2";
                $sql_v_scout2 = mysqli_query($conn, $sql_scout2); 
                
                while($row_scout2 = mysqli_fetch_array($sql_v_scout2)){
                    $scout_nilai_angka2 = $row_scout2['scout_nilai_angka'];
                }

                $sql_lifeskill2 = "SELECT ifnull(pf_hf_absent,0) + ifnull(pf_hf_uks,0) + ifnull(pf_hf_tardiness,0) as jumlah_pf_hf,
                            ifnull(ss_relationship,0) + ifnull(ss_cooperation,0) + ifnull(ss_conflict,0) + ifnull(ss_self_a,0) as jumlah_ss,
                            ifnull(spirit_coping,0) + ifnull(spirit_emo,0) + ifnull(spirit_grate,0) as jumlah_spirit,
                            ifnull(moral_b_lo,0) + ifnull(moral_b_so,0) as jumlah_moral_b,
                            ifnull(emo_aware_ex,0) + ifnull(emo_aware_so,0) + ifnull(emo_aware_ne,0) as jumlah_emo_aware, 
                            siswa_komen_akhir, siswa_absenin, siswa_absenex, siswa_tardy, siswa_special_note
                            FROM(
                                SELECT * FROM siswa
                                LEFT join pf_hf
                                ON siswa_id = pf_hf_siswa_id
                                LEFT join ss
                                ON siswa_id = ss_siswa_id
                                LEFT join spirit
                                ON siswa_id = spirit_siswa_id
                                LEFT join moral_b
                                ON siswa_id = moral_b_siswa_id
                                LEFT join emo_aware
                                ON siswa_id = emo_aware_siswa_id
                                WHERE siswa_id = $siswa_id2
                            ) as life_skill";
                            
                mysqli_query($conn, "SET SQL_BIG_SELECTS=1"); 
                $sql_v_lifeskill2 = mysqli_query($conn, $sql_lifeskill2); 
                
                while($row_life2 = mysqli_fetch_array($sql_v_lifeskill2)){
                    $jumlah_pf_hf2 = $row_life2['jumlah_pf_hf'];
                    $jumlah_moral_b2 = $row_life2['jumlah_moral_b'];
                    $jumlah_emo_aware2 = $row_life2['jumlah_emo_aware'];
                    $jumlah_spirit2 = $row_life2['jumlah_spirit'];
                    $jumlah_ss2 = $row_life2['jumlah_ss'];
                    $siswa_komen_akhir2 = $row_life2['siswa_komen_akhir'];
                    $siswa_absenin2 = $row_life2['siswa_absenin'];
                    $siswa_absenex2 = $row_life2['siswa_absenex'];
                    $siswa_tardy2 = $row_life2['siswa_tardy'];
                    $siswa_special_note2 = $row_life2['siswa_special_note'];
                }
                
                $jumlah_pf_hf2 = return_abjad_base4($jumlah_pf_hf2 /= 3);
                $jumlah_moral_b2 = return_abjad_base4($jumlah_moral_b2 /= 2);
                $jumlah_emo_aware2 = return_abjad_base4($jumlah_emo_aware2 /= 3);
                $jumlah_spirit2 = return_abjad_base4($jumlah_spirit2 /= 3);
                $jumlah_ss2 = return_abjad_base4($jumlah_ss2 /= 4);

                mysqli_query($conn, "SET group_concat_max_len=15000"); 
                $sql_cek_karakter2 = "SELECT karakter_id,karakter_nama,karakter_a,karakter_b,karakter_c,GROUP_CONCAT(mapel_id),COUNT(mapel_id) as jum_mapel,GROUP_CONCAT(mapel_nama) as mapel_nama_total, GROUP_CONCAT(total_bulan) as total_bulan_total, GROUP_CONCAT(afektif_total SEPARATOR '#')as karakter_afektif FROM 
                                    (
                                            SELECT d_karakter_mapel_id, karakter_id, karakter_urutan, karakter_nama,karakter_a,karakter_b,karakter_c  FROM `d_karakter`
                                            LEFT JOIN karakter
                                            ON d_karakter_k_id = karakter_id
                                    )AS a
                                    LEFT JOIN
                                    (
                                            SELECT mapel_id, mapel_nama, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                                            FROM afektif
                                            LEFT JOIN mapel
                                            ON afektif_mapel_id = mapel_id
                                            WHERE afektif_siswa_id = $siswa_id2
                                            GROUP BY mapel_id
                                            ORDER BY mapel_urutan
                                    )AS b
                                    ON a.d_karakter_mapel_id = b.mapel_id
                                    GROUP BY karakter_id
                                    ORDER BY karakter_urutan";
                
                $sql_karakter2 = mysqli_query($conn, $sql_cek_karakter2); 

                $karakter_nama2 = array();
                $nilai_karakter2 = array();
                while($row_mapel2 = mysqli_fetch_array($sql_karakter2)){
                    array_push($karakter_nama2,$row_mapel2['karakter_nama']);

                    $mapel_nama_kar2 = $row_mapel2['mapel_nama_total'];
                        
                    $afektif_total_akhir2 = $row_mapel2['karakter_afektif'];
                    
                    $total_nilai_karakter2 = 0;
                    $nilai_permapel2 = explode('#', $afektif_total_akhir2);
                    for($x=0;$x<count($nilai_permapel2);$x++){
                        
                        $nilai_permapel_bulan2 = explode('.', $nilai_permapel2[$x]);
                        $total_nilai_karakter2 += return_total_nilai_afektif_bulan($nilai_permapel_bulan2)/count($nilai_permapel_bulan2);
                    }
                    $rata_rata_karakter2 = $total_nilai_karakter2/count($nilai_permapel2);
                    array_push($nilai_karakter2,return_abjad_afek($rata_rata_karakter2));
                }
            }
            echo "<div id='print_area'>";
            echo "<h6 class='text-center mb-3 mt-5'>LAPORAN PENILAIAN HASIL BELAJAR SISWA</h6>";

            echo"<div id='textbox'>
                <p class='alignleft_induk'>
                    NAMA PESERTA DIDIK &emsp;:&nbsp$nama_siswa<br>
                    NOMOR INDUK &emsp;&emsp;&thinsp;&nbsp&nbsp&nbsp&nbsp&emsp;&thinsp;:&nbsp$no_induk
                </p>
                <p class='alignright'>
                    NISN &nbsp&nbsp&nbsp&emsp;&thinsp;&emsp;: <br>
                    PROGRAM ST $program_nama[1]";
            echo"</p>
                </div>";
            echo"<div style='clear: both;'></div>";
            
            echo "<table class='induk mt-3'>
                    <tr>
                    <th rowspan='5'>No</th>
                    <th>Tahun Pelajaran</th>
                    <th colspan='5'>$t_ajaran_nama</th>
                    <th colspan='5'>$t_ajaran_nama</th>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Kelas&nbsp</td>
                    <td colspan='5' style='text-align: center;'>{$program_nama[0]}</td>
                    <td colspan='5' style='text-align: center;'>{$program_nama[0]}</td>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Semester&nbsp</td>
                    <td colspan='5' style='text-align: center;'>1</td>
                    <td colspan='5' style='text-align: center;'>2</td>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Nilai&nbsp</td>
                    <td style='text-align: center;' rowspan='2'>KKM</td>
                    <td style='text-align: center;'>Penge<br>tahuan</td>
                    <td style='text-align: center;'>Ketram<br>pilan<br></td>
                    <td style='text-align: center;'>Final</td>
                    <td style='text-align: center;'>Sikap</td>
                    <td style='text-align: center;' rowspan='2'>KKM</td>
                    <td style='text-align: center;'>Penge<br>tahuan</td>
                    <td style='text-align: center;'>Ketram<br>pilan</td>
                    <td style='text-align: center;'>Final</td>
                    <td style='text-align: center;'>Sikap</td>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Mata Pelajaran&nbsp</td>
                    <td style='text-align: center;'>Angka</td>
                    <td style='text-align: center;'>Angka</td>
                    <td style='text-align: center;'>Angka</td>
                    <td style='text-align: center;'>Predikat</td>
                    <td style='text-align: center;'>Angka</td>
                    <td style='text-align: center;'>Angka</td>
                    <td style='text-align: center;'>Angka</td>
                    <td style='text-align: center;'>Predikat</td>
                    </tr>";
                
                $no_mapel = 1;
                for($j=0;$j<count($mapel_nama);$j++){
                    echo "<tr'>";
                        echo "<td style='text-align: center; height: 10px;'>".$no_mapel."</td>";
                        echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>{$mapel_nama[$j]}</td>";
                        echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$mapel_kkm[$j]}</td>";
                        //pengetahuan
                        $kognitif = round($for_kog[$j] * $mapel_persen_for[$j] + $sum_kog[$j] * $mapel_persen_sum[$j]);
                        echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$kognitif}</td>";
                        //ketrampilan
                        $psikomotor = round($for_psi[$j] * $mapel_persen_for_psi[$j] + $sum_psi[$j] * $mapel_persen_sum_psi[$j]);
                        echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$psikomotor}</td>";
                        $n_akhir = round($kognitif * $mapel_persen_kog[$j] + $psikomotor * $mapel_persen_psi[$j]);
                        echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$n_akhir}</td>";
                        //afektif
                        $nilai_perbulan = explode('.', $afektif_total[$j]);
                        echo"<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>".return_abjad_afek(return_total_nilai_afektif_bulan($nilai_perbulan)/$total_bulan[$j])."</td>";

                        if(count($mapel_nama)==count($mapel_nama2)){
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$mapel_kkm2[$j]}</td>";
                            //pengetahuan
                            $kogniti2 = round($for_kog2[$j] * $mapel_persen_for2[$j] + $sum_kog2[$j] * $mapel_persen_sum2[$j]);
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$kognitif2}</td>";
                            //ketrampilan
                            $psikomotor2 = round($for_psi2[$j] * $mapel_persen_for_psi2[$j] + $sum_psi2[$j] * $mapel_persen_sum_psi2[$j]);
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$psikomotor2}</td>";
                            $n_akhir2 = round($kognitif2 * $mapel_persen_kog2[$j] + $psikomotor2 * $mapel_persen_psi2[$j]);
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>{$n_akhir}</td>";
                            //afektif
                            $nilai_perbulan2 = explode('.', $afektif_total2[$j]);
                            echo"<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>".return_abjad_afek(return_total_nilai_afektif_bulan($nilai_perbulan2)/$total_bulan2[$j])."</td>";
                        }
                        else{
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>-</td>";
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>-</td>";
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>-</td>";
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>-</td>";
                            echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: center;'>-</td>";
                        }
                    echo "</tr>";
                    $no_mapel+=1;
                }
                echo "<tr>";
                echo "<td colspan='2' style='text-align: center;'>Pengembangan Diri</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='2' style='padding: 0px 0px 0px 5px; text-align: left;'>Extrakurikuler</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                echo "</tr>";
                //ssp utama dan pramuka
                echo "<tr>";
                echo "<td style='padding: 0px 0px 0px 5px; text-align: left; text-align: left;'>1</td>";
                echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: left;'>$nama_ssp</td>
                        <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>".return_abjad_base4($final_score)."</td>
                        <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>".return_abjad_base4($final_score2)."</td>";
                echo "</tr>";
                if(return_abjad_base4($scout_nilai_angka) != "D"){
                    echo "<tr>";
                    echo "<td style='padding: 0px 0px 0px 5px; text-align: left; text-align: left;'>2</td>";
                    echo "<td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: left;'>PRAMUKA (SCOUT)</td>
                            <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>".return_abjad_base4($scout_nilai_angka)."</td>
                            <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>".return_abjad_base4($scout_nilai_angka2)."</td>";
                    echo "</tr>";
                }
                //organisasi sekolah
                echo "<tr>";
                echo "<td colspan='2' style='padding: 0px 0px 0px 5px; text-align: left;'>Keikutsertaan Dalam Organisasi/Kegiatan Sekolah</td><td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'></td><td colspan='5'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='2' style='padding: 0px 0px 0px 5px; text-align: left;'>Ketidakhadiran</td><td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'></td><td colspan='5'></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='padding: 0px 0px 0px 5px; text-align: left;'>1</td>
                    <td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: left;'>SAKIT</td>
                    <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>$siswa_tardy</td>
                    <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>$siswa_tardy2</td>"; 
                echo "</tr>";

                echo "<tr>";
                    echo "<td style='padding: 0px 0px 0px 5px; text-align: left;'>2</td>
                    <td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: left;'>IJIN</td>
                    <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>$siswa_absenin</td>
                    <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>$siswa_absenin2</td>";
                echo "</tr>";

                echo "<tr>";
                    echo "<td style='padding: 0px 0px 0px 5px; text-align: left;'>3</td>
                    <td style='padding: 0px 0px 0px 5px; font-size: 10px !important; text-align: left;'>TANPA KETERANGAN</td>
                    <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>$siswa_absenex</td>
                    <td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'>$siswa_absenex2</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td colspan='2' style='padding: 0px 0px 0px 5px; text-align: left;'>Akhlak Mulia dan Kepribadian</td><td colspan='5' style='padding: 0px 0px 0px 5px; text-align: center; font-size: 10px !important;'></td><td colspan='5'></td>";
                echo "</tr>";

                $no_karakter = 1;
                for($k=0;$k<count($karakter_nama);$k++){
                    echo "<tr>";
                    echo "<td style='text-align: left; padding: 0px 0px 0px 5px;'>$no_karakter</td>
                        <td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>".strtoupper($karakter_nama[$k])."</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$nilai_karakter[$k]</td>";
                    if($nilai_karakter2[$k]){
                        echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$nilai_karakter2[$k]</td>";
                    }
                    echo "</tr>";
                    $no_karakter++;
                }

                echo "<tr>";
                    echo "<td style='text-align: left; padding: 0px 0px 0px 5px;'>6</td><td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>".strtoupper("Physical Fitness and Healthful Habit")."</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_pf_hf</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_pf_hf2</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align: left; padding: 0px 0px 0px 5px;'>7</td><td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>".strtoupper("Moral Behavior")."</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_moral_b</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_moral_b2</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align: left; padding: 0px 0px 0px 5px;'>8</td><td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>".strtoupper("Emotional Awareness")."</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_emo_aware</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_emo_aware2</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align: left; padding: 0px 0px 0px 5px;'>9</td><td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>".strtoupper("Spirituality")."</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_spirit</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_spirit2</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align: left; padding: 0px 0px 0px 5px;'>10</td><td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>".strtoupper("Social Skill")."</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_ss</td>";
                    echo "<td colspan='5' style='text-align: center; font-size: 10px !important;'>$jumlah_ss2</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td colspan='2' style='text-align: center; padding: 10px 10px 10px 5px; font-size: 15px !important;'><b>".strtoupper("status akhir tahun")."</b></td>";
                    echo "<td colspan='10' style='text-align: left; padding: 0px 0px 0px 35px; font-size: 12px !important;'><u>&nbsp&nbsp&nbsp&nbspNaik  ke&nbsp&nbsp&nbsp&nbsp</u><br>&nbspTinggal di</td>";
                echo "</tr>";
            echo "</table>";        

            echo"<div id='textbox'>
                <p class='alignleft_bawah_induk'>
                <br>Mengetahui,<br>
                Kepala Sekolah<br><br><br><br>
                $kepsek_nama
                </p>
                <p class='alignright_bawah'>
                <br>Surabaya,<br>Wali Kelas<br><br><br><br>
                $wali_kelas
                </p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            echo '<p style="page-break-after: always;">&nbsp;</p>';

            echo "</div>";

            echo'<input type="button" name="print_dkn" id="print_dkn" class="btn btn-primary print_dkn mt-2" value="Print">';

            echo'<input type="button" name="export_dkn" id="export_dkn" class="btn btn-success export_dkn mt-2 ml-2" value="Export To Excel">';
        }
    }
    
?>

<script>
$(document).ready(function(){
    $("#print_dkn").click(function(){
        $('#print_area').printThis({
            printDelay: 2000,
            importCSS: true,
            importStyle: true,
            loadCSS: "http://localhost/acpa/CSS/customCSS_preview.css"
        });
    });   

    $("#export_dkn").click(function (e) {
        //alert("hai");
        window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#print_area').html()));
        e.preventDefault();
    });
});
</script>