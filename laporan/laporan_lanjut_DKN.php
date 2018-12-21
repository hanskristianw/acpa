<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        $kelas_id = $_POST['option_kelas'];
        
        if($kelas_id>0){

            include ("../includes/db_con.php");
            include ("../includes/fungsi_lib.php");

            //laporan nilai akhir raport
            $query_nilai_akhir =
                "SELECT siswa_id, GROUP_CONCAT(mapel_nama ORDER BY mapel_urutan) as nama_mapel,
                    GROUP_CONCAT(ROUND((kog_uts*kog_uts_persen+kog_uas*kog_uas_persen)/100,0) ORDER BY mapel_urutan)as sum_kog,
                    GROUP_CONCAT(ROUND((psi_uts*psi_uts_persen+psi_uas*psi_uas_persen)/100,0) ORDER BY mapel_urutan) as sum_psi,
                    siswa_nama_depan,siswa_nama_belakang, GROUP_CONCAT(mapel_id ORDER BY mapel_urutan),
                    GROUP_CONCAT(mapel_persen_for ORDER BY mapel_urutan) as mapel_persen_for, GROUP_CONCAT(mapel_persen_sum ORDER BY mapel_urutan) as mapel_persen_sum, 
                    GROUP_CONCAT(mapel_persen_for_psi ORDER BY mapel_urutan), GROUP_CONCAT(mapel_persen_sum_psi ORDER BY mapel_urutan) as mapel_persen_sum_psi,
                    GROUP_CONCAT(mapel_persen_psi ORDER BY mapel_urutan) as mapel_persen_psi, GROUP_CONCAT(mapel_persen_kog ORDER BY mapel_urutan) as mapel_persen_kog
                FROM kog_psi_ujian
                LEFT JOIN mapel
                ON kog_psi_ujian_mapel_id = mapel_id
                LEFT JOIN siswa
                ON kog_psi_ujian_siswa_id = siswa_id
                LEFT JOIN kelas
                ON siswa_id_kelas = kelas_id
                WHERE siswa_id_kelas = 31
                GROUP BY siswa_id
                ORDER BY siswa_id";

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