<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        $mapel_id = $_POST['option_search_mapel'];
        $guru_id = $_SESSION['guru_id'];
        if($mapel_id > 0){

            include ("../includes/db_con.php");
            include ("../includes/fungsi_lib.php");

            //dapatkan kkm mapel
            $query_kkm ="SELECT *
                        FROM mapel
                        WHERE mapel_id = $mapel_id";

            $query_info_kkm = mysqli_query($conn, $query_kkm);
            $firstrow = mysqli_fetch_assoc($query_info_kkm);
            $kkm = $firstrow['mapel_kkm'];

            //dapatkan nilai ujian
            $query2 =   "SELECT siswa_nama_depan, siswa_nama_belakang, kelas_nama, kog_uts, kog_uts_persen, kog_uas, kog_uas_persen, psi_uts, psi_uts_persen, psi_uas, psi_uas_persen
                        FROM kog_psi_ujian
                        LEFT JOIN siswa
                        ON kog_psi_ujian_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        LEFT JOIN mapel
                        ON kog_psi_ujian_mapel_id = mapel_id
                        WHERE 
                        siswa_id_kelas IN 
                            (SELECT d_mapel_id_kelas as siswa_id_kelas FROM d_mapel
                            LEFT JOIN mapel
                            ON d_mapel_id_mapel = mapel_id
                            LEFT JOIN t_ajaran
                            ON mapel_t_ajaran_id = t_ajaran_id
                            WHERE d_mapel_id_guru = $guru_id AND t_ajaran_active = 1) 
                        AND
                        ((mapel_id = $mapel_id AND kog_uts < $kkm AND kog_uts_persen > 0) OR
                        (mapel_id = $mapel_id AND kog_uas < $kkm AND kog_uas_persen > 0) OR
                        (mapel_id = $mapel_id AND psi_uts < $kkm AND psi_uts_persen > 0) OR
                        (mapel_id = $mapel_id AND psi_uas < $kkm AND psi_uas_persen > 0))
                        ORDER BY kelas_id, siswa_nama_depan";

            $query_info2 = mysqli_query($conn, $query2);
            
            echo "<h4 class='text-center mb-3 mt-5'><u>Laporan Siswa Remidial Ujian</u></h4>";
            echo return_alert("Bertanda merah: Dibawah kkm dengan persentase UTS dan UAS > 0","warning");
            echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3'>
                     <tr>
                       <th>No</th>
                       <th>Nama siswa</th>
                       <th>Kelas</th>
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
                      <td>{$row2['kelas_nama']}</td>";
                if($row2['kog_uts']<$kkm && $row2['kog_uts_persen']>0){
                    echo "<td class='table-danger'>{$row2['kog_uts']}</td>";
                }
                elseif($row2['kog_uts']<$kkm && $row2['kog_uts_persen']==0){
                    echo "<td>-</td>";
                }
                else{
                    echo "<td>{$row2['kog_uts']}</td>";
                }
                
                if($row2['kog_uas']<$kkm && $row2['kog_uas_persen']>0){
                    echo "<td class='table-danger'>{$row2['kog_uas']}</td>";
                }
                elseif($row2['kog_uas']<$kkm && $row2['kog_uas_persen']==0){
                    echo "<td>-</td>";
                }
                else{
                    echo "<td>{$row2['kog_uas']}</td>";
                }

                if($row2['psi_uts']<$kkm && $row2['psi_uts_persen']>0){
                    echo "<td class='table-danger'>{$row2['psi_uts']}</td>";
                }
                elseif($row2['psi_uts']<$kkm && $row2['psi_uts_persen']==0){
                    echo "<td>-</td>";
                }
                else{
                    echo "<td>{$row2['psi_uts']}</td>";
                }

                if($row2['psi_uas']<$kkm && $row2['psi_uas_persen']>0){
                    echo "<td class='table-danger'>{$row2['psi_uas']}</td>";
                }
                elseif($row2['psi_uas']<$kkm && $row2['psi_uas_persen']==0){
                    echo "<td>-</td>";
                }
                else{
                    echo "<td>{$row2['psi_uas']}</td>";
                }

                echo "</tr>";
                $no++;
            }

            echo "</table>";
            
        }
    }
    
