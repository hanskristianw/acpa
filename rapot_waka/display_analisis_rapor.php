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
        
        $query =    
        "SELECT t_for.mapel_nama, mapel_kkm,t_afek.afektif_total,t_afek.total_bulan, mapel_nama_singkatan, jum_topik, t_for.mapel_id as mapel_id,
                for_kog,mapel_persen_for,sum_kog,mapel_persen_sum,for_psi,sum_psi,
                mapel_persen_for_psi, mapel_persen_sum_psi,
                mapel_persen_kog, mapel_persen_psi,
        ROUND(ROUND(for_kog * mapel_persen_for + sum_kog * mapel_persen_sum,2)) as Cognitive, 
        ROUND(ROUND(for_psi * mapel_persen_for_psi + sum_psi * mapel_persen_sum_psi,2)) as Psychomotor,
        ROUND(ROUND(for_kog * mapel_persen_for + sum_kog * mapel_persen_sum,2))*mapel_persen_kog + ROUND(ROUND(for_psi * mapel_persen_for_psi + sum_psi * mapel_persen_sum_psi,2))*mapel_persen_psi AS n_akhir
        FROM
            (SELECT mapel_id, mapel_nama,COUNT(DISTINCT kog_psi_topik_id) as jum_topik,
            ROUND(SUM(ROUND(kog_quiz*kog_quiz_persen/100 + kog_ass*kog_ass_persen/100 + kog_test*kog_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
            AS for_kog,
            ROUND(SUM(ROUND(psi_quiz*psi_quiz_persen/100 + psi_ass*psi_ass_persen/100 + psi_test*psi_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
            AS for_psi
            FROM kog_psi 
            LEFT JOIN topik
            ON kog_psi_topik_id = topik_id
            LEFT JOIN mapel
            ON topik_mapel_id = mapel_id
            WHERE kog_psi_siswa_id = $siswa_id
            GROUP BY mapel_nama
            ORDER BY mapel_urutan) AS t_for
        JOIN
            (SELECT mapel_id, mapel_nama, mapel_kkm, mapel_persen_for, mapel_persen_sum, mapel_nama_singkatan,
                    mapel_persen_for_psi, mapel_persen_sum_psi,
                    mapel_persen_kog, mapel_persen_psi,
            ROUND((kog_uts * kog_uts_persen + kog_uas * kog_uas_persen) /100,0) as sum_kog,
            ROUND((psi_uts * psi_uts_persen + psi_uas * psi_uas_persen) /100,0) as sum_psi
            FROM kog_psi_ujian
            LEFT JOIN mapel
            ON kog_psi_ujian_mapel_id = mapel_id
            WHERE kog_psi_ujian_siswa_id = $siswa_id
            GROUP BY mapel_nama
            ORDER BY mapel_urutan) AS t_sum ON t_for.mapel_id = t_sum.mapel_id 
        JOIN
            (SELECT mapel_id, count(mapel_id) as total_bulan, GROUP_CONCAT(afektif_nilai SEPARATOR '.') as afektif_total
            FROM afektif
            LEFT JOIN mapel
            ON afektif_mapel_id = mapel_id
            WHERE afektif_siswa_id = $siswa_id
            GROUP BY mapel_id
            ORDER BY mapel_urutan)AS t_afek ON t_afek.mapel_id = t_sum.mapel_id";

            //echo $query;
        $query_k_afektif_info = mysqli_query($conn, $query);

        if(!$query_k_afektif_info){
            die("QUERY FAILED".mysqli_error($conn));
        }
        
        $nomor =1;
        
        //TABEL
        echo"<b>Formative &nbsp&nbsp&nbsp&nbsp&nbsp:</b> Quiz, Test, Assignment <br>
            <b>Summative &nbsp&nbsp&nbsp:</b> UTS, UAS <br><br>";

        echo"<div id='container-analisis'></div>";

        echo"
        <table class='rapot'>
            <thead>
                <tr>
                  <th rowspan='2'>NO.</th>
                  <th rowspan='2'>SUBJECT</th>
                  <th colspan='5'>ACHIEVEMENT REPORT</th>
                </tr>
                <tr>
                  <th>Cognitive<br>(for * %for) + (sum * %sum))</th>
                  <th>Psychomotor<br>(for * %for) + (sum * %sum)</th>
                  <th>Final Score<br>(kog * %kog) + (psi * %psi)</th>
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
            echo"<td>&nbsp{$row['mapel_nama_singkatan']}({$row['mapel_kkm']})</td>";
            
            //kognitif
            echo"<td class='biasa'>
                ((<a rel='".$row['mapel_id']."' rel2='".$siswa_id."' class='link-formative' href='javascript:void(0)'>{$row['for_kog']}</a>*{$row['mapel_persen_for']})+({$row['sum_kog']}*{$row['mapel_persen_sum']}))
                <br>={$row['Cognitive']}</td>";

            //psikomotor
            echo"<td class='biasa'>
                ((<a rel='".$row['mapel_id']."' rel2='".$siswa_id."' class='link-formative-psi' href='javascript:void(0)'>{$row['for_psi']}</a>*{$row['mapel_persen_for_psi']})+({$row['sum_psi']}*{$row['mapel_persen_sum_psi']}))
                                    <br>={$row['Psychomotor']}</td>";
            
            // $kognitif = $row['Cognitive'];
            // $psikomotor = $row['Psychomotor'];
            // $persen_kog = $row['mapel_persen_kog'];
            // $persen_psi = $row['persen_psi'];

            // $nilai_akhir = ($kognitif * $persen_kog + $psikomotor * $persen_psi)/100;

            echo"<td class='biasa'>({$row['Cognitive']} * {$row['mapel_persen_kog']}+{$row['Psychomotor']} * {$row['mapel_persen_psi']})<br>={$row['n_akhir']}</td>";
            
            echo"</tr>";
            $nomor++;
        }
               
         echo"
            </tbody>
        </table>";
       
    }
        
?>


<script>

$(".link-formative").on('click', function(){
    //$("#container-siswa").show();
    
    var mapel_id = $(this).attr("rel");
    var siswa_id = $(this).attr("rel2");
    

    $.post("rapot_waka/display_analisis_permapel.php",{mapel_id: mapel_id, siswa_id: siswa_id}, function(data){
        $("#container-analisis").html(data);
    });
    
});

$(".link-formative-psi").on('click', function(){
    //$("#container-siswa").show();
    
    var mapel_id = $(this).attr("rel");
    var siswa_id = $(this).attr("rel2");
    

    $.post("rapot_waka/display_analisis_permapel_psi.php",{mapel_id: mapel_id, siswa_id: siswa_id}, function(data){
        $("#container-analisis").html(data);
    });
    
});
</script>
