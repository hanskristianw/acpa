<?php
    //RAPOT WAKA
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");   
    include ("../includes/fungsi_lib.php"); 
    
    if(!empty($_POST["check_siswa_id"])) {
        ini_set('max_execution_time', 300);
        $kelas_id = $_POST["option_kelas2"];
        $s_id = $_POST["check_siswa_id"];
        
        for($z=0;$z<count($s_id);$z++){
//            echo $s_id[$i];
//            echo "<br>";

            //cek apakah siswa terdaftar pada kelas khusus
            $mapel_khusus_id = [];
            $mapel_khusus_nama = [];
            $query =    "SELECT mapel_k_m_mapel_id, mapel_k_m_nama
                        FROM detail_mapel_khusus_master
                        LEFT JOIN mapel_khusus_master
                        ON d_m_k_mapel_k_m_id = mapel_k_m_id
                        WHERE d_m_k_siswa_id = {$s_id[$z]}";

            $query_info = mysqli_query($conn, $query);
            
            while ($row3 = mysqli_fetch_assoc($query_info)) {
                array_push($mapel_khusus_id, $row3['mapel_k_m_mapel_id']);
                array_push($mapel_khusus_nama, $row3['mapel_k_m_nama']);
            }

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
                $siswa_nama_lengkap = $row_mapel['siswa_nama_depan'].' '.$row_mapel['siswa_nama_belakang'];
                $siswa_no_induk = $row_mapel['siswa_no_induk'];
                $siswa_komen = $row_mapel['siswa_komen'];
                $siswa_absenin = $row_mapel['siswa_absenin'];
                $siswa_absenex = $row_mapel['siswa_absenex'];
                $siswa_tardy = $row_mapel['siswa_tardy'];
                $kelas_nama = $row_mapel['kelas_nama'];
                $tahun_ajaran_nama = $row_mapel['t_ajaran_nama']; 
                $tahun_ajaran_semester = $row_mapel['t_ajaran_semester']; 
                $tahun_ajaran_nama_kepsek = $row_mapel['guru_name']; 
                $tahun_ajaran_tanggal_rapot = $row_mapel['t_ajaran_tanggal_rapot_sisipan']; 
            }
            if($tahun_ajaran_semester == '1'){
                $semester_inggris = '(Odd)';
            }
            else{
                $semester_inggris = '(Even)';
            }

            //$program_nama = explode(" ", $kelas_nama);

            echo '<hr style="height:5px; visibility:hidden;" />';
            echo"<br><br><br><div id='textbox'>
                <p class='alignleft'>
                STUDENT'S NAME &nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_nama_lengkap<br>
                STUDENT ID &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;:&nbsp$siswa_no_induk<br>
                </p>
                <p class='alignright'>
                GRADE &nbsp&nbsp&nbsp&nbsp&emsp;&emsp;&emsp;&thinsp;:&nbsp$kelas_nama<br>
                SEMESTER &nbsp&emsp;&thinsp;&emsp;: $tahun_ajaran_semester $semester_inggris<br>
                </p>
            </div>";

            echo"<div style='clear: both;'></div>";

            //QUERY
            $query =    "SELECT * FROM
                    (
                        SELECT mapel_id, mapel_urutan, kog_psi_siswa_id, mapel_nama,mapel_kkm, 
                        GROUP_CONCAT(kog_quiz ORDER BY topik_urutan) as kq, 
                        GROUP_CONCAT(kog_ass ORDER BY topik_urutan) as ka, 
                        GROUP_CONCAT(kog_test ORDER BY topik_urutan) as kt, 
                        GROUP_CONCAT(psi_quiz ORDER BY topik_urutan) as pq, 
                        GROUP_CONCAT(psi_ass ORDER BY topik_urutan) as pa, 
                        GROUP_CONCAT(psi_test ORDER BY topik_urutan) as pt
                        FROM kog_psi 
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        LEFT JOIN mapel
                        ON topik_mapel_id = mapel_id
                        WHERE kog_psi_siswa_id = {$s_id[$z]}
                        GROUP BY mapel_id
                        ORDER BY mapel_urutan
                    )as formative
                    LEFT JOIN
                    (
                        SELECT mapel_id, kog_uts, psi_uts
                        FROM kog_psi_ujian
                        LEFT JOIN mapel
                        ON kog_psi_ujian_mapel_id = mapel_id
                        WHERE kog_psi_ujian_siswa_id = {$s_id[$z]}
                        GROUP BY mapel_id
                        ORDER BY mapel_urutan
                    )as summative ON formative.mapel_id = summative.mapel_id
                    LEFT JOIN
                    (
                        SELECT mapel_id, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
                        FROM afektif
                        LEFT JOIN mapel
                        ON afektif_mapel_id = mapel_id
                        WHERE afektif_siswa_id = {$s_id[$z]}
                        GROUP BY mapel_id
                        ORDER BY mapel_urutan
                    )AS afek ON afek.mapel_id = formative.mapel_id ORDER BY mapel_urutan";

                //echo $query;
            $query_k_afektif_info = mysqli_query($conn, $query);

            if(!$query_k_afektif_info){
                die("QUERY FAILED".mysqli_error($conn));
            }

            $nomor =1;

            //TABEL
            echo"
        <table style='margin-top: 5px;' class='rapot'>
            <thead>
                <tr>
                    <th rowspan='4'>NO.</th>
                    <th rowspan='4' style='width: 190px; padding: 0px 0px 0px 5px;'>SUBJECT</th>
                    <th rowspan='4' style='width: 60px; padding: 0px 0px 0px 5px;'>PASSING <br>GRADE</th>
                    <th colspan='15' style='padding: 0px 0px 0px 5px;'>PROGRESS REPORT</th>
                </tr>
                <tr>
                    <th colspan='12'>FORMATIVE</th>
                    <th rowspan='3' style='width: 80px;'>AFFECTIVE</th>
                    <th rowspan='2' colspan='2'>SUMMATIVE</th>
                </tr>
                <tr>
                    <th colspan='6'>COGNITIVE</th>
                    <th colspan='6'>PSYCHOMOTOR</th>
                </tr>
                <tr>
                    <th>Q1</th>
                    <th>A1</th>
                    <th>T1</th>
                    <th>Q2</th>
                    <th>A2</th>
                    <th>T2</th>
                    <th>Q1</th>
                    <th>A1</th>
                    <th>T1</th>
                    <th>Q2</th>
                    <th>A2</th>
                    <th>T2</th>
                    <th>COG</th>
                    <th>PSY</th>
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

                $index_ketemu_khusus = -1;
                $mapel_id_temp = $row['mapel_id'];
                for($ii=0;$ii<count($mapel_khusus_id);$ii++){
                    if($mapel_id_temp == $mapel_khusus_id[$ii]){
                        $index_ketemu_khusus = $ii;
                    }
                }

                
                //pisahkan nama MAPEL jika terlalu panjang
                $mapel_nama_fix ="";

                if($index_ketemu_khusus > -1){
                    $temp_mapel_nama = explode(" ", $mapel_khusus_nama[$index_ketemu_khusus]);

                }else{
                    $temp_mapel_nama = explode(" ", $row['mapel_nama']);
                }

                //kalau lebih dari 2 kata dan panjang katanya >20
                if(sizeof($temp_mapel_nama)>2){
                    $panjang_mapel = 0;

                    for($t=0;$t<count($temp_mapel_nama);$t++){
                        $panjang_mapel += strlen($temp_mapel_nama[$t]);
                    }

                    if($panjang_mapel > 20){
                        for($i=0;$i<sizeof($temp_mapel_nama);$i++){
                            $mapel_nama_fix .= $temp_mapel_nama[$i] ." ";
                            if($i==1){
                                $mapel_nama_fix .= "<br>";
                            }
                        }
                    }else{
                        for($i=0;$i<sizeof($temp_mapel_nama);$i++){
                            $mapel_nama_fix .= $temp_mapel_nama[$i] ." ";
                        }
                    }
                    
                    echo"<td style='padding: 0px 0px 0px 5px; margin: 0px;'>$mapel_nama_fix</td>";
                }
                else{
                    if($index_ketemu_khusus > -1){
                        echo"<td style='padding: 0px 0px 0px 5px; margin: 0px;'>{$mapel_khusus_nama[$index_ketemu_khusus]}</td>";
                    }else{
                        echo"<td style='padding: 0px 0px 0px 5px; margin: 0px;'>{$row['mapel_nama']}</td>";
                    }
                    
                }

                echo"<td class='kkm'>{$row['mapel_kkm']}</td>";
    //            echo"<td class='biasa'>{$row['Cognitive']}</td>";
    //            echo"<td class='biasa'>{$row['Psychomotor']}</td>";

                ///////////////////////////////////////////////////////////COGNITIVE////////////////////////////////////////////////
                $kq = explode(",", $row['kq']);
                $ka = explode(",", $row['ka']);
                $kt = explode(",", $row['kt']);
                $pq = explode(",", $row['pq']);
                $pa = explode(",", $row['pa']);
                $pt = explode(",", $row['pt']);
                //KQ1
                echo"<td class='biasa'>";
                    if(isset($kq[0])){
                        if($kq[0]>0){
                            echo $kq[0];
                        }elseif($kq[0]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //KA1
                echo"<td class='biasa'>";
                    if(isset($ka[0])){
                        if($ka[0]>0){
                            echo $ka[0];
                        }elseif($ka[0]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //KT1
                echo"<td class='biasa'>";
                    if(isset($kt[0])){
                        if($kt[0]>0){
                            echo $kt[0];
                        }elseif($kt[0]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //KQ2
                echo"<td class='biasa'>";
                    if(isset($kq[1])){
                        if($kq[1]>0){
                            echo $kq[1];
                        }elseif($kq[1]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //KA2
                echo"<td class='biasa'>";
                    if(isset($ka[1])){
                        if($ka[1]>0){
                            echo $ka[1];
                        }elseif($ka[1]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //KT2
                echo"<td class='biasa'>";
                    if(isset($kt[1])){
                        if($kt[1]>0){
                            echo $kt[1];
                        }elseif($kt[1]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";

                ///////////////////////////////////////////////////////////PSYCHOMOTOR////////////////////////////////////////////////
                //PQ1
                echo"<td class='biasa'>";
                    if(isset($pq[0])){
                        if($pq[0]>0){
                            echo $pq[0];
                        }elseif($pq[0]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //PA1
                echo"<td class='biasa'>";
                    if(isset($pa[0])){
                        if($pa[0]>0){
                            echo $pa[0];
                        }elseif($pa[0]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //PT1
                echo"<td class='biasa'>";
                    if(isset($pt[0])){
                        if($pt[0]>0){
                            echo $pt[0];
                        }elseif($pt[0]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //PQ2
                echo"<td class='biasa'>";
                    if(isset($pq[1])){
                        if($pq[1]>0){
                            echo $pq[1];
                        }elseif($pq[1]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //PA2
                echo"<td class='biasa'>";
                    if(isset($pa[1])){
                        if($pa[1]>0){
                            echo $pa[1];
                        }elseif($pa[1]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";
                //PT2
                echo"<td class='biasa'>";
                    if(isset($pt[1])){
                        if($pt[1]>0){
                            echo $pt[1];
                        }elseif($pt[1]<0){
                            echo "-";
                        }else{echo " ";}
                    }else{echo " ";}
                echo "</td>";

                ///////////////////////////////////////////////////////////hitung afektif////////////////////////////////////////////
                $afektif_total = $row['afektif_total'];
                $total_bulan = $row['total_bulan'];
                $nilai_perbulan = explode('.', $afektif_total);

                echo"<td class='biasa'>".return_abjad_afek(return_total_nilai_afektif_bulan($nilai_perbulan)/$total_bulan)."</td>";

                ////////////////////////////////////////////////////////SUMMATIVE (UTS)////////////////////////////////////////////////
                echo"<td class='biasa'>";
                if($row['kog_uts']>0){
                    echo $row['kog_uts'];
                }elseif($row['kog_uts']<0 && $row['kog_uts']!=-99){
                    echo "-";
                }elseif($row['kog_uts']==-99){
                    echo "0";
                }else{echo " ";}
                echo "</td>";

                echo"<td class='biasa'>";
                    if($row['psi_uts']>0){
                        echo $row['psi_uts'];
                    }elseif($row['psi_uts']<0 && $row['psi_uts']!=-99){
                        echo "-";
                    }elseif($row['psi_uts']==-99){
                        echo "0";
                    }else{echo " ";}
                echo "</td>";
                echo"</tr>";
                $nomor++;
            }
            //////////////////////////////////////////////////////////SSP//////////////////////////////////////////////////////////////
            $query_ssp = "SELECT ssp_nilai_siswa_id, ssp_nama, SUM(ssp_nilai_angka)/count(ssp_nilai_angka) as ssp_nilai_angka, GROUP_CONCAT(d_ssp_kriteria)
                        FROM ssp_nilai
                        LEFT JOIN d_ssp
                        ON ssp_nilai_d_ssp_id = d_ssp_id
                        LEFT JOIN ssp
                        ON d_ssp_ssp_id = ssp_id
                        WHERE ssp_nilai_siswa_id = {$s_id[$z]}
                        GROUP BY ssp_nilai_siswa_id";

            $query_ssp_info = mysqli_query($conn, $query_ssp);        
            if(!$query_ssp_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            $ssp_nilai_angka = 0;
            $ssp_nama = "";
            while($row_mapel = mysqli_fetch_array($query_ssp_info)){
                $ssp_nilai_angka = $row_mapel['ssp_nilai_angka'];
                $ssp_nama = $row_mapel['ssp_nama'];
            }
            $ssp_nilai_huruf ="";

            if(isset($ssp_nilai_angka) && $ssp_nilai_angka>0 ){
                $ssp_nilai_huruf = return_abjad_base4($ssp_nilai_angka); 
            }
            if(isset($ssp_nama) && $ssp_nama != ""){
                echo "<tr>
                    <td class='biasa'>$nomor</td>
                    <td>&nbsp SSP $ssp_nama</td>
                    <td class='biasa' colspan='13'> </td>
                    <td class='biasa' colspan='3'>$ssp_nilai_huruf</td>
                  </tr>";
            }

            ///////////////////////////////////////////////////////Homeroom Teacher//////////////////////////////////////////////////////////
            echo "<tr>
                    <td class='kkm' colspan='2'>Homeroom Teacher's Comment</td>
                    <td colspan='16' cellpadding='20' style='padding: 0px 0px 0px 5px;'> $siswa_komen</td>
                  </tr>";

            ///////////////////////////////////////////////////////ATTENDANCE RECORD//////////////////////////////////////////////////////////
            echo "<tr>
                  <td style='height:0px; padding: 0px 0px 0px 0px;' class='kkm' colspan='18'>ATTENDANCE RECORD</td>
                </tr>";
            echo "<tr>
                  <td style='height:0px; padding: 0px 0px 0px 0px;' colspan='18'>&nbsp&nbsp&nbsp1. Sick &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp&nbsp&nbsp&thinsp;: ".$siswa_tardy." days</td>
                </tr>";
            echo "<tr>
                  <td style='height:0px; padding: 0px 0px 0px 0px;' colspan='18'>&nbsp&nbsp&nbsp2. Absent (Including Excuse)&nbsp&nbsp&thinsp;: ".$siswa_absenin." days</td>
                </tr>";
            echo "<tr>
                  <td style='height:0px; padding: 0px 0px 0px 0px;' colspan='18'>&nbsp&nbsp&nbsp3. Absent (Excluding Excuse)&nbsp;&thinsp;: ".$siswa_absenex." days</td>
                </tr>";
            echo"
               </tbody>
            </table>";
            echo "<div style='font-size: 10px !important'>&emsp;&emsp;*)Q = Quiz; &nbsp A = Assignment; &nbsp T=Test;</div>";
            ///////////////////////////////////////////////////////FOOTER////////////////////////////////////////////////////////////////////
            $query_mapel2 = "SELECT guru_name
                            FROM kelas
                            LEFT JOIN guru
                            ON kelas_wali_guru_id = guru_id
                            WHERE kelas_id = {$kelas_id}";

            $query_mapel_info2 = mysqli_query($conn, $query_mapel2);        
            if(!$query_mapel_info2){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row_mapel = mysqli_fetch_array($query_mapel_info2)){
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
                Parents / Guardian<br><br><br>
                ............................................
                </p>
                <p class='alignright_bawah'>
                <br>Surabaya, $bulan $tanggal_arr[2], $tanggal_arr[0]<br>
                Homeroom Teacher<br><br><br>
                <b>$wali_kelas</b><br>
                </p>
            </div>";

            echo"<div style='clear: both;'></div>";

            echo"<p class='aligncenter_bawah'>Acknowleged by<br>Principal<br><br><br><b>$tahun_ajaran_nama_kepsek</b></p>";
            
            echo '<p style="page-break-after: always;">&nbsp;</p>';
            
        }
        
    }
        
?>
