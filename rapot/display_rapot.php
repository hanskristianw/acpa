<?php
    //RAPOT WALI
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");   
    
    if(!empty($_POST["option_siswa"])) {
        $kelas_id = $_POST["kelas_id"];
        $siswa_id = $_POST["option_siswa"];
        
        $query_mapel = "SELECT *
                        FROM siswa
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        LEFT JOIN t_ajaran
                        ON kelas_t_ajaran_id = t_ajaran_id
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
        }
        if($tahun_ajaran_semester == '1'){
            $semester_inggris = '(Odd)';
        }
        else{
            $semester_inggris = '(Even)';
        }
        
        $program_nama = explode(" ", $kelas_nama);
        
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
        
        //QUERY
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
            
            if($row['mapel_kkm']>=80){
                if($row['n_akhir']>=92){
                    echo"<td class='biasa'>EXCELLENT</td>";
                }
                elseif($row['n_akhir']>=86){
                    echo"<td class='biasa'>GOOD</td>";
                }
                elseif($row['n_akhir']>=80){
                    echo"<td class='biasa'>SATISFACTORY</td>";
                }
                else{
                    echo"<td class='biasa'>POOR</td>";
                }
            }
            elseif($row['mapel_kkm']>=75){
                if($row['n_akhir']>=91){
                    echo"<td class='biasa'>EXCELLENT</td>";
                }
                elseif($row['n_akhir']>=83){
                    echo"<td class='biasa'>GOOD</td>";
                }
                elseif($row['n_akhir']>=75){
                    echo"<td class='biasa'>SATISFACTORY</td>";
                }
                else{
                    echo"<td class='biasa'>POOR</td>";
                }
            }
            elseif($row['mapel_kkm']>=70){
                if($row['n_akhir']>=90){
                    echo"<td class='biasa'>EXCELLENT</td>";
                }
                elseif($row['n_akhir']>=80){
                    echo"<td class='biasa'>GOOD</td>";
                }
                elseif($row['n_akhir']>=70){
                    echo"<td class='biasa'>SATISFACTORY</td>";
                }
                else{
                    echo"<td class='biasa'>POOR</td>";
                }
            }
            echo"</tr>";
            $nomor++;
        }
               
         echo"
            </tbody>
        </table>";
        

    }
        
?>
