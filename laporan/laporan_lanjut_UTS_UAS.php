<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        
        if($_POST['option_kelas']){

            include ("../includes/db_con.php");
            include ("../includes/fungsi_lib.php");
    
            $kelas_id = $_POST['option_kelas'];
            
            mysqli_query($conn, "SET group_concat_max_len=15000"); 

            //laporan nilai akhir raport
            $query_dkn =
                'SELECT urutan.siswa_no_induk, urutan.siswa_nama_depan, urutan.siswa_nama_belakang, urutan.mapel_nama, urutan.mapel_kkm, urutan.kelas_nama, urutan.mapel_id, summative_final.mapel_id, kog_uts, kog_uas
                FROM 
               (
                   SELECT siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, GROUP_CONCAT(mapel_nama_singkatan ORDER BY mapel_urutan) as mapel_nama, GROUP_CONCAT(mapel_id ORDER BY mapel_urutan) as mapel_id, GROUP_CONCAT(mapel_kkm ORDER BY mapel_urutan) as mapel_kkm, kelas_nama
                   FROM kelas
                   LEFT JOIN d_mapel
                   ON kelas_id = d_mapel_id_kelas
                   LEFT JOIN mapel
                   ON d_mapel_id_mapel = mapel_id
                   LEFT JOIN siswa
                   ON siswa_id_kelas = kelas_id
                   WHERE kelas_id = '.$kelas_id.'
                   GROUP BY siswa_no_induk
                   ORDER BY siswa_no_induk    

               )as urutan
               JOIN(
                   SELECT siswa_no_induk, siswa_nama_depan,
                   GROUP_CONCAT(mapel_id ORDER BY mapel_urutan) as mapel_id, 
                   GROUP_CONCAT(mapel_urutan ORDER BY mapel_urutan) as mapel_urutan, 
                   GROUP_CONCAT(kog_uts ORDER BY mapel_urutan) as kog_uts,
                   GROUP_CONCAT(kog_uas ORDER BY mapel_urutan) as kog_uas
                   FROM(

                       SELECT siswa_no_induk, siswa_nama_depan, mapel_id, mapel_urutan, mapel_nama_singkatan, 
                       kog_uts, kog_uas
                       FROM kog_psi_ujian
                       LEFT JOIN mapel
                       ON kog_psi_ujian_mapel_id = mapel_id
                       LEFT JOIN siswa
                       ON kog_psi_ujian_siswa_id = siswa_id
                       WHERE siswa_id_kelas = '.$kelas_id.'
                   )AS summative
                   GROUP BY siswa_no_induk    
               ) as summative_final ON urutan.siswa_no_induk = summative_final.siswa_no_induk
               ';


            $query_dkn = mysqli_query($conn, $query_dkn);
            $rowss = mysqli_fetch_row($query_dkn);
            $nama_mapel_array = $rowss[3];
            $mapel_kkm_array = $rowss[4];
            $kelas_nama = $rowss[5];
            $mapel_id_fix_array = $rowss[6];
            $mapel_id_isi_array = $rowss[7];
            
            
            mysqli_data_seek($query_dkn, 0);
           
            $nama_mapel = explode(",",$nama_mapel_array);
            $mapel_kkm = explode(",",$mapel_kkm_array);
            $mapel_id_fix = explode(",",$mapel_id_fix_array);
            $mapel_id_isi = explode(",",$mapel_id_isi_array);

            echo "<div id='print_area'>";

            echo "<h6 class='text-center mb-3 mt-5'><u>SMA NATION STAR ACADEMY<br>DAFTAR UTS UAS</u></h6>";
            
            echo "<h6>KELAS: ".$kelas_nama."</h6>";

            echo "<table class='rapot mt-3'>
                    <tr>
                        <th rowspan='2' style='font-size: 9px !important;'>No</th>
                        <th rowspan='2' style='font-size: 9px !important;'>No Induk</th>
                        <th rowspan='2' style='font-size: 9px !important;'>Nama Siswa</th>";
                //nama mapel            
                for($i=0;$i<count($nama_mapel);$i++){
                    echo"<th colspan='2' style='font-size: 9px !important;'>".$nama_mapel[$i]." (".$mapel_kkm[$i].")</th>";
                }
            echo "</tr>";
            
            echo "<tr>";
            for($i=0;$i<count($nama_mapel);$i++){
                echo "<td style='font-size: 9px !important; text-align: center;'>PTS</td>
                    <td style='font-size: 9px !important; text-align: center;'>PAS</td>";
            }
            echo "</tr>";
            
            
            $no = 1;

            $no_array = array();
            $no_induk = array();
            $nama = array();
            $jumlah_mapel = count($nama_mapel);
            // $data = array();
            
            // $data_total = array();
            // $rank = array();
            // $rank_sort = array();

            while($row2 = mysqli_fetch_array($query_dkn)){
                $nama_belakang = $row2['siswa_nama_belakang'];
                if(strlen($nama_belakang) > 0){
                    $nama_siswa = $row2['siswa_nama_depan'] . " " . $nama_belakang[0];
                }else{
                    $nama_siswa = $row2['siswa_nama_depan'];
                }

                $uts_array = $row2[8];
                $uas_array = $row2[9];

                $uts = explode(",",$uts_array);
                $uas = explode(",",$uas_array);

                echo "<tr>
                    <td style='padding: 0px 0px 0px 5px;'>$no</td>
                    <td style='padding: 0px 0px 0px 5px;'>{$row2[0]}</td>
                    <td style='padding: 0px 0px 0px 5px;'>$nama_siswa</td>
                    ";

                for($k=0;$k<count($nama_mapel);$k++){
                    if($mapel_id_fix[$k]==$mapel_id_isi[$k]){
                        echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>$uts[$k]</td>";
                        echo "<td style='width: 30px; font-size: 10px !important; text-align: center;'>$uas[$k]</td>";
                    
                    }
                    
                }
      
                echo "</tr>";

                $no++;
            }
            
            echo "</table>";
            
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
            loadCSS: "http://192.168.16.253/cpasma/CSS/customCSS_preview.css"
        });
    });   

    $("#export_dkn").click(function (e) {
        //alert("hai");
        window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#print_area').html()));
        e.preventDefault();
    });
});
</script>