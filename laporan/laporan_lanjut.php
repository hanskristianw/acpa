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
                "SELECT *
                FROM kog_psi_ujian
                LEFT JOIN siswa
                ON kog_psi_ujian_siswa_id = siswa_id
                LEFT JOIN kelas
                ON siswa_id_kelas = kelas_id
                WHERE kog_psi_ujian_mapel_id= $mapel_id AND siswa_id_kelas = $kelas_id";

            //dapatkan nilai ujian
            $query2 =   "SELECT *
                        FROM kog_psi_ujian
                        LEFT JOIN siswa
                        ON kog_psi_ujian_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE kog_psi_ujian_mapel_id= $mapel_id AND siswa_id_kelas = $kelas_id";

            $query_info2 = mysqli_query($conn, $query2);
            
            
            echo "<h4 class='text-center mb-3 mt-5'><u>Laporan Hasil Ujian</u></h4>";
            echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                     <tr>
                       <th>No</th>
                       <th>Nama siswa</th>
                       <th>Peng MID</th>
                       <th>Peng FINAL</th>
                       <th>Ket MID</th>
                       <th>Ket FINAL</th>
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
                      <td>$no</td>
                      <td>{$nama_siswa}</td>
                      <td>{$row2['kog_uts']}</td>
                      <td>{$row2['kog_uas']}</td>
                      <td>{$row2['psi_uts']}</td>
                      <td>{$row2['psi_uas']}</td>
                     </tr>";
                $no++;
            }

            echo "</table>";
            
            
            //cari semua topik yang ada pada siswa ini dan mapel ini dan ada pada tabel quiz
            $query_cari_topik =  "SELECT DISTINCT topik_id, topik_nama
                        FROM kog_psi
                        LEFT JOIN siswa
                        ON kog_psi_siswa_id = siswa_id
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        WHERE siswa_id_kelas = $kelas_id AND topik_mapel_id = $mapel_id
                        ORDER BY topik_id";

            $query_info3 = mysqli_query($conn, $query_cari_topik);

            $topik_nama = array();
            while($row = mysqli_fetch_array($query_info3)){
                array_push($topik_nama, $row['topik_nama']);
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
            echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                    <tr>
                        <th rowspan='3' style='vertical-align: bottom;'>No</th>
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
                    echo"<th style='width: 30px;'>Q</th>
                        <th style='width: 30px;'>T</th>
                        <th style='width: 30px;'>A</th>
                        <th style='width: 30px;'>Q</th>
                        <th style='width: 30px;'>T</th>
                        <th style='width: 30px;'>A</th>";
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
                      <td>$no</td>";

                if(strlen($nama_belakang) > 0){
                    echo"<td>{$row2['siswa_nama_depan']} $nama_belakang[0]</td>";
                }else{
                    echo"<td>{$row2['siswa_nama_depan']}</td>";
                }

                for($i=0;$i<count($topik_nama);$i++){
                    echo"<td>{$kum_k_quiz[$i]}</td>";
                    echo"<td>{$kum_k_test[$i]}</td>";
                    echo"<td>{$kum_k_ass[$i]}</td>";
                    echo"<td>{$kum_p_quiz[$i]}</td>";
                    echo"<td>{$kum_p_test[$i]}</td>";
                    echo"<td>{$kum_p_ass[$i]}</td>";
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
            echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                  <tr>
                        <th>No</th>
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
                      <td>$no</td>";

                if(strlen($nama_belakang) > 0){
                    echo"<td>{$row2['siswa_nama_depan']} $nama_belakang[0]</td>";
                }else{
                    echo"<td>{$row2['siswa_nama_depan']}</td>";
                }

                echo "<td>".return_abjad_afek(return_total_nilai_afektif_bulan($afektif_nilai)/$jumlah_bul)."</td>";
                echo"</tr>";
                $no++;
            }

            echo "</table>";
        }
    }
    
