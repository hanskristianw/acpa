<?php

    echo'<table class="table table-sm table-striped table-hover table-bordered mb-3">
          <thead>
              <tr>
                  <th>Guru Pengajar</th>
                  <th>Nama Siswa</th>
                  <th>Nilai</th>
                  <th>Alasan</th>
                  <th>Action</th>
              </tr>
          </thead>';

        $nilai_lama = 0;
        $nilai_rev = 0;

        include '../includes/db_con.php';
        $sql3 = "SELECT * from ssp_revisi
                LEFT JOIN ssp_nilai 
                ON ssp_rev_ssp_nilai_id = ssp_nilai_id
                LEFT JOIN d_ssp 
                ON ssp_nilai_d_ssp_id = d_ssp_id
                LEFT JOIN ssp 
                ON d_ssp_ssp_id = ssp_id
                LEFT JOIN siswa 
                ON ssp_nilai_siswa_id = siswa_id
                WHERE ssp_rev_status = 0";
        $result3 = mysqli_query($conn, $sql3);
        
        
    echo'<tbody>';
    
        while ($row = mysqli_fetch_assoc($result3)) {
            $ssp_nilai_id = $row['ssp_rev_ssp_nilai_id'];
            
            $nama_guru = $row['ssp_rev_guru_name'];
            
            $ssp_rev_nilai_lama = $row['ssp_rev_nilai_lama'];
            $ssp_rev_nilai_baru = $row['ssp_rev_nilai_baru'];

            if($ssp_rev_nilai_lama == 4){
                $nilai_ssp_lama_huruf = "A";
            }elseif($ssp_rev_nilai_lama == 3){
                $nilai_ssp_lama_huruf = "B";
            }elseif($ssp_rev_nilai_lama == 2){
                $nilai_ssp_lama_huruf = "C";
            }elseif($ssp_rev_nilai_lama == 1){
                $nilai_ssp_lama_huruf = "D";
            }
            
            if($ssp_rev_nilai_baru == 4){
                $nilai_ssp_baru_huruf = "A";
            }elseif($ssp_rev_nilai_baru == 3){
                $nilai_ssp_baru_huruf = "B";
            }elseif($ssp_rev_nilai_baru == 2){
                $nilai_ssp_baru_huruf = "C";
            }elseif($ssp_rev_nilai_baru == 1){
                $nilai_ssp_baru_huruf = "D";
            }
            
            echo"<tr>";
            echo"<td class='table-bordered'><input type='hidden' name=ssp_revisi_id[] value={$row['ssp_revisi_id']}>{$nama_guru}<br>({$row['ssp_nama']})</td>";
            echo"<td class='table-bordered'>{$row['siswa_nama_depan']}<br>{$row['siswa_nama_belakang']}</td>";

            echo"<input type='hidden' name=ssp_nilai_id[] value=$ssp_nilai_id>";
            echo"<input type='hidden' name=ssp_rev_nilai_baru[] value=$ssp_rev_nilai_baru>";

            echo"<td>";
                      echo "$nilai_ssp_lama_huruf&rarr;$nilai_ssp_baru_huruf";
            echo"</td>";
            echo"<td class='table-bordered'>{$row['ssp_rev_alasan']}</td>";
            echo"<td class='table-bordered'>
                <select class='form-control form-control-sm mb-3' name='pilihan[]'>
                  <option value = 1>Terima</option>
                  <option value = 2>Tolak</option>
                  <option value = 0>Pending</option>
                </select>
                </td>";
            echo"</tr>";
        }
    
    echo'</tbody>';
    echo'</table>';    
    
    echo'<input type="submit" name="submit_ssp" class="btn btn-primary" value="Proses Revisi SSP">';
?>

