<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        $mapel_id = $_POST['option_search_mapel'];
        $kelas_id = $_POST['option_kelas'];
        
        if($mapel_id > 0 && $kelas_id>0){

            include ("../includes/db_con.php");
            include ("../includes/fungsi_lib.php");

            //laporan nilai akhir raport
            $query_nilai_akhir =   
                "SELECT t_sum.siswa_id as siswa_id_bar, t_sum.mapel_id as mapel_id_bar,
                    t_sum.siswa_nama_depan as siswa_depan, t_sum.siswa_nama_belakang as siswa_belakang,
                    t_sum.sum_kog as tot_sum_kog, t_sum.sum_psi as tot_sum_psi,
                    t_for.for_kog as tot_for_kog, t_for.for_psi as tot_for_psi,
                    ROUND((t_for.for_kog * t_sum.mapel_persen_for + t_sum.sum_kog * t_sum.mapel_persen_sum),0) as Cognitive, 
                    ROUND((t_for.for_psi * t_sum.mapel_persen_for_psi + t_sum.sum_psi * t_sum.mapel_persen_sum_psi),0) as Psychomotor,
                    ROUND((ROUND((t_for.for_kog * t_sum.mapel_persen_for + t_sum.sum_kog * t_sum.mapel_persen_sum),0)*t_sum.mapel_persen_kog + ROUND((t_for.for_psi * t_sum.mapel_persen_for_psi + t_sum.sum_psi * t_sum.mapel_persen_sum_psi),0)*t_sum.mapel_persen_psi),0) AS n_akhir
                FROM 
                    (
                        SELECT siswa_id,
                        ROUND((kog_uts*kog_uts_persen+kog_uas*kog_uas_persen)/100,0) as sum_kog,
                        ROUND((psi_uts*psi_uts_persen+psi_uas*psi_uas_persen)/100,0) as sum_psi,
                        siswa_nama_depan,siswa_nama_belakang, mapel_id,
                        mapel_persen_for, mapel_persen_sum, 
                        mapel_persen_for_psi, mapel_persen_sum_psi,
                        mapel_persen_psi, mapel_persen_kog
                        FROM kog_psi_ujian
                        LEFT JOIN mapel
                        ON kog_psi_ujian_mapel_id = mapel_id
                        LEFT JOIN siswa
                        ON kog_psi_ujian_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE kog_psi_ujian_mapel_id= $mapel_id AND siswa_id_kelas = $kelas_id) as t_sum
                JOIN
                    (
                        SELECT siswa_id,
                        ROUND(SUM(ROUND(kog_quiz*kog_quiz_persen/100 + kog_ass*kog_ass_persen/100 + kog_test*kog_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
                        AS for_kog,
                        ROUND(SUM(ROUND(psi_quiz*psi_quiz_persen/100 + psi_ass*psi_ass_persen/100 + psi_test*psi_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
                        AS for_psi
                        FROM kog_psi
                        LEFT JOIN siswa
                        ON kog_psi_siswa_id = siswa_id
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        WHERE siswa_id_kelas = $kelas_id AND topik_mapel_id = $mapel_id
                        GROUP BY siswa_id
                        ORDER BY topik_id
                    ) as t_for ON t_sum.siswa_id = t_for.siswa_id";

            $query_akhir_info = mysqli_query($conn, $query_nilai_akhir);
            
            echo "<div id='container-analisis' class= 'p-3 mb-2 bg-light border border-primary rounded'></div>";

            echo "<h4 class='text-center mb-3 mt-5'><u>Laporan Nilai AKhir</u></h4>";
            
            echo return_alert("Klik nilai berwarna biru untuk melihat detail nilai","info");
            echo "<table class='rapot'>
                <tr>
                    <th rowspan='2' style='vertical-align: bottom;'>No</th>
                    <th rowspan='2' style='vertical-align: bottom;'>Nama Siswa</th>
                    <th colspan='3'>Cognitive</th>
                    <th colspan='3'>Psychomotor</th>
                    <th rowspan='2' style='vertical-align: bottom;'>Nilai Akhir</th>
                </tr>
                <tr>
                    <th>Formative</th>
                    <th>Summative</th>
                    <th>Final Cog</th>
                    <th>Formative</th>
                    <th>Summative</th>
                    <th>Final Psy</th>
                </tr>
                     ";

            $no = 1;
            while($row2 = mysqli_fetch_array($query_akhir_info)){
                $nama_belakang = $row2['siswa_belakang'];
                if(strlen($nama_belakang) > 0){
                    $nama_siswa = $row2['siswa_depan'] . " " . $nama_belakang[0];
                }else{
                    $nama_siswa = $row2['siswa_depan'];
                }
                echo "<tr>
                    <td style='padding: 0px 0px 0px 5px;'>$no</td>
                    <td style='padding: 0px 0px 0px 5px;'>{$nama_siswa}</td>
                    <td style='padding: 0px 0px 0px 5px;'><a rel='".$row2['mapel_id_bar']."' rel2='".$row2['siswa_id_bar']."' class='link-formative' href='javascript:void(0)'>{$row2['tot_for_kog']}</a></td>
                    <td style='padding: 0px 0px 0px 5px;'>{$row2['tot_sum_kog']}</td>
                    <td style='padding: 0px 0px 0px 5px;'>{$row2['Cognitive']}</td>
                    <td style='padding: 0px 0px 0px 5px;'><a rel='".$row2['mapel_id_bar']."' rel2='".$row2['siswa_id_bar']."' class='link-formative-psi' href='javascript:void(0)'>{$row2['tot_for_psi']}</a></td>
                    <td style='padding: 0px 0px 0px 5px;'>{$row2['tot_sum_psi']}</td>
                    <td style='padding: 0px 0px 0px 5px;'>{$row2['Psychomotor']}</td>
                    <td style='padding: 0px 0px 0px 5px;'>{$row2['n_akhir']}</td>
                    </tr>";
                $no++;
            }

            echo "</table>";

            //dapatkan nilai ujian
            $query2 =   "SELECT kog_uts,kog_uas,psi_uts,psi_uas,kog_uts_persen,kog_uas_persen,psi_uts_persen,psi_uas_persen,siswa_nama_depan,siswa_nama_belakang
                        FROM kog_psi_ujian
                        LEFT JOIN siswa
                        ON kog_psi_ujian_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE kog_psi_ujian_mapel_id= $mapel_id AND siswa_id_kelas = $kelas_id";

            $query_info2 = mysqli_query($conn, $query2);

            $rowss = mysqli_fetch_row($query_info2);
            $kog_uts_persen = $rowss['4'];
            $kog_uas_persen = $rowss['5'];
            $psi_uts_persen = $rowss['6'];
            $psi_uas_persen = $rowss['7'];
            mysqli_data_seek($query_info2, 0);
            
            echo "<h4 class='text-center mb-3 mt-5'><u>Laporan Hasil Ujian</u></h4>";
            echo "<table class='rapot mt-3'>
                     <tr>
                       <th>No</th>
                       <th>Nama siswa</th>
                       <th>Peng MID(".$kog_uts_persen."%)</th>
                       <th>Peng FINAL(".$kog_uas_persen."%)</th>
                       <th>Ket MID(".$psi_uts_persen."%)</th>
                       <th>Ket FINAL(".$psi_uas_persen."%)</th>
                     </tr>
                     ";
            
            $no = 1;
            while($row2 = mysqli_fetch_array($query_info2)){
                $nama_belakang = $row2['siswa_nama_belakang'];
                if(strlen($nama_belakang) > 0){
                    $nama_siswa = $row2['siswa_nama_depan'] . " " . $nama_belakang[0];
                }else{
                    $nama_siswa = $row2['siswa_nama_depan'];
                }
                echo "<tr>
                      <td style='padding: 0px 0px 0px 5px;'>$no</td>
                      <td style='padding: 0px 0px 0px 5px;'>{$nama_siswa}</td>
                      <td style='padding: 0px 0px 0px 5px;'>{$row2['kog_uts']}</td>
                      <td style='padding: 0px 0px 0px 5px;'>{$row2['kog_uas']}</td>
                      <td style='padding: 0px 0px 0px 5px;'>{$row2['psi_uts']}</td>
                      <td style='padding: 0px 0px 0px 5px;'>{$row2['psi_uas']}</td>
                     </tr>";
                $no++;
            }

            echo "</table>";
            
            
            //cari semua topik yang ada pada siswa ini dan mapel ini dan ada pada tabel quiz
            $query_cari_topik =  "SELECT DISTINCT topik_id, topik_nama, kog_quiz_persen, kog_test_persen, kog_ass_persen, psi_quiz_persen, psi_test_persen, psi_ass_persen
                        FROM kog_psi
                        LEFT JOIN siswa
                        ON kog_psi_siswa_id = siswa_id
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        WHERE siswa_id_kelas = $kelas_id AND topik_mapel_id = $mapel_id
                        ORDER BY topik_id";

            $query_info3 = mysqli_query($conn, $query_cari_topik);

            $topik_nama = array();
            $kog_quiz_persen = array();
            $kog_test_persen = array();
            $kog_ass_persen = array();
            $psi_quiz_persen = array();
            $psi_test_persen = array();
            $psi_ass_persen = array();
            while($row = mysqli_fetch_array($query_info3)){
                array_push($topik_nama, $row['topik_nama']);

                array_push($kog_quiz_persen, $row['kog_quiz_persen']);
                array_push($kog_test_persen, $row['kog_test_persen']);
                array_push($kog_ass_persen, $row['kog_ass_persen']);

                array_push($psi_quiz_persen, $row['psi_quiz_persen']);
                array_push($psi_test_persen, $row['psi_test_persen']);
                array_push($psi_ass_persen, $row['psi_ass_persen']);
            }
            
            //cari nilai pertopiknya
            $query =   "SELECT siswa_nama_depan, siswa_nama_belakang, GROUP_CONCAT(kog_quiz ORDER BY topik_id)as kum_k_quiz, GROUP_CONCAT(kog_ass ORDER BY topik_id)as kum_k_ass, GROUP_CONCAT(kog_test ORDER BY topik_id) as kum_k_test, GROUP_CONCAT(psi_quiz ORDER BY topik_id)as kum_p_quiz, GROUP_CONCAT(psi_ass ORDER BY topik_id)as kum_p_ass, GROUP_CONCAT(psi_test ORDER BY topik_id) as kum_p_test
                        FROM kog_psi
                        LEFT JOIN siswa
                        ON kog_psi_siswa_id = siswa_id
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        WHERE siswa_id_kelas = $kelas_id AND topik_mapel_id = $mapel_id
                        GROUP BY siswa_id
                        ORDER BY siswa_id";

            $query_info = mysqli_query($conn, $query);

            echo "<h4 class='text-center mb-3 mt-5'><u>Laporan Hasil Test, Assignment, dan Quiz</u></h4>";
            echo "<table class='rapot mt-3'>
                    <tr>
                        <th rowspan='3' style='vertical-align: bottom; width: 30px;'>No</th>
                        <th rowspan='3' style='vertical-align: bottom;'>Nama</th>";
                for($i=0;$i<count($topik_nama);$i++){
                    echo"<th colspan='6'>".$topik_nama[$i]."</th>";
                }
            echo "</tr>";
            echo "<tr>";
                for($i=0;$i<count($topik_nama);$i++){
                    echo"<th colspan='3'>Pengetahuan</th>
                        <th colspan='3'>Ketrampilan</th>";
                }
            echo "</tr>";
            echo "<tr>";
                for($i=0;$i<count($topik_nama);$i++){
                    echo"<th style='width: 30px;'>Q(".$kog_quiz_persen[$i]."%)</th>
                        <th style='width: 30px;'>T(".$kog_test_persen[$i]."%)</th>
                        <th style='width: 30px;'>A(".$kog_ass_persen[$i]."%)</th>
                        <th style='width: 30px;'>Q(".$psi_quiz_persen[$i]."%)</th>
                        <th style='width: 30px;'>T(".$psi_test_persen[$i]."%)</th>
                        <th style='width: 30px;'>A(".$psi_ass_persen[$i]."%)</th>";
                }
            echo "</tr>";
            
            $no = 1;
            while($row2 = mysqli_fetch_array($query_info)){

                $kum_k_quiz = explode(",", $row2['kum_k_quiz']);
                $kum_k_test = explode(",", $row2['kum_k_test']);
                $kum_k_ass = explode(",", $row2['kum_k_ass']);
                $kum_p_quiz = explode(",", $row2['kum_p_quiz']);
                $kum_p_test = explode(",", $row2['kum_p_test']);
                $kum_p_ass = explode(",", $row2['kum_p_ass']);
                $nama_belakang = $row2['siswa_nama_belakang'];

                echo "<tr>
                      <td style='padding: 0px 0px 0px 5px;'>$no</td>";

                if(strlen($nama_belakang) > 0){
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$row2['siswa_nama_depan']} $nama_belakang[0]</td>";
                }else{
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$row2['siswa_nama_depan']}</td>";
                }

                for($i=0;$i<count($topik_nama);$i++){
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$kum_k_quiz[$i]}</td>";
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$kum_k_test[$i]}</td>";
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$kum_k_ass[$i]}</td>";
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$kum_p_quiz[$i]}</td>";
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$kum_p_test[$i]}</td>";
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$kum_p_ass[$i]}</td>";
                }
                echo"</tr>";
                $no++;
            }

            echo "</table>";


            //NILAI AFEKTIF
            $query_af = "SELECT siswa_nama_depan, siswa_nama_belakang, GROUP_CONCAT(k_afektif_bulan ORDER BY k_afektif_id) as bulan, COUNT(k_afektif_bulan) as jumlah_bulan, GROUP_CONCAT(afektif_nilai ORDER BY k_afektif_id) as nilai
                        FROM afektif
                        LEFT JOIN siswa
                        ON afektif_siswa_id = siswa_id
                        LEFT JOIN k_afektif
                        ON afektif_k_afektif_id = k_afektif_id
                        WHERE siswa_id_kelas = $kelas_id AND afektif_mapel_id = $mapel_id
                        GROUP BY siswa_id
                        ORDER BY siswa_nama_depan";

            $query_info = mysqli_query($conn, $query_af);

            echo "<h4 class='text-center mb-3 mt-5'><u>Laporan Hasil Afektif</u></h4>";
            echo "<table class='rapot mt-3'>
                  <tr>
                        <th style='vertical-align: bottom; width: 30px;'>No</th>
                        <th>Nama</th>
                        <th>Afektif Akhir Raport</th>";
            echo "</tr>";
            
            $no = 1;
            while($row2 = mysqli_fetch_array($query_info)){

                //$kum_p_ass = explode(",", $row2['kum_p_ass']);
                $nama_belakang = $row2['siswa_nama_belakang'];
                $jumlah_bulan = $row2['jumlah_bulan'];

                //kumpulan nilai afektif
                $afektif_nilai = explode(",", $row2['nilai']);
                $jumlah_bul = $row2['jumlah_bulan'];

                echo "<tr>
                      <td style='padding: 0px 0px 0px 5px;'>$no</td>";

                if(strlen($nama_belakang) > 0){
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$row2['siswa_nama_depan']} $nama_belakang[0]</td>";
                }else{
                    echo"<td style='padding: 0px 0px 0px 5px;'>{$row2['siswa_nama_depan']}</td>";
                }

                echo "<td style='padding: 0px 0px 0px 5px;'>".return_abjad_afek(return_total_nilai_afektif_bulan($afektif_nilai)/$jumlah_bul)."</td>";
                echo"</tr>";
                $no++;
            }

            echo "</table>";
        }
    }
    
?>

<script>
$(document).ready(function(){
    $("#container-analisis").hide();
    $(".link-formative").on('click', function(){
        //$("#container-siswa").show();
        
        var mapel_id = $(this).attr("rel");
        var siswa_id = $(this).attr("rel2");
        
        // alert(mapel_id);
        // alert(siswa_id);

        $.post("rapot_waka/display_analisis_permapel.php",{mapel_id: mapel_id, siswa_id: siswa_id}, function(data){
            $("#container-analisis").show();
            $("#container-analisis").html(data);
        });
        
    });

    $(".link-formative-psi").on('click', function(){
        //$("#container-siswa").show();
        
        var mapel_id = $(this).attr("rel");
        var siswa_id = $(this).attr("rel2");
        

        $.post("rapot_waka/display_analisis_permapel_psi.php",{mapel_id: mapel_id, siswa_id: siswa_id}, function(data){
            $("#container-analisis").show();
            $("#container-analisis").html(data);
        });
        
    });
});
</script>