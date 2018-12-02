<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        echo "Tidak seharusnya disini";
    }else{
        $ssp_id = $_POST['option_search_ssp'];
        include ("../includes/db_con.php");
        include ("../includes/fungsi_lib.php");

        if($ssp_id != 0 && $ssp_id != -1){


            //dapatkan nilai ujian
            $query2 =   "SELECT siswa_nama_depan, siswa_nama_belakang, ssp_nama, kelas_nama, GROUP_CONCAT(d_ssp_kriteria ORDER BY d_ssp_id SEPARATOR '_' ) as topik_ssp, GROUP_CONCAT(ssp_nilai_angka ORDER BY d_ssp_id) as nilai_ssp, SUM(ssp_nilai_angka)/COUNT(ssp_nilai_angka) as nilai_akhir
                        FROM ssp_daftar
                        LEFT JOIN siswa
                        ON ssp_daftar_siswa_id = siswa_id
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        LEFT JOIN ssp
                        ON ssp_daftar_ssp_id = ssp_id
                        LEFT JOIN ssp_nilai
                        ON ssp_daftar_siswa_id = ssp_nilai_siswa_id
                        LEFT JOIN d_ssp
                        ON ssp_nilai_d_ssp_id = d_ssp_id
                        WHERE ssp_daftar_ssp_id= $ssp_id
                        GROUP BY siswa_id
                        ORDER BY siswa_nama_depan";

            $query_info2 = mysqli_query($conn, $query2);
            
            $firstrow = mysqli_fetch_assoc($query_info2);
            $topik_ssp = explode("_",$firstrow['topik_ssp']);
            echo "<h4 class='text-center mb-3 mt-5 mb-3'><u>Laporan SSP ".$firstrow['ssp_nama']."</u></h4>";
            return_info_abjad_base4();
            echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3 mb-3'>
                     <tr>
                       <th>No</th>
                       <th>Nama</th>
                       <th>Kelas</th>";
                    for($i=0;$i<count($topik_ssp);$i++){
                        echo"<th style='width: 70px;'>".$topik_ssp[$i]."</th>";
                    }
            echo "   
                        <th>Nilai Akhir</th>
                        </tr>
                     ";
            
            mysqli_data_seek($query_info2, 0);
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

                $nilai_ssp = explode(",",$row2['nilai_ssp']);
                for($i=0;$i<count($nilai_ssp);$i++){
                    echo"<td>".return_abjad_base4($nilai_ssp[$i])."(".$nilai_ssp[$i].")</td>";
                }
                $nilai_akhir = round($row2['nilai_akhir'],2);
                echo "<td>".return_abjad_base4($nilai_akhir)."(".$nilai_akhir.")</td></tr>";
                $no++;
            }

            echo "</table>";
        }elseif($ssp_id != 0 && $ssp_id == -1){
            $query2 =   "SELECT siswa_id, siswa_nama_depan, siswa_nama_belakang, kelas_nama
                        FROM siswa
                        LEFT JOIN kelas
                        ON siswa_id_kelas = kelas_id
                        LEFT JOIN t_ajaran
                        ON kelas_t_ajaran_id = t_ajaran_id
                        WHERE t_ajaran_active = 1 AND siswa_id NOT IN (SELECT ssp_daftar_siswa_id as siswa_id from ssp_daftar)
                        ORDER BY siswa_nama_depan";

            $query_info2 = mysqli_query($conn, $query2);

            echo "<h4 class='text-center mb-3 mt-5 mb-3'><u>SISWA YANG TIDAK TERDAFTAR</u></h4>";
            
            echo return_alert("Hubungi div Kurikulum jika ada siswa yang belum terdaftar tetapi nilai sudah masuk", "danger");

            echo "<table class='table table-sm table-responsive table-striped table-bordered mt-3 mb-3'>
                     <tr>
                       <th>No</th>
                       <th>Nama</th>
                       <th>Kelas</th>";
            echo "   
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
                      <td>{$row2['kelas_nama']}</td>
                      </tr>";
                $no++;
            }

            echo "</table>";

        }
    }
    
