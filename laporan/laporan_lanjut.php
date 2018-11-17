<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        $mapel_id = $_POST['option_search_mapel'];
        $kelas_id = $_POST['option_kelas'];
        
        if($mapel_id > 0 && $kelas_id>0){

            include ("../includes/db_con.php");
            
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
                       <th>Kog UTS</th>
                       <th>Kog UAS</th>
                       <th>Psi UTS</th>
                       <th>Psi UAS</th>
                     </tr>
                     ";
            
            $no = 1;
            while($row2 = mysqli_fetch_array($query_info2)){
                echo "<tr>
                      <td>$no</td>
                      <td>{$row2['siswa_nama_depan']} {$row2['siswa_nama_belakang']}</td>
                      <td>{$row2['kog_uts']}</td>
                      <td>{$row2['kog_uas']}</td>
                      <td>{$row2['psi_uts']}</td>
                      <td>{$row2['psi_uas']}</td>
                     </tr>";
                $no++;
            }

            echo "</table>";
            
            
            //cari semua topik yang ada pada mapel ini dan ada pada tabel quiz
            $query3 =   "SELECT *
                        FROM kog_psi
                        LEFT JOIN topik
                        ON kog_psi_topik_id = topik_id
                        LEFT JOIN siswa
                        ON kog_psi_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        WHERE topik_mapel_id= $mapel_id AND siswa_id_kelas = $kelas_id ORDER BY topik_urutan";
            
        }
    }
    
