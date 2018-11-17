<?php
    //RAPOT WAKA
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");   
    
    if(!empty($_POST["option_siswa"])) {
        $kelas_id = $_POST["option_kelas"];
        $siswa_id = $_POST["option_siswa"];
        
        $query_mapel = "SELECT *
                        FROM siswa
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        LEFT JOIN t_ajaran
                        ON kelas_t_ajaran_id = t_ajaran_id
                        LEFT JOIN guru
                        ON t_ajaran_kepsek_id_guru = guru_id
                        WHERE siswa_id = {$siswa_id}";

        $query_mapel_info = mysqli_query($conn, $query_mapel);        
        if(!$query_mapel_info){
            die("QUERY FAILED".mysqli_error($conn));
        }
        while($row_mapel = mysqli_fetch_array($query_mapel_info)){
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
            SCHOOL YEAR &nbsp&nbsp&nbsp&nbsp&nbsp&thinsp;: $tahun_ajaran_nama<br>
            PROGRAM &nbsp&nbsp&emsp;&emsp;&thinsp;&thinsp;&thinsp;: $program_nama[1]<br>
            </p>
        </div>";
        
        echo"<div style='clear: both;'></div>";
        
        $query =    "SELECT t_for.mapel_nama, mapel_kkm,t_afek.afektif_total,t_afek.total_bulan,
                    ROUND((for_kog * 0.7 + sum_kog * 0.3),0) as Cognitive, 
                    ROUND((for_psi * 0.7 + sum_psi * 0.3),0) as Psychomotor,
                    ROUND((ROUND((for_kog * 0.7 + sum_kog * 0.3),0)*50 + ROUND((for_psi * 0.7 + sum_psi * 0.3),0)*50)/100,0) AS n_akhir
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
                        WHERE kog_psi_siswa_id = {$siswa_id}
                        GROUP BY mapel_nama
                        ORDER BY mapel_urutan) AS t_for
                    JOIN
                        (SELECT mapel_id, mapel_nama, mapel_kkm,
                        ROUND((kog_uts * kog_uts_persen + kog_uas * kog_uas_persen) /100,0) as sum_kog,
                        ROUND((psi_uts * psi_uts_persen + psi_uas * psi_uas_persen) /100,0) as sum_psi
                        FROM kog_psi_ujian
                        LEFT JOIN mapel
                        ON kog_psi_ujian_mapel_id = mapel_id
                        WHERE kog_psi_ujian_siswa_id = {$siswa_id}
                        GROUP BY mapel_nama
                        ORDER BY mapel_urutan) AS t_sum ON t_for.mapel_id = t_sum.mapel_id 
                    JOIN
                        (SELECT mapel_id, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                        FROM afektif
                        LEFT JOIN mapel
                        ON afektif_mapel_id = mapel_id
                        WHERE afektif_siswa_id = {$siswa_id}
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
            $temp_mapel_nama = explode(" ", $row['mapel_nama']);
            if(sizeof($temp_mapel_nama)>2){
                for($i=0;$i<sizeof($temp_mapel_nama);$i++){
                    $mapel_nama_fix .= $temp_mapel_nama[$i] ." ";
                    if($i==1){
                        $mapel_nama_fix .= "<br>&nbsp";
                    }
                }
                echo"<td >&nbsp$mapel_nama_fix</td>";
            }
            else{
                echo"<td>&nbsp{$row['mapel_nama']}</td>";
            }
            
            echo"<td class='kkm'>{$row['mapel_kkm']}</td>";
            echo"<td class='biasa'>{$row['Cognitive']}</td>";
            echo"<td class='biasa'>{$row['Psychomotor']}</td>";
            
            //hitung afektif
            
            $afektif_total = $row['afektif_total'];
            $total_bulan = $row['total_bulan'];
            $nilai_perbulan = explode('.', $afektif_total);
            for ($i=0;$i<count($nilai_perbulan);$i++){
                $nilai_perminggu = explode('/', $nilai_perbulan[$i]);
                for ($j=0;$j<count($nilai_perminggu);$j++){
                    $nilai_pertopik = explode('_', $nilai_perminggu[$j]);
                    for ($k=0;$k<count($nilai_pertopik);$k++){
                        if($nilai_pertopik[$k] > 0){
                            $cek_minggu_aktif += 1;
                        }
                        $total[$nomor] += $nilai_pertopik[$k];
                    }
                    if($cek_minggu_aktif == 3){
                        $pembagi_afektif[$nomor] += 1;
                    }
                    $cek_minggu_aktif = 0;
                }
            }
            

            //echo"<td class='biasa'>$total[$nomor]</td>";
            $afektif_akhir = $total[$nomor] * 10 / $pembagi_afektif[$nomor];
            if ($afektif_akhir >=80){
                echo"<td class='biasa'>A</td>";
            }
            elseif ($afektif_akhir >=70){
                echo"<td class='biasa'>B</td>";
            }
            elseif ($afektif_akhir >=65){
                echo"<td class='biasa'>C</td>";
            }
            elseif ($afektif_akhir >=50){
                echo"<td class='biasa'>D</td>";
            }
            else{
                echo"<td class='biasa'>E</td>";
            }
//            echo"<td class='biasa'>$afektif_akhir</td>";
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
        
        ////////////////////////////////HALAMAN 2//////////////////////////////////////
        echo '<p style="page-break-after: always;">&nbsp;</p>';
        $query_ssp = "SELECT ssp_nilai_siswa_id, ssp_nama,ssp_nilai_angka,d_ssp_kriteria,d_ssp_a,d_ssp_b,d_ssp_c, guru_name
                    FROM ssp_nilai
                    LEFT JOIN d_ssp
                    ON ssp_nilai_d_ssp_id = d_ssp_id
                    LEFT JOIN ssp
                    ON d_ssp_ssp_id = ssp_id
                    LEFT JOIN guru
                    ON ssp_guru_id = guru_id
                    WHERE ssp_nilai_siswa_id = {$siswa_id}";

        $query_ssp_info = mysqli_query($conn, $query_ssp);  
        $rowss = mysqli_fetch_row($query_ssp_info);
        $nama_ssp = $rowss[1];
        $guru_ssp = $rowss[7];
        
        mysqli_data_seek($query_ssp_info, 0);
        
        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }
        
        echo "<p class='judul'>SHARPENING STUDENTS POTENTIAL</p>";
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
                
                if($nilai_angka_ssp == 4){
                    echo"<td style='width: 50px; padding: 0px 5px 0px 5px; text-align: center;'>A</td>";
                }elseif($nilai_angka_ssp == 3){
                    echo"<td style='width: 50px; padding: 0px 5px 0px 5px; text-align: center;'>B</td>";
                }elseif($nilai_angka_ssp == 2){
                    echo"<td style='width: 50px; padding: 0px 5px 0px 5px; text-align: center;'>C</td>";
                }else{
                    echo"<td style='width: 50px; padding: 0px 5px 0px 5px; text-align: center;'>D</td>";
                }
                
                if($nilai_angka_ssp == 4){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['d_ssp_a']}</td>";
                }elseif($nilai_angka_ssp == 3){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['d_ssp_b']}</td>";
                }elseif($nilai_angka_ssp == 2){
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>{$row_mapel['d_ssp_c']}</td>";
                }else{
                    echo"<td style='width: 350px; padding: 0px 5px 0px 5px;'>-</td>";
                }
                $nomor_ssp++;
            echo"</tr>";
        } 
        
        
        $final_score = $total_ssp/($nomor_ssp - 1);
        
        if($final_score > 3){
            $final_score_angka = "A";
        }elseif($final_score > 2){
            $final_score_angka = "B";
        }elseif($final_score > 1){
            $final_score_angka = "C";
        }else{
            $final_score_angka = "D";
        }
        
        echo"<tr>
            <td style='text-align: center; font-weight:bold; height: 50px;' colspan='2'>FINAL SCORE</td>
            <td style='text-align: center; font-weight:bold; height: 50px;' colspan='2'> $final_score_angka </td>
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
        
        //////////////////////////////////////////////////////////////////HALAMAN 3////////////////////////////////////////////////////////////
        echo '<p style="page-break-after: always;">&nbsp;</p>';
        echo"<div id='textbox'>
            <p class='alignleft'>
            NAME &nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_nama_lengkap<br>
            ID NUMBER &nbsp&nbsp&emsp;:&nbsp$siswa_no_induk<br>
            CLASS &nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;&thinsp;:&nbsp$kelas_nama<br>
            </p>
            <p class='alignright'>
            SEMESTER &nbsp&nbsp&nbsp&emsp;&thinsp;&emsp;: $tahun_ajaran_semester $semester_inggris<br>
            SCHOOL YEAR &nbsp&nbsp&nbsp&nbsp&nbsp&thinsp;: $tahun_ajaran_nama<br>
            PROGRAM &nbsp&nbsp&emsp;&emsp;&thinsp;&thinsp;&thinsp;: $program_nama[1]<br>
            </p>
        </div>";
        echo"<div style='clear: both;'></div>";
        
        $sql_cek_karakter = "SELECT karakter_id,karakter_nama,GROUP_CONCAT(mapel_id),GROUP_CONCAT(mapel_nama) as mapel_nama_total, GROUP_CONCAT(total_bulan) as total_bulan_total, GROUP_CONCAT(afektif_total SEPARATOR '#')as karakter_afektif FROM 
                            (
                                    SELECT d_karakter_mapel_id, karakter_id, karakter_urutan, karakter_nama  FROM `d_karakter`
                                    LEFT JOIN karakter
                                    ON d_karakter_k_id = karakter_id
                            )AS a
                            LEFT JOIN
                            (
                                    SELECT mapel_id, mapel_nama, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                                    FROM afektif
                                    LEFT JOIN mapel
                                    ON afektif_mapel_id = mapel_id
                                    WHERE afektif_siswa_id = {$siswa_id}
                                    GROUP BY mapel_id
                                    ORDER BY mapel_urutan
                            )AS B
                            ON a.d_karakter_mapel_id = b.mapel_id
                            GROUP BY karakter_id
                            ORDER BY karakter_urutan";
        
        $sql_karakter = mysqli_query($conn, $sql_cek_karakter); 
        
        echo"<br>
        <table class='rapot'>
            <thead>
                <tr>
                  <th style='width: 35px; padding: 0px 0px 0px 0px;'>NO </th>
                  <th style='width: 200px;'>AFFECTIVE</th>
                  <th style='width: 350px;'>DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                ";
        
        $total = [];
        $pembagi_afektif = [];
        $nomor = 1;
        while($row_mapel = mysqli_fetch_array($sql_karakter)){
           
            echo"<tr>";
                echo"<td style='text-align: center;'>$nomor</td>";
                echo"<td style='width: 350px; padding: 0px 0px 0px 5px;'>{$row_mapel['karakter_nama']}</td>";
                
                $afektif_total_akhir = $row_mapel['karakter_afektif'];
                $total_bulan_total = $row_mapel['total_bulan_total'];
                $mapel_nama_total = $row_mapel['mapel_nama_total'];
                echo"<td padding: 0px 0px 0px 5px;'>";
                //ulang sebanyak jumlah mapel
                $mapel_nama = explode(',', $mapel_nama_total);
                $nilai_permapel = explode('#', $afektif_total_akhir);
                $akhir_tiap_karakter = 0;
                for($z=0;$z<sizeof($nilai_permapel);$z++){
                    $nomor2 = 1;
                    $akhir_tiap_mapel = 0;
                    echo $mapel_nama[$z]."<br>";
                    //ulang sebanyak jumlah bulan pada mapel $total[$nomor] = 0;
                    $pembagi_afektif[$nomor2] = 0;
                    $cek_minggu_aktif = 0;
                    $nilai_perbulan = explode('.', $nilai_permapel[$z]);
                    for ($i=0;$i<count($nilai_perbulan);$i++){
                        echo $nilai_perbulan[$i]."<br>";
                        //ulang nilai tiap minggu
                        $nilai_perminggu = explode('/', $nilai_perbulan[$i]);
                        $total_a =0;
                        $aktif_a =0;
                        for ($j=0;$j<count($nilai_perminggu);$j++){
                            //ulang nilai pertopik
                            $nilai_pertopik = explode('_', $nilai_perminggu[$j]);
                            for ($k=0;$k<count($nilai_pertopik);$k++){
                                if($nilai_pertopik[$k] > 0){
                                    $cek_minggu_aktif += 1;
                                }
                                //$total[$nomor2] += $nilai_pertopik[$k];
                                //echo $nilai_pertopik[$k];
                                $total_a += $nilai_pertopik[$k];
                            }
                            if($cek_minggu_aktif == 3){
                                $aktif_a +=1;
                                //$pembagi_afektif[$nomor2] += 1;
                            }
                            $cek_minggu_aktif = 0;
                        }
                        $akhir_tiap_bulan = $total_a/$aktif_a;
                        echo $total_a."/".$aktif_a."= ".$akhir_tiap_bulan;
                        echo "<br>";
                        //echo $total[$nomor2]."<br>";
                        $akhir_tiap_mapel += $akhir_tiap_bulan;
                    }
                    $akhir_tiap_mapel_fix= $akhir_tiap_mapel/count($nilai_perbulan);
                    echo "Total Nilai Mapel: ".$akhir_tiap_mapel_fix."<br>";
                    $akhir_tiap_karakter += $akhir_tiap_mapel_fix;
                    //$afektif_akhir = $total[$nomor2] * 10 / $pembagi_afektif[$nomor2];
                    //echo $afektif_akhir;
                    echo"<br>";
                }
                echo "--------------------------------------------------<br>";
                $total_nilai_karakter = $akhir_tiap_karakter/count($nilai_permapel);
                echo "Total Nilai Karakter: ".$total_nilai_karakter."<br><br>";
                
                if($total_nilai_karakter>=7.65){
                    echo "Hasil Karakter: A";
                }elseif($total_nilai_karakter>=6.3){
                    echo "Hasil Karakter: B";
                }elseif($total_nilai_karakter>=4.95){
                    echo "Hasil Karakter: C";
                }elseif($total_nilai_karakter>=3.6){
                    echo "Hasil Karakter: D";
                }else{
                    echo "Hasil Karakter: E";
                }
                
                echo "</td>";
                $nomor++;
            echo"</tr>";
        }
        echo"
            </tbody>
        </table>";
    }
        
?>
