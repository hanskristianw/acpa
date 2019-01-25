<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        
        if($_POST['option_siswa']){

            include ("../includes/db_con.php");
            include ("../includes/fungsi_lib.php");
    
            $siswa_id = explode(",",$_POST['option_siswa']);
            $siswa_id1 = $siswa_id[0];
            $siswa_id2 = $siswa_id[1];
            
            // echo $siswa_id1."<br>";
            // echo $siswa_id2;

            // mysqli_query($conn, "SET group_concat_max_len=15000"); 

            // //laporan nilai akhir raport
            // $query_dkn =
            //     'SELECT urutan.siswa_no_induk, urutan.siswa_nama_depan, urutan.siswa_nama_belakang, urutan.mapel_nama, urutan.mapel_kkm, urutan.kelas_nama, for_kog, for_psi, sum_kog, sum_psi, mapel_persen_for, mapel_persen_sum, mapel_persen_for_psi, mapel_persen_sum_psi, mapel_persen_kog, mapel_persen_psi, afektif_total, jumlah_bulan
            //     FROM 
            //    (
            //        SELECT siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, GROUP_CONCAT(mapel_nama_singkatan ORDER BY mapel_urutan) as mapel_nama, GROUP_CONCAT(mapel_kkm ORDER BY mapel_urutan) as mapel_kkm, kelas_nama
            //                    FROM kelas
            //                    LEFT JOIN d_mapel
            //                    ON kelas_id = d_mapel_id_kelas
            //                    LEFT JOIN mapel
            //                    ON d_mapel_id_mapel = mapel_id
            //                    LEFT JOIN siswa
            //                    ON siswa_id_kelas = kelas_id
            //                    WHERE kelas_id IN ('.$kelas_sem1.')
            //                    GROUP BY siswa_no_induk
            //                    ORDER BY siswa_no_induk    
                   
            //    )as urutan
            //    JOIN
            //    (
            //        SELECT siswa_no_induk, siswa_nama_depan, GROUP_CONCAT(mapel_urutan ORDER BY mapel_urutan) as mapel_urutan, GROUP_CONCAT(mapel_id ORDER BY mapel_urutan) as mapel_id, GROUP_CONCAT(mapel_nama_singkatan ORDER BY mapel_urutan) as mapel_nama, GROUP_CONCAT(for_kog ORDER BY mapel_urutan) as for_kog, GROUP_CONCAT(for_psi ORDER BY mapel_urutan) as for_psi 
            //        FROM (
            //                    SELECT siswa_no_induk, siswa_nama_depan, mapel_id, mapel_urutan, mapel_nama_singkatan,
            //                    ROUND(SUM(ROUND(kog_quiz*kog_quiz_persen/100 + kog_ass*kog_ass_persen/100 + kog_test*kog_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
            //                    AS for_kog,
            //                    ROUND(SUM(ROUND(psi_quiz*psi_quiz_persen/100 + psi_ass*psi_ass_persen/100 + psi_test*psi_test_persen/100,0))/COUNT(DISTINCT kog_psi_topik_id),0)
            //                    AS for_psi
            //                    FROM kog_psi 
            //                    LEFT JOIN topik
            //                    ON kog_psi_topik_id = topik_id
            //                    LEFT JOIN mapel
            //                    ON topik_mapel_id = mapel_id
            //                    LEFT JOIN siswa
            //                    ON kog_psi_siswa_id = siswa_id
            //                    LEFT JOIN kelas
            //                    ON siswa_id_kelas = kelas_id
            //                    WHERE siswa_id_kelas = '.$kelas_sem1.'
            //                    GROUP BY mapel_nama, kog_psi_siswa_id
            //                    ORDER BY siswa_no_induk, mapel_urutan
            //        ) as formative
            //        GROUP BY siswa_no_induk
            //    ) as formative_final ON urutan.siswa_no_induk = formative_final.siswa_no_induk
            //    JOIN(
            //        SELECT siswa_no_induk, siswa_nama_depan,
            //        GROUP_CONCAT(mapel_id ORDER BY mapel_urutan) as mapel_id, 
            //        GROUP_CONCAT(mapel_urutan ORDER BY mapel_urutan) as mapel_urutan, 
            //        GROUP_CONCAT(mapel_nama_singkatan ORDER BY mapel_urutan) as mapel_nama_singkatan,
            //        GROUP_CONCAT(mapel_persen_for ORDER BY mapel_urutan) as mapel_persen_for,
            //        GROUP_CONCAT(mapel_persen_sum ORDER BY mapel_urutan) as mapel_persen_sum,
            //        GROUP_CONCAT(mapel_persen_for_psi ORDER BY mapel_urutan) as mapel_persen_for_psi,
            //        GROUP_CONCAT(mapel_persen_sum_psi ORDER BY mapel_urutan) as mapel_persen_sum_psi,
            //        GROUP_CONCAT(mapel_persen_kog ORDER BY mapel_urutan) as mapel_persen_kog,
            //        GROUP_CONCAT(mapel_persen_psi ORDER BY mapel_urutan) as mapel_persen_psi,
            //        GROUP_CONCAT(sum_kog ORDER BY mapel_urutan) as sum_kog,
            //        GROUP_CONCAT(sum_psi ORDER BY mapel_urutan) as sum_psi
            //        FROM(
               
            //            SELECT siswa_no_induk, siswa_nama_depan, mapel_id, mapel_urutan, mapel_nama_singkatan, 
            //                                        mapel_persen_for, mapel_persen_sum, 
            //                                        mapel_persen_for_psi, mapel_persen_sum_psi,
            //                                        mapel_persen_kog, mapel_persen_psi,
            //                                        ROUND((kog_uts * kog_uts_persen + kog_uas * kog_uas_persen) /100,0) as sum_kog,
            //                                        ROUND((psi_uts * psi_uts_persen + psi_uas * psi_uas_persen) /100,0) as sum_psi
            //            FROM kog_psi_ujian
            //            LEFT JOIN mapel
            //            ON kog_psi_ujian_mapel_id = mapel_id
            //            LEFT JOIN siswa
            //            ON kog_psi_ujian_siswa_id = siswa_id
            //            WHERE siswa_id_kelas = '.$kelas_sem1.'
            //        )AS summative
            //        GROUP BY siswa_no_induk    
            //    ) as summative_final ON urutan.siswa_no_induk = summative_final.siswa_no_induk
            //    JOIN(
            //        SELECT siswa_no_induk, siswa_nama_depan, GROUP_CONCAT(mapel_id ORDER BY mapel_urutan) as mapel_id, GROUP_CONCAT(afektif_nilai ORDER BY mapel_urutan SEPARATOR ".") as afektif_total, GROUP_CONCAT(jumlah_bulan ORDER BY mapel_urutan) as jumlah_bulan
            //        FROM
            //        (
            //            SELECT siswa_no_induk, siswa_nama_depan, mapel_id, mapel_urutan, count(afektif_nilai) as jumlah_bulan, mapel_nama, GROUP_CONCAT(afektif_nilai ORDER BY mapel_urutan) as afektif_nilai
            //            FROM afektif
            //            LEFT JOIN mapel
            //            ON afektif_mapel_id = mapel_id
            //            LEFT JOIN siswa
            //            ON afektif_siswa_id = siswa_id
            //            WHERE siswa_id_kelas = '.$kelas_sem1.'
            //            GROUP BY siswa_no_induk, mapel_id
            //            ORDER BY siswa_nama_depan, mapel_urutan
            //        )AS afektif_total
            //        GROUP BY siswa_no_induk
            //    )as afektif_final ON urutan.siswa_no_induk = afektif_final.siswa_no_induk';


            // $query_dkn = mysqli_query($conn, $query_dkn);
            // $rowss = mysqli_fetch_row($query_dkn);
            // $nama_mapel_array = $rowss[3];
            // $mapel_kkm_array = $rowss[4];
            // $kelas_nama = $rowss[5];
            
            // mysqli_data_seek($query_dkn, 0);
           
            // $nama_mapel = explode(",",$nama_mapel_array);
            // $mapel_kkm = explode(",",$mapel_kkm_array);

            // echo "<div id='print_area'>";

            echo "<h6 class='text-center mb-3 mt-5'>LAPORAN PENILAIAN HASIL BELAJAR SISWA</h6>";
            
            // echo "<h6>KELAS: ".$kelas_nama."</h6>";

            // echo "<table class='rapot mt-3'>
            //         <tr>
            //             <th rowspan='2' style='font-size: 9px !important;'>No</th>
            //             <th rowspan='2' style='font-size: 9px !important;'>No Induk</th>
            //             <th rowspan='2' style='font-size: 9px !important;'>Nama Siswa</th>";
            //     //nama mapel            
            //     for($i=0;$i<count($nama_mapel);$i++){
            //         echo"<th style='font-size: 9px !important;'>".$mapel_kkm[$i]."</th>";
            //         echo"<th colspan='3' style='font-size: 9px !important;'>".$nama_mapel[$i]."</th>";
            //     }
            //     echo"<th colspan='3' style='font-size: 9px !important;'>Total</th>";
            //     echo"<th rowspan='2' style='font-size: 9px !important;'>Rank</th>";
            // echo "</tr>";
            
            // echo "<tr>";
            // for($i=0;$i<count($nama_mapel);$i++){
            //     echo "<td style='font-size: 9px !important; text-align: center;'>C</td>
            //         <td style='font-size: 9px !important; text-align: center;'>P</td>
            //         <td style='font-size: 9px !important; text-align: center;'>F</td>
            //         <td style='font-size: 9px !important; text-align: center;'>A</td>";
            // }
            // echo "<td style='font-size: 9px !important; text-align: center;'>C</td>
            //         <td style='font-size: 9px !important; text-align: center;'>P</td>
            //         <td style='font-size: 9px !important; text-align: center;'>F</td>";
            // echo "</tr>";
            
            
            // $no = 1;

            // $no_array = array();
            // $no_induk = array();
            // $nama = array();
            // $jumlah_mapel = count($nama_mapel);
            // $data = array();
            
            // $data_total = array();
            // $rank = array();
            // $rank_sort = array();

            // while($row2 = mysqli_fetch_array($query_dkn)){
            //     $nama_belakang = $row2['siswa_nama_belakang'];
            //     if(strlen($nama_belakang) > 0){
            //         $nama_siswa = $row2['siswa_nama_depan'] . " " . $nama_belakang[0];
            //     }else{
            //         $nama_siswa = $row2['siswa_nama_depan'];
            //     }

            //     array_push($no_array, $no);
            //     array_push($nama, $nama_siswa);
            //     array_push($no_induk, $row2[0]);

            //     // echo "<tr>
            //     //     <td style='padding: 0px 0px 0px 5px;'>$no</td>
            //     //     <td style='padding: 0px 0px 0px 5px;'>{$row2[0]}</td>
            //     //     <td style='padding: 0px 0px 0px 5px;'>$nama_siswa</td>";

            //     //kognitif (for_kog * mapel_persen_for + sum_kog * mapel_persen_sum)
            //     $for_kog = explode(",",$row2['for_kog']);
            //     $mapel_persen_for = explode(",",$row2['mapel_persen_for']);
            //     $sum_kog = explode(",",$row2['sum_kog']);
            //     $mapel_persen_sum = explode(",",$row2['mapel_persen_sum']);
            //     //psikomotor (for_psi * mapel_persen_for_psi + sum_psi * mapel_persen_sum_psi)
            //     $for_psi = explode(",",$row2['for_psi']);
            //     $mapel_persen_for_psi = explode(",",$row2['mapel_persen_for_psi']);
            //     $sum_psi = explode(",",$row2['sum_psi']);
            //     $mapel_persen_sum_psi = explode(",",$row2['mapel_persen_sum_psi']);
            //     //persentase
            //     $mapel_persen_kog = explode(",",$row2['mapel_persen_kog']);
            //     $mapel_persen_psi = explode(",",$row2['mapel_persen_psi']);
            //     //afektif permapel
            //     $afektif_mapel = explode(".",$row2['afektif_total']);
            //     $jumlah_bulan = explode(",",$row2['jumlah_bulan']);

            //     $total_kog = 0;
            //     $total_psi = 0;
            //     $total_final = 0;
            //     for($j=0;$j<count($nama_mapel);$j++){
            //         $kognitif = round($for_kog[$j] * $mapel_persen_for[$j] + $sum_kog[$j] * $mapel_persen_sum[$j]);
            //         $psikomotor = round($for_psi[$j] * $mapel_persen_for_psi[$j] + $sum_psi[$j] * $mapel_persen_sum_psi[$j]);
            //         $n_akhir = round($kognitif * $mapel_persen_kog[$j] + $psikomotor * $mapel_persen_psi[$j]);
            //         //echo "<td style='text-align: center;'>".$for_kog[$j]."*".$mapel_persen_for[$j]."+".$sum_kog[$j]."*".$mapel_persen_sum[$j]."=".round($for_kog[$j] * $mapel_persen_for[$j] + $sum_kog[$j] * $mapel_persen_sum[$j])."</td>";
            //         // echo "<td style='text-align: center;'>".$kognitif."</td>";
            //         //echo "<td style='text-align: center;'>".$for_psi[$j]."*".$mapel_persen_for_psi[$j]."+".$sum_psi[$j]."*".$mapel_persen_sum_psi[$j]."=".round($for_psi[$j] * $mapel_persen_for_psi[$j] + $sum_psi[$j] * $mapel_persen_sum_psi[$j])."</td>";
            //         // echo "<td style='text-align: center;'>".$psikomotor."</td>";
            //         // echo "<td style='text-align: center;'>".$n_akhir."</td>";

            //         $total_kog += $kognitif;
            //         $total_psi += $psikomotor;
            //         $total_final += $n_akhir;

            //         //afektif minggu dipisahkan koma
            //         $nilai_perbulan = explode(',', $afektif_mapel[$j]);

                    
            //         array_push($data, $kognitif);
            //         array_push($data, $psikomotor);
            //         array_push($data, $n_akhir);
            //         array_push($data, return_abjad_afek(return_total_nilai_afektif_bulan($nilai_perbulan)/$jumlah_bulan[$j]));
                    
            //         // echo "<td style='text-align: center;'>".return_abjad_afek(return_total_nilai_afektif_bulan($nilai_perbulan)/$jumlah_bulan[$j])."</td>";
            //     }

            //     array_push($data_total, $total_kog);
            //     array_push($data_total, $total_psi);
            //     array_push($data_total, $total_final);

            //     array_push($rank, $total_final);
            //     array_push($rank_sort, $total_final);
                
            //     $no++;
            // }

            // $index = 0;
            // $index_total = 0;
            // rsort($rank_sort);
            // for($j=0;$j<count($nama);$j++){
            //     echo "<tr>
            //         <td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>{$no_array[$j]}</td>
            //         <td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>{$no_induk[$j]}</td>
            //         <td style='padding: 0px 0px 0px 5px; font-size: 10px !important;'>{$nama[$j]}</td>";
            //     for($k=0;$k<count($nama_mapel);$k++){
            //         echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data[$index]."</td>";
            //         $index++;
            //         echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data[$index]."</td>";
            //         $index++;
            //         echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data[$index]."</td>";
            //         $index++;
            //         echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data[$index]."</td>";
            //         $index++;
            //     }
            //     echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data_total[$index_total]."</td>";
            //     $index_total++;
            //     echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data_total[$index_total]."</td>";
            //     $index_total++;
            //     echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".$data_total[$index_total]."</td>";
            //     $index_total++;

            //     echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>".return_rank($rank_sort,$rank[$j])."</td>";
            //     echo "</tr>";
            // }

            // echo "</table>";
            
            // echo "</div>";

            // echo'<input type="button" name="print_dkn" id="print_dkn" class="btn btn-primary print_dkn mt-2" value="Print">';

            // echo'<input type="button" name="export_dkn" id="export_dkn" class="btn btn-success export_dkn mt-2 ml-2" value="Export To Excel">';
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