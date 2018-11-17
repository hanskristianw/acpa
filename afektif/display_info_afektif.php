<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");

    
    function hitung_afektif($string) {
        $nilai_perminggu = explode('/', $string);
        $hasil = 0;
        $pembagi = 0;
        
        //3_3_3 sebanyak 5x
        $len=count($nilai_perminggu);
        if($len>0){
            for ($i=0;$i<$len;$i++){
                //333
                $nilai_perhari = explode('_', $nilai_perminggu[$i]);
                $len2=count($nilai_perhari);
                for ($j=0;$j<$len2;$j++){
                    $hasil += $nilai_perhari[$j]*10;
                    if($nilai_perhari[0]!=0 && $j==0){
                        $pembagi += 1;
                    }
                }
            }
            return $hasil/$pembagi;
        }
        else{
            return 0;
        }
        
    }
    
    if(!empty($_POST["option_bulan_afektif"])) {
        
        $pilihan = $_POST["kriteria_bulan_option"];
        
        if($pilihan == 1)
        {
            
            //murid dengan nilai <60
            $query = "SELECT k_afektif_1, k_afektif_2, k_afektif_3, k_afektif_id
                FROM k_afektif, t_ajaran 
                WHERE k_afektif_t_ajaran_id = t_ajaran_id AND
                    t_ajaran_active = 1 AND 
                    k_afektif_bulan = {$_POST["option_bulan_afektif"]}";

            $query_k_afektif_info = mysqli_query($conn, $query);

            if(!$query_k_afektif_info){
                die("QUERY FAILED".mysqli_error($conn));
            }

            echo "<thead>
                    <tr>
                        <th>Indikator 1</th>
                        <th>Indikator 2</th>
                        <th>Indikator 3</th>
                    </tr>
                </thead>
            <tbody >";


            while($row = mysqli_fetch_array($query_k_afektif_info)){
                echo "<input type='hidden' name='input_afektif_id' id='input_afektif_id' value='{$row['k_afektif_id']}'>";
                echo"<tr>";
                echo"<td>{$row['k_afektif_1']}</td>";
                echo"<td>{$row['k_afektif_2']}</td>";
                echo"<td>{$row['k_afektif_3']}</td>";
                echo"</tr>";
            }

            echo "</tbody>";
        }
        else if($pilihan == 2){
            //rekap nilai afektif
            
            $afektif_bulan = $_POST["option_bulan_afektif"];
            $kelas_id = $_POST["option_kelas"];
            
            //cari mapel id dan mapel nama pada tahun ajaran aktif
            
            $mapel_id_arr = array();
            $mapel_nama_arr = array();
            $query_mapel = "SELECT mapel_id, mapel_nama, mapel_nama_singkatan
                            FROM mapel, d_mapel 
                            WHERE mapel_id = d_mapel_id_mapel AND d_mapel_id_kelas = {$kelas_id}";
                    
            $query_mapel_info = mysqli_query($conn, $query_mapel);        
            if(!$query_mapel_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row_mapel = mysqli_fetch_array($query_mapel_info)){
                array_push($mapel_id_arr, $row_mapel['mapel_id']);
                array_push($mapel_nama_arr, $row_mapel['mapel_nama_singkatan']);
            }
            
            $query_concat = "";
            $len=count($mapel_id_arr);
            
            for ($i=0;$i<$len;$i++){
               $query_concat .= "GROUP_CONCAT(IF(afektif_mapel_id = {$mapel_id_arr[$i]}, afektif_nilai, '') SEPARATOR '') AS '{$mapel_nama_arr[$i]}'";
               if($i != $len-1){
                   $query_concat .= ",";
               }
            }
            
            //rekap nilai afektif
            
            $query =    "SELECT afektif_siswa_id, siswa_nama_depan, siswa_nama_belakang, siswa_id_kelas,
                        $query_concat
                        FROM afektif
                        LEFT JOIN siswa
                                on afektif_siswa_id = siswa_id
                        LEFT JOIN k_afektif
                                on k_afektif_id = afektif_k_afektif_id
                        LEFT JOIN kelas
                                on siswa_id_kelas = kelas_id
                        WHERE k_afektif_bulan = {$afektif_bulan} AND kelas_id = {$kelas_id}
                        GROUP BY afektif_siswa_id";

            //echo $query;
            $query_k_afektif_info = mysqli_query($conn, $query);

            if(!$query_k_afektif_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            
            $data_header = "";
            for ($i=0;$i<$len;$i++){
               $data_header .= "<th colspan='2' class='text-center'>{$mapel_nama_arr[$i]}</th>";
            }
            
            echo "<thead>
                    <tr>
                        <th>Nama</th>
                        $data_header
                        <th colspan='2'>Mean</th>
                    </tr>
                </thead>
            <tbody >";
            
            $rekap_nilai = array();
            $tampung_nama_siswa = array();
            while($row = mysqli_fetch_array($query_k_afektif_info)){
                $snb = $row['siswa_nama_belakang'];
                echo"<tr>";
                if(strlen($snb) > 0){
                    echo"<td>{$row['siswa_nama_depan']} $snb[0]</td>";
                    $nama_lengkap = "{$row['siswa_nama_depan']} $snb[0]";
                    array_push($tampung_nama_siswa,$nama_lengkap);
                }else{
                    echo"<td>{$row['siswa_nama_depan']}</td>";
                    array_push($tampung_nama_siswa,$row['siswa_nama_depan']);
                }
                $total_nilai = 0;
                $pembagi_total = 0;
                for ($i=0;$i<$len;$i++){
                    $temp = hitung_afektif($row[$mapel_nama_arr[$i]]);
                    
                    if($temp > 0){
                        $total_nilai+=$temp;
                        $pembagi_total +=1;
                        
                        $temp = round($temp,2);
                        if($temp>80){
                            echo"<td class='text-center'><a class='link-afek' rel='".$row['siswa_nama_depan']." ".$row['siswa_nama_belakang']."' rel4='".$row[$mapel_nama_arr[$i]]."' href='javascript:void(0)' style='text-decoration:none; color:inherit;'>{$temp}</a></td>";
                            echo"<td class='text-center'>A</td>";
                        }elseif($temp>=65){
                            echo"<td class='text-center'><a class='link-afek' rel='".$row['siswa_nama_depan']." ".$row['siswa_nama_belakang']."' rel4='".$row[$mapel_nama_arr[$i]]."' href='javascript:void(0)' style='text-decoration:none; color:inherit;'>{$temp}</a></td>";
                            echo"<td class='text-center'>B</td>";
                        }elseif($temp>31){
                            echo"<td class='table-danger text-center'><a class='link-afek' rel='".$row['siswa_nama_depan']." ".$row['siswa_nama_belakang']."' rel4='".$row[$mapel_nama_arr[$i]]."' href='javascript:void(0)' style='text-decoration:none; color:inherit;'>{$temp}</a></td>";
                            echo"<td class='table-danger text-center'>C</td>";
                        }else{
                            echo"<td class='table-danger text-center'><a class='link-afek' rel='".$row['siswa_nama_depan']." ".$row['siswa_nama_belakang']."' rel4='".$row[$mapel_nama_arr[$i]]."' href='javascript:void(0)' style='text-decoration:none; color:inherit;'>{$temp}</a></td>";
                            echo"<td class='table-danger text-center'>D</td>";
                        }
                    }
                    else{
                        echo"<td class='text-center'>-</td>";
                        echo"<td class='text-center'>-</td>";
                    }
                }
                $total_nilai = round($total_nilai/$pembagi_total,2);
                
                array_push($rekap_nilai,$total_nilai);
                if($total_nilai>80){
                    echo"<td class='text-center'>{$total_nilai}</td>";
                    echo"<td class='text-center'>A</td>";
                }elseif($total_nilai>=65){
                    echo"<td class='text-center'>{$total_nilai}</td>";
                    echo"<td class='text-center'>B</td>";
                }elseif($total_nilai>31){
                    echo"<td class='table-danger text-center'>{$total_nilai}</td>";
                    echo"<td class='table-danger text-center'>C</td>";
                }else{
                    echo"<td class='table-danger text-center'>{$total_nilai}</td>";
                    echo"<td class='table-danger text-center'>D</td>";
                }
                echo"</tr>";
            }
            //cari nilai tertinggi
            $tertinggi = max($rekap_nilai);
            
            $kolom = $len*2+2;
            echo "<tr style='border: 3px solid #ddd; border-top: 3px double #ddd;;'><td><b>Nilai Tertinggi<b></td><td style='text-align:center;vertical-align:middle' colspan='$kolom'>";
            //cari siswa mana yang tertinggi
            //bandingkan yang tertinggi dengan nilai total semua siswa
            $index_siswa = array();
            
            for($j=0;$j<count($rekap_nilai);$j++){
                if($tertinggi==$rekap_nilai[$j]){
                    array_push($index_siswa,$j);
                }
            }
            for($k=0;$k<count($index_siswa);$k++){
                echo $tampung_nama_siswa[$index_siswa[$k]];
                echo "(".$tertinggi.")";
                if($k != count($index_siswa)-1){
                    echo ", ";
                }
                else{
                    echo ".";
                }
            }
            echo "</td></tr>";
            echo "</tbody>";
            
        }
        elseif ($pilihan == 3){
            $afektif_bulan = $_POST["option_bulan_afektif"];
            
            $nama_bulan = "";
            if($afektif_bulan == 1){$nama_bulan = "Januari";}
            elseif($afektif_bulan == 2){$nama_bulan = "Februari";}
            elseif($afektif_bulan == 3){$nama_bulan = "Maret";}
            elseif($afektif_bulan == 4){$nama_bulan = "April";}
            elseif($afektif_bulan == 5){$nama_bulan = "Mei";}
            elseif($afektif_bulan == 6){$nama_bulan = "Juni";}
            elseif($afektif_bulan == 7){$nama_bulan = "Juli";}
            elseif($afektif_bulan == 8){$nama_bulan = "Agustus";}
            elseif($afektif_bulan == 9){$nama_bulan = "September";}
            elseif($afektif_bulan == 10){$nama_bulan = "Oktober";}
            elseif($afektif_bulan == 11){$nama_bulan = "November";}
            elseif($afektif_bulan == 12){$nama_bulan = "Desember";}
            
            //cari yang sudah isi pada semua kelas
            $query_mapel = "SELECT * 
                            FROM (SELECT kelas_id, kelas_nama, GROUP_CONCAT(mapel_id) as mapel_id_seluruh, GROUP_CONCAT(mapel_nama_singkatan) as ms
                                  FROM kelas
                                  LEFT JOIN t_ajaran
                                  ON kelas_t_ajaran_id = t_ajaran_id
                                  LEFT JOIN d_mapel
                                  ON kelas_id = d_mapel_id_kelas
                                  LEFT JOIN mapel
                                  ON d_mapel_id_mapel = mapel_id
                                  WHERE t_ajaran_active = 1
                                  GROUP BY kelas_id) AS A
                            LEFT JOIN (SELECT kelas_id, GROUP_CONCAT(DISTINCT mapel_id) as mapel_sudah
                                  FROM afektif
                                  LEFT JOIN siswa
                                  ON afektif_siswa_id = siswa_id
                                  LEFT JOIN kelas
                                  ON siswa_id_kelas = kelas_id
                                  LEFT JOIN k_afektif
                                  ON afektif_k_afektif_id = k_afektif_id
                                  LEFT JOIN mapel
                                  ON afektif_mapel_id = mapel_id
                                  LEFT JOIN t_ajaran
                                  ON mapel_t_ajaran_id = t_ajaran_id
                                  WHERE t_ajaran_active = 1 AND k_afektif_bulan = $afektif_bulan
                                  GROUP BY kelas_id) AS B
                            ON A.kelas_id=B.kelas_id
                            ORDER BY A.kelas_id";
                    
            $query_mapel_info = mysqli_query($conn, $query_mapel);        
            if(!$query_mapel_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            
            echo "<thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Mapel yang belum mengisi afektif bulan $nama_bulan</th>
                    </tr>
                </thead>
            <tbody >";
            
            $mapel_id_semua = array();
            $mapel_id_sudah = array();
            $mapel_nama = array();
            $kelas_nama = array();
            while($row_mapel = mysqli_fetch_array($query_mapel_info)){
                array_push($kelas_nama, $row_mapel['kelas_nama']);
                array_push($mapel_id_semua, $row_mapel['mapel_id_seluruh']);
                array_push($mapel_id_sudah, $row_mapel['mapel_sudah']);
                array_push($mapel_nama, $row_mapel['ms']);
            }
            
            for($i=0;$i<count($mapel_id_semua);$i++){
                echo"<tr>";
                echo "<td>".$kelas_nama[$i]."</td>";
                
                //lakukan proses memilah disini
                $pisah_mapel_semua = explode(",",$mapel_id_semua[$i]);
                $pisah_mapel_sudah = explode(",",$mapel_id_sudah[$i]);
                $pisah_singkat_sudah = explode(",",$mapel_nama[$i]);
                
                echo "<td>";
                $str_hasil = "";
                for($j=0;$j<count($pisah_mapel_semua);$j++){
                    $sama = 0;
                    for($k=0;$k<count($pisah_mapel_sudah);$k++){
                        if($pisah_mapel_semua[$j]==$pisah_mapel_sudah[$k]){
                            $sama = 1;
                        }
                    }
                    if($sama == 0){
                        $str_hasil .= $pisah_singkat_sudah[$j];
                        $str_hasil .= ", ";
                    }
                    
                }
                echo substr($str_hasil, 0, -2); 
                echo "</td>";
                echo"</tr>";
            }
            
            
        }
        
    }
?>

<script>
    $(document).ready(function(){
        
        $(".link-afek").on('click', function(){

            $("#myModal2").show();
    //        
            var detail_nilai = $(this).attr("rel4");
            var nama_lengkap = $(this).attr("rel");

            var nilai_minggu = detail_nilai.split("/");
            
//            for (i = 0; i < nilai_minggu.length; i++) { 
//                alert(nilai_minggu[i]);
//            }
            
            //alert(nilai_minggu.length);
            
            var i,j;
            var text = "";
            var nilai_topik;
            var cekaktif;
            var mingguaktif = 0;
            var nilai_sementara = 0;
            var nilai_total = 0;
            var nilai_akhir = 0;
            for (i = 1; i <= nilai_minggu.length; i++) {
                cekaktif = 0;
                nilai_sementara = 0;
                nilai_topik = nilai_minggu[i-1].split("_");
                
                text += "<br><b>Minggu " + i + ":</b> ";
                for (j = 0; j < nilai_topik.length; j++){
                    if(nilai_topik[j]!=0){
                        cekaktif +=1;
                    }
                    text += nilai_topik[j] + " ";
                    nilai_sementara += parseInt(nilai_topik[j]);
                }
                text += "<br>";
                
                if(cekaktif == 3){
                    mingguaktif +=1;
                    nilai_total += nilai_sementara;
                }
            }
            
            nilai_total *= 10;
            nilai_akhir = parseInt(nilai_total)/parseInt(mingguaktif);
            
            var cetak = '<div class="text-center"><b><u>' + nama_lengkap + '</u></b></div>' + text + '<br>----------------------------<br><b>Total Minggu Aktif: </b>' + mingguaktif + '<br><b>Total Nilai: </b>' + nilai_total + '<br><b>Nilai Akhir: </b>' + nilai_total + '/' + mingguaktif + '=' + nilai_akhir;
            
            $("#myAlertbox").html(cetak);
            //alert(mapel_nama);
            
    //        
    //        //passing id kelas dan id guru
    //        $.post("kelas/proses_kelas.php",{id: id, id2:id2, id3:id3}, function(data){
    //            $("#container-kelas").html(data);
    //        });

        });
    });

</script>