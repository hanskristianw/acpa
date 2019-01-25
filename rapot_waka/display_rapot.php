<?php
    //RAPOT WAKA
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");   
    include ("../includes/fungsi_lib.php"); 
    
    if(!empty($_POST["check_siswa_id"])) {
        $kelas_id = $_POST["option_kelas"];
        //$siswa_id = $_POST["option_siswa"];
        $s_id = $_POST["check_siswa_id"];

        for($z=0;$z<count($s_id);$z++){
            $query_mapel = "SELECT *
                            FROM siswa
                            LEFT JOIN kelas
                            ON siswa_id_kelas = kelas_id
                            LEFT JOIN t_ajaran
                            ON kelas_t_ajaran_id = t_ajaran_id
                            LEFT JOIN guru
                            ON t_ajaran_kepsek_id_guru = guru_id
                            WHERE siswa_id = {$s_id[$z]}";

            $query_mapel_info = mysqli_query($conn, $query_mapel);        
            if(!$query_mapel_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row_mapel = mysqli_fetch_array($query_mapel_info)){
                $siswa_nama_d = $row_mapel['siswa_nama_depan'];
                $siswa_nama_lengkap = $row_mapel['siswa_nama_depan'].' '.$row_mapel['siswa_nama_belakang'];
                $siswa_no_induk = $row_mapel['siswa_no_induk'];
                $kelas_nama = $row_mapel['kelas_nama'];
                $tahun_ajaran_nama = $row_mapel['t_ajaran_nama']; 
                $tahun_ajaran_semester = $row_mapel['t_ajaran_semester']; 
                $tahun_ajaran_nama_kepsek = $row_mapel['guru_name']; 
                $tahun_ajaran_tanggal_rapot = $row_mapel['t_ajaran_tanggal_rapot']; 
            }
            if($tahun_ajaran_semester == '1'){
                $semester_inggris = '(Odd)';
            }
            else{
                $semester_inggris = '(Even)';
            }
            
            $program_nama = explode(" ", $kelas_nama);
            
            
            //////////////////////////////////////////////////////////////////HALAMAN 1////////////////////////////////////////////////////////////
            echo"<p class='judul'>ACADEMIC ACHIEVEMENT<br>Nation Star Academy Senior High School</p>";
            
            echo"<div id='textbox'>
                <p class='alignleft'>
                NAME &nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_nama_lengkap<br>
                ID NUMBER &nbsp&nbsp&emsp;:&nbsp$siswa_no_induk<br>
                CLASS &nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;&thinsp;:&nbsp$kelas_nama<br>
                </p>
                <p class='alignright'>
                SEMESTER &nbsp&nbsp&nbsp&emsp;&thinsp;&emsp;: $tahun_ajaran_semester $semester_inggris<br>
                SCHOOL YEAR &nbsp&nbsp&nbsp&nbsp&nbsp&thinsp;: $tahun_ajaran_nama<br>";
                if(strlen($program_nama[1])>1){
                    echo"PROGRAM &nbsp&nbsp&emsp;&emsp;&thinsp;&thinsp;&thinsp;: $program_nama[1]<br>";
                }
                else{
                    echo"<br>";
                }
            echo"</p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            
            $query =    "SELECT t_for.mapel_nama, mapel_kkm,t_afek.afektif_total,t_afek.total_bulan,
                        ROUND((for_kog * mapel_persen_for + sum_kog * mapel_persen_sum),0) as Cognitive, 
                        ROUND((for_psi * mapel_persen_for_psi + sum_psi * mapel_persen_sum_psi),0) as Psychomotor,
                        ROUND((ROUND((for_kog * mapel_persen_for + sum_kog * mapel_persen_sum),0)*mapel_persen_kog + ROUND((for_psi * mapel_persen_for_psi + sum_psi * mapel_persen_sum_psi),0)*mapel_persen_psi),0) AS n_akhir
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
                            WHERE kog_psi_siswa_id = {$s_id[$z]}
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
                            WHERE kog_psi_ujian_siswa_id = {$s_id[$z]}
                            GROUP BY mapel_nama
                            ORDER BY mapel_urutan) AS t_sum ON t_for.mapel_id = t_sum.mapel_id 
                        JOIN
                            (SELECT mapel_id, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                            FROM afektif
                            LEFT JOIN mapel
                            ON afektif_mapel_id = mapel_id
                            WHERE afektif_siswa_id = {$s_id[$z]}
                            GROUP BY mapel_id
                            ORDER BY mapel_urutan)AS t_afek ON t_afek.mapel_id = t_sum.mapel_id";

                //echo $query;
            $query_k_afektif_info = mysqli_query($conn, $query);

            if(!$query_k_afektif_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            
            $nomor =1;
            
            //TABEL
            echo"
            <table class='rapot'>
                <thead>
                    <tr>
                    <th rowspan='2'>NO.</th>
                    <th rowspan='2'>SUBJECT</th>
                    <th rowspan='2'>PASSING <br>GRADE</th>
                    <th colspan='5'>ACHIEVEMENT REPORT</th>
                    </tr>
                    <tr>
                    <th>Cognitive</th>
                    <th>Psychomotor</th>
                    <th>Affective</th>
                    <th>Final <br>Score</th>
                    <th>Grading</th>
                    </tr>
                </thead>
                <tbody>
                    ";
            $total = [];
            $pembagi_afektif = [];
            //nomor, subject, passing grade     
            while($row = mysqli_fetch_array($query_k_afektif_info)){
                $total[$nomor] = 0;
                $pembagi_afektif[$nomor] = 0;
                $cek_minggu_aktif = 0;
                echo"<tr>";
                echo"<td class='nomor'>$nomor</td>";
                
                //pisahkan nama subject jika terlalu panjang
                $mapel_nama_fix ="";

                if(strlen($row['mapel_nama'])< 17){
                    echo"<td>&nbsp{$row['mapel_nama']}</td>";
                }else{
                    $temp_mapel_nama = explode(" ", $row['mapel_nama']);
                    if(sizeof($temp_mapel_nama)>2){
                        for($i=0;$i<sizeof($temp_mapel_nama);$i++){
                            $mapel_nama_fix .= $temp_mapel_nama[$i] ." ";
                            if($i==1){
                                $mapel_nama_fix .= "<br>&nbsp";
                            }
                        }
                        echo"<td>&nbsp$mapel_nama_fix</td>";
                    }
                    else{
                        echo"<td>&nbsp{$row['mapel_nama']}</td>";
                    }
                }

                
                
                echo"<td class='kkm'>{$row['mapel_kkm']}</td>";
                echo"<td class='biasa'>{$row['Cognitive']}</td>";
                echo"<td class='biasa'>{$row['Psychomotor']}</td>";
                
                //hitung afektif
                
                $afektif_total = $row['afektif_total'];
                $total_bulan = $row['total_bulan'];
                $nilai_perbulan = explode('.', $afektif_total);

                echo"<td class='biasa'>".return_abjad_afek(return_total_nilai_afektif_bulan($nilai_perbulan)/$total_bulan)."</td>";

                echo"<td class='biasa'>{$row['n_akhir']}</td>";
                
                
                $mkkm = $row['mapel_kkm'];
                $nakhir = $row['n_akhir'];
                $grading_akhir = $nakhir - $mkkm;
                if($grading_akhir >16){
                    echo"<td class='biasa'>EXCELLENT</td>";
                }elseif($grading_akhir >=11){
                    echo"<td class='biasa'>GOOD</td>";
                }elseif($grading_akhir >=0){
                    echo"<td class='biasa'>SATISFACTORY</td>";
                }elseif($grading_akhir >=-10){
                    echo"<td class='biasa'>UNSATISFACTORY</td>";
                }else{
                    echo"<td class='biasa'>POOR</td>";
                }
                
                echo"</tr>";
                $nomor++;
            }
                
            echo"
                </tbody>
            </table>";
            
            $query_mapel = "SELECT guru_name
                            FROM kelas
                            LEFT JOIN guru
                            ON kelas_wali_guru_id = guru_id
                            WHERE kelas_id = {$kelas_id}";

            $query_mapel_info = mysqli_query($conn, $query_mapel);        
            if(!$query_mapel_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row_mapel = mysqli_fetch_array($query_mapel_info)){
                $wali_kelas = $row_mapel['guru_name'];
            } 
            
            $tanggal_arr = explode('-', $tahun_ajaran_tanggal_rapot);
            
            if($tanggal_arr[1] == '1'){
                $bulan = 'January';
            }elseif($tanggal_arr[1] == '2'){
                $bulan = 'February';
            }elseif($tanggal_arr[1] == '3'){
                $bulan = 'March';
            }elseif($tanggal_arr[1] == '4'){
                $bulan = 'April';
            }elseif($tanggal_arr[1] == '5'){
                $bulan = 'May';
            }elseif($tanggal_arr[1] == '6'){
                $bulan = 'June';
            }elseif($tanggal_arr[1] == '7'){
                $bulan = 'July';
            }elseif($tanggal_arr[1] == '8'){
                $bulan = 'August';
            }elseif($tanggal_arr[1] == '9'){
                $bulan = 'September';
            }elseif($tanggal_arr[1] == '10'){
                $bulan = 'October';
            }elseif($tanggal_arr[1] == '11'){
                $bulan = 'November';
            }elseif($tanggal_arr[1] == '12'){
                $bulan = 'December';
            }else{
                $bulan = '';
            }
            
            echo"<div id='textbox'>
                <p class='alignleft_bawah'>
                <br>Acknowledged by<br>
                Parents / Guardian<br><br><br><br>
                ............................................
                </p>
                <p class='alignright_bawah'>
                <br>Surabaya, $bulan $tanggal_arr[2], $tanggal_arr[0]<br>
                Homeroom Teacher<br><br><br><br>
                <b>$wali_kelas</b><br>
                </p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            
            echo"<p class='aligncenter_bawah'>Acknowleged by<br>Principal<br><br><br><br><b>$tahun_ajaran_nama_kepsek</b></p>";
            
            ////////////////////////////////HALAMAN RAPOR SSP//////////////////////////////////////
            echo '<p style="page-break-after: always;">&nbsp;</p>';
            $query_ssp = "SELECT ssp_nilai_siswa_id, ssp_nama,ssp_nilai_angka,d_ssp_kriteria,d_ssp_a,d_ssp_b,d_ssp_c, guru_name
                        FROM ssp_nilai
                        LEFT JOIN d_ssp
                        ON ssp_nilai_d_ssp_id = d_ssp_id
                        LEFT JOIN ssp
                        ON d_ssp_ssp_id = ssp_id
                        LEFT JOIN guru
                        ON ssp_guru_id = guru_id
                        WHERE ssp_nilai_siswa_id = {$s_id[$z]}";

            $query_ssp_info = mysqli_query($conn, $query_ssp);  
            $rowss = mysqli_fetch_row($query_ssp_info);
            $nama_ssp = $rowss[1];
            $guru_ssp = $rowss[7];
            
            mysqli_data_seek($query_ssp_info, 0);
            
            if(!$query_ssp_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            
            echo "<p class='judul'>SHARPENING STUDENT&#39;S POTENTIAL</p>";
            echo"<div id='textbox'>
                <p class='alignleft'>
                NAME &nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_nama_lengkap<br>
                ID NUMBER &nbsp&nbsp&emsp;:&nbsp$siswa_no_induk<br>
                CLASS &nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;&thinsp;:&nbsp$kelas_nama<br>
                </p>
                <p class='alignright'>
                SEMESTER &nbsp&nbsp&nbsp&emsp;&thinsp;&emsp;: $tahun_ajaran_semester $semester_inggris<br>
                SCHOOL YEAR &nbsp&nbsp&nbsp&nbsp&nbsp&thinsp;: $tahun_ajaran_nama<br>
                SSP &nbsp&nbsp&emsp;&emsp;&emsp;&emsp;&thinsp;&thinsp;&emsp;&thinsp;&thinsp;: $nama_ssp<br>
                </p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            
            echo"<br>
            <table class='rapot'>
                <thead>
                    <tr>
                    <th style='width: 35px; padding: 0px 0px 0px 0px;'>NO </th>
                    <th style='width: 200px;'>CRITERIA</th>
                    <th style='width: 50px;'>GRADE</th>
                    <th style='width: 350px;'>DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    ";
            
            $nomor_ssp = 1;
            $total_ssp = 0;
            while($row_mapel = mysqli_fetch_array($query_ssp_info)){
                echo"<tr>";
                    echo"<td style='text-align: center;'>$nomor_ssp</td>";
                    echo"<td style='width: 350px; padding: 0px 0px 0px 5px;'>{$row_mapel['d_ssp_kriteria']}</td>";
                    $nilai_angka_ssp = $row_mapel['ssp_nilai_angka'];
                    
                    $total_ssp += $nilai_angka_ssp;

                echo"<td style='width: 50px; padding: 0px 5px 0px 5px; text-align: center;'>".return_abjad_base4($nilai_angka_ssp)."</td>";
       
                if(return_abjad_base4($nilai_angka_ssp)=="A"){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['d_ssp_a']}</td>";
                }elseif(return_abjad_base4($nilai_angka_ssp)=="B"){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['d_ssp_b']}</td>";
                }elseif(return_abjad_base4($nilai_angka_ssp)=="C"){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['d_ssp_c']}</td>";
                }else{
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>-</td>";
                }
                $nomor_ssp++;
                echo"</tr>";
            } 
            
            
            $final_score = $total_ssp/($nomor_ssp - 1);
            
            echo"<tr>
                <td style='text-align: center; font-weight:bold; height: 50px;' colspan='2'>FINAL SCORE</td>
                <td style='text-align: center; font-weight:bold; height: 50px;' colspan='2'>".return_abjad_base4($final_score)."</td>
            </tr>";
            echo"
                </tbody>
            </table>";
            
            echo"<div id='textbox'>
                <p class='alignright_bawah'>
                <br>Surabaya, $bulan $tanggal_arr[2], $tanggal_arr[0]<br>
                SSP Teacher<br><br><br><br>
                <b>$guru_ssp</b><br>
                </p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            
            // ///////////////////////////////////////////////HALAMAN CHARACTER BUILDING///////////////////////////////
            echo '<p style="page-break-after: always;">&nbsp;</p>';
            $query_ce ="SELECT ce_nilai_siswa_id, SUM(ce_nilai_angka)as total_nilai_tema, COUNT(ce_id) as jumlah_indikator, ce_aspek, ce_a, ce_b, ce_c
                        FROM ce_nilai
                        LEFT JOIN d_ce
                        ON ce_nilai_d_ce_id = d_ce_id
                        LEFT JOIN ce
                        ON d_ce_ce_id = ce_id
                        WHERE ce_nilai_siswa_id = {$s_id[$z]}
                        GROUP BY ce_id
                        ORDER BY ce_id";

            $query_ce_info = mysqli_query($conn, $query_ce);  
            $rowss = mysqli_fetch_row($query_ce_info);
            
            mysqli_data_seek($query_ce_info, 0);
            
            if(!$query_ce_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            
            echo "<p class='judul'>CHARACTER BUILDING</p>";
            echo"<div id='textbox'>
                <p class='alignleft'>
                NAME &nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_nama_lengkap<br>
                ID NUMBER &nbsp&nbsp&emsp;:&nbsp$siswa_no_induk<br>
                CLASS &nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;&thinsp;:&nbsp$kelas_nama<br>
                </p>
                <p class='alignright'>
                SEMESTER &nbsp&nbsp&nbsp&emsp;&thinsp;&emsp;: $tahun_ajaran_semester $semester_inggris<br>
                SCHOOL YEAR &nbsp&nbsp&nbsp&nbsp&nbsp&thinsp;: $tahun_ajaran_nama<br>";
                if(strlen($program_nama[1])>1){
                    echo"PROGRAM &nbsp&nbsp&emsp;&emsp;&thinsp;&thinsp;&thinsp;: $program_nama[1]<br>";
                }
                else{
                    echo"<br>";
                }
            echo"</p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            
            echo"<br>
            <table class='rapot'>
                <thead>
                    <tr>
                    <th style='width: 35px; padding: 0px 0px 0px 0px;'>NO </th>
                    <th style='width: 200px;'>TOPIC</th>
                    <th style='width: 50px;'>GRADE</th>
                    <th style='width: 350px;'>DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    ";
            
            $nomor_aspek = 1;
            $total_aspek = 0;
            while($row_mapel = mysqli_fetch_array($query_ce_info)){
                echo"<tr>";
                    echo"<td style='text-align: center;'>$nomor_aspek</td>";
                    echo"<td style='width: 350px; padding: 0px 0px 0px 5px;'>{$row_mapel['ce_aspek']}</td>";
                    $nilai_angka_aspek = $row_mapel['total_nilai_tema'];
                    $jumlah_indikator = $row_mapel['jumlah_indikator'];

                    $total_aspek += $nilai_angka_aspek/$jumlah_indikator;
                    
                echo"<td style='width: 50px; padding: 0px 5px 0px 5px; text-align: center;'>".return_abjad_base4($nilai_angka_aspek/$jumlah_indikator)."</td>";
 
                
                if(return_abjad_base4($nilai_angka_aspek/$jumlah_indikator) == "A"){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['ce_a']}</td>";
                }elseif(return_abjad_base4($nilai_angka_aspek/$jumlah_indikator) == "B"){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['ce_b']}</td>";
                }elseif(return_abjad_base4($nilai_angka_aspek/$jumlah_indikator) == "C"){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['ce_c']}</td>";
                }else{
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>-</td>";
                }
                $nomor_aspek++;
                echo"</tr>";
            } 
            
            if($nomor_aspek != 1){
                $final_score_bk = $total_aspek/($nomor_aspek - 1);
            }
            else{
                $final_score_bk = 0;
            }
            
            echo"<tr>
                <td style='text-align: center; font-weight:bold; height: 50px;' colspan='2'>FINAL SCORE</td>
                <td style='text-align: center; font-weight:bold; height: 50px;' colspan='2'>".return_abjad_base4($final_score_bk)."</td>
            </tr>";
            echo"
                </tbody>
            </table>";
            
            $query_bk =     "SELECT *
                            FROM t_ajaran
                            LEFT JOIN guru
                            ON t_ajaran_bk_id_guru = guru_id
                            WHERE t_ajaran_active = 1";

            $query_bk_info = mysqli_query($conn, $query_bk);        
            if(!$query_bk_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row_bk = mysqli_fetch_array($query_bk_info)){
                $tahun_ajaran_nama_bk = $row_bk['guru_name']; 
            }
            
            echo"<div id='textbox'>
                <p class='alignright_bawah'>
                <br>Surabaya, $bulan $tanggal_arr[2], $tanggal_arr[0]<br>
                CB Teacher<br><br><br><br>
                <b>$tahun_ajaran_nama_bk</b><br>
                </p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            
            // //////////////////////////////////////////////////////////////////HALAMAN RANGKUMAN////////////////////////////////////////////////////////////
            echo '<p style="page-break-after: always;">&nbsp;</p>';
            echo"<div id='textbox'>
                <p class='alignleft'>
                NAME &nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_nama_lengkap<br>
                ID NUMBER &nbsp&nbsp&emsp;:&nbsp$siswa_no_induk<br>
                CLASS &nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;&thinsp;:&nbsp$kelas_nama<br>
                </p>
                <p class='alignright'>
                SEMESTER &nbsp&nbsp&nbsp&emsp;&thinsp;&emsp;: $tahun_ajaran_semester $semester_inggris<br>
                SCHOOL YEAR &nbsp&nbsp&nbsp&nbsp&nbsp&thinsp;: $tahun_ajaran_nama<br>";
            if(strlen($program_nama[1])>1){
                echo"PROGRAM &nbsp&nbsp&emsp;&emsp;&thinsp;&thinsp;&thinsp;: $program_nama[1]<br>";
            }
            else{
                echo"<br>";
            }
            echo"</p>
            </div>";
            echo"<div style='clear: both;'></div>";
            

            mysqli_query($conn, "SET group_concat_max_len=15000"); 
            $sql_cek_karakter = "SELECT karakter_id,karakter_nama,karakter_a,karakter_b,karakter_c,GROUP_CONCAT(mapel_id),GROUP_CONCAT(mapel_nama) as mapel_nama_total, GROUP_CONCAT(total_bulan) as total_bulan_total, GROUP_CONCAT(afektif_total SEPARATOR '#')as karakter_afektif FROM 
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
                                        WHERE afektif_siswa_id = {$s_id[$z]}
                                        GROUP BY mapel_id
                                        ORDER BY mapel_urutan
                                )AS b
                                ON a.d_karakter_mapel_id = b.mapel_id
                                GROUP BY karakter_id
                                ORDER BY karakter_urutan";
            
            $sql_karakter = mysqli_query($conn, $sql_cek_karakter); 
            
            echo"
            <table style='table-layout:fixed;' class='rapot_rangkuman'>
                <thead>
                    <tr>
                    <th style='width: 20px; height: 15px; padding: 0px 0px 0px 0px;'>NO </th>
                    <th style='width: 150px; height: 15px; padding: 0px 0px 0px 5px;'>AFFECTIVE</th>
                    <th style='width: 350px; height: 15px;'>DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    ";
                    
            $nomor = 1;
            while($row_mapel = mysqli_fetch_array($sql_karakter)){
            
                echo"<tr>";
                    echo"<td style='text-align: center;'>$nomor</td>";
                    echo"<td>{$row_mapel['karakter_nama']}</td>";
                    
                    $afektif_total_akhir = $row_mapel['karakter_afektif'];
                    //$total_bulan_total = $row_mapel['total_bulan_total'];
                    //$mapel_nama_total = $row_mapel['mapel_nama_total'];
                    echo"<td style='padding: 5px 5px 5px 5px;'>";
                    //ulang sebanyak jumlah mapel
                    //$mapel_nama = explode(',', $mapel_nama_total);

                    //echo $afektif_total_akhir;
                    $nilai_permapel = explode('#', $afektif_total_akhir);
                    
                    if(return_abjad_afek(return_total_nilai_perkarakter($nilai_permapel))=="A"){
                        echo ucfirst(strtolower($siswa_nama_d)).' '.$row_mapel['karakter_a'];
                    }elseif(return_abjad_afek(return_total_nilai_perkarakter($nilai_permapel))=="B"){
                        echo ucfirst(strtolower($siswa_nama_d)).' '.$row_mapel['karakter_b'];
                    }elseif(return_abjad_afek(return_total_nilai_perkarakter($nilai_permapel))=="C"){
                        echo ucfirst(strtolower($siswa_nama_d)).' '.$row_mapel['karakter_c'];
                    }else{
                        echo "-";
                    }

                    echo "</td>";
                    $nomor++;
                echo"</tr>";
            }
            echo"
                </tbody>
            </table>";
            // $setupMySql = mysqli_query($conn, "SET OPTION SQL_BIG_SELECTS = 1") 
            //             or die('Cannot complete SETUP BIG SELECTS because: ' . mysqli_error($conn));


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
                                WHERE siswa_id = {$s_id[$z]}
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
            
            echo"<br>
            <table style='table-layout:fixed;' class='rapot_rangkuman'>
                <thead>
                    <tr>
                    <th style='width: 20px; height: 15px; padding: 0px 0px 0px 0px;'>NO </th>
                    <th style='width: 150px; height: 15px; padding: 0px 0px 0px 5px;'>LIFE SKILLS</th>
                    <th style='width: 350px; height: 15px;'>GRADE</th>
                    </tr>
                </thead>
                <tbody>
                    ";
            echo "<tr>";
                echo "<td style='text-align: center;'>1</td><td>Physical Fitness and Healthful Habit</td>";
                echo "<td style='padding: 0px 0px 0px 15px;'><b>$jumlah_pf_hf</b></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center;'>2</td><td>Moral Behavior</td>";
                echo "<td style='padding: 0px 0px 0px 15px;'><b>$jumlah_moral_b</b></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center;'>3</td><td>Emotional Awareness</td>";
                echo "<td style='padding: 0px 0px 0px 15px;'><b>$jumlah_emo_aware</b></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center;'>4</td><td>Spirituality</td>";
                echo "<td style='padding: 0px 0px 0px 15px;'><b>$jumlah_spirit</b></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center;'>5</td><td>Social Skill</td>";
                echo "<td style='padding: 0px 0px 0px 15px;'><b>$jumlah_ss</b></td>";
            echo "</tr>";
            echo"
                </tbody>
            </table>";
            
            $scout_nilai_angka = 0;
            $sql_scout = "SELECT *
                          FROM scout_nilai
                          WHERE scout_nilai_siswa_id = {$s_id[$z]}";
            $sql_v_scout = mysqli_query($conn, $sql_scout); 
            
            while($row_scout = mysqli_fetch_array($sql_v_scout)){
                $scout_nilai_angka = $row_scout['scout_nilai_angka'];
            }

            echo"
            <div class='sub_judul mt-1'>SELF DEVELOPMENT</div>
            <table style='table-layout:fixed;' class='rapot_rangkuman'>
                <tbody>
                    ";
            echo "<tr>";
                echo "<td style='text-align: center; width: 20px;'>1</td><td style='width: 150px;'>Character Building</td>";
                echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>".return_abjad_base4($final_score_bk)."</b></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center; width: 20px;'>2</td><td style='width: 150px;'>".ucfirst(strtolower($nama_ssp))."</td>";
                echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>".return_abjad_base4($final_score)."</b></td>";
            echo "</tr>";
            echo "<tr>";
                if($scout_nilai_angka>1){
                    echo "<td style='text-align: center; width: 20px;'>3</td><td style='width: 150px;'>Scout</td>";
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>".return_abjad_base4($scout_nilai_angka)."</b></td>";
                }
             echo "</tr>";
            echo"
                </tbody>
            </table>";
            
            echo"
            <div class='sub_judul mt-1'>ATTENDANCE RECORD</div>
            <table style='table-layout:fixed;' class='rapot_rangkuman'>
                <tbody>
                    ";
            echo "<tr>";
                echo "<td style='text-align: center; width: 20px;'>1</td><td style='width: 150px;'>Sick</td>";
                if($siswa_tardy >0){
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>$siswa_tardy</b> day(s)</td>";
                }else{
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>-</b> day(s)</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center; width: 20px;'>2</td><td style='width: 150px;'>Absent (Including Excuse)</td>";
                if($siswa_absenin >0){
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>$siswa_absenin</b> day(s)</td>";
                }else{
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>-</b> day(s)</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align: center; width: 20px;'>3</td><td style='width: 150px;'>Absent (Excluding Excuse)</td>";
                if($siswa_absenex >0){
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>$siswa_absenex</b> day(s)</td>";
                }else{
                    echo "<td style='padding: 0px 0px 0px 15px; width: 350px;'><b>-</b> day(s)</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='padding: 0px 0px 0px 5px;' colspan = 2><b>Homeroom Teacher's Comment</b></td><td style='width: 200px; padding: 5px 5px 5px 5px;'>$siswa_komen_akhir</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='padding: 0px 0px 0px 5px;' colspan = 2><b>Special Note</b></td><td style='width: 200px; padding: 5px 5px 5px 5px;'>$siswa_special_note</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='padding: 0px 0px 0px 5px;' colspan = 2><b>Note</b></td>";
                echo "<td></td>";
            echo "</tr>";
            echo"
                </tbody>
            </table>";
            
            echo"<div id='textbox'>
                <p class='alignleft_bawah'>
                <br>Acknowledged by<br>
                Parents / Guardian<br><br><br><br>
                ............................................
                </p>
                <p class='alignright_bawah'>
                <br>Surabaya, $bulan $tanggal_arr[2], $tanggal_arr[0]<br>
                Principal<br><br><br><br>
                <b>$tahun_ajaran_nama_kepsek</b><br>
                </p>
            </div>";
            
            echo"<div style='clear: both;'></div>";
            echo '<p style="page-break-after: always;">&nbsp;</p>';
        }

        
    }
        
?>
