<?php

    echo'<table class="table table-sm table-striped table-hover table-bordered mb-3">
          <thead>
              <tr>
                  <th>Kelas</th>
                  <th>Nama Siswa</th>
                  <th>Nilai</th>
                  <th>Alasan</th>
                  <th>Action</th>
              </tr>
          </thead>';

        $kog_uts_lama = 0;
        $kog_uas_lama = 0;
        $psi_uts_lama = 0;
        $psi_uas_lama = 0;
        $kog_uts_rev = 0;
        $kog_uas_rev = 0;
        $psi_uts_rev = 0;
        $psi_uas_rev = 0;

        include '../includes/db_con.php';
        $sql3 = "SELECT * from kog_psi_ujian_revisi
                LEFT JOIN kelas 
                ON ujian_rev_kelas_id = kelas_id
                LEFT JOIN mapel 
                ON ujian_rev_mapel_id = mapel_id
                LEFT JOIN kog_psi_ujian
                ON kog_psi_ujian_id_fk = kog_psi_ujian_id
                LEFT JOIN siswa
                ON kog_psi_ujian_siswa_id = siswa_id
                WHERE ujian_rev_status = 0";
        $result3 = mysqli_query($conn, $sql3);
        
        
    echo'<tbody>';
        while ($row = mysqli_fetch_assoc($result3)) {
            $kog_psi_ujian_id = $row['kog_psi_ujian_id_fk'];
            
            $nama_guru = $row['ujian_rev_guru_name'];
            
            $kog_uts_lama = $row['kog_uts_lama'];
            $kog_uas_lama = $row['kog_uas_lama'];
            $psi_uts_lama = $row['psi_uts_lama'];
            $psi_uas_lama = $row['psi_uas_lama'];
            $kog_uts_rev = $row['kog_uts_rev'];
            $kog_uas_rev = $row['kog_uas_rev'];
            $psi_uts_rev = $row['psi_uts_rev'];
            $psi_uas_rev = $row['psi_uas_rev'];

            echo"<tr>";
            echo"<td class='table-bordered'><input type='hidden' name=kog_psi_ujian_rev_id[] value={$row['kog_psi_ujian_rev_id']}>{$row['kelas_nama']}<br>({$nama_guru})</td>";
            echo"<td class='table-bordered'>{$row['siswa_nama_depan']}<br>{$row['siswa_nama_belakang']}</td>";

            echo"<input type='hidden' name=kog_psi_ujian_id[] value=$kog_psi_ujian_id>";
            echo"<input type='hidden' name=kog_uts_rev[] value=$kog_uts_rev>";
            echo"<input type='hidden' name=kog_uas_rev[] value=$kog_uas_rev>";
            echo"<input type='hidden' name=psi_uts_rev[] value=$psi_uts_rev>";
            echo"<input type='hidden' name=psi_uas_rev[] value=$psi_uas_rev>";

            echo"<td>";
                  if($kog_uts_lama!=$kog_uts_rev){
                      echo "KOG UTS: $kog_uts_lama&rarr;$kog_uts_rev";
                      echo "<br>";
                  }
                  if($kog_uas_lama!=$kog_uas_rev){
                      echo "KOG UAS: $kog_uas_lama&rarr;$kog_uas_rev";
                      echo "<br>";
                  }
                  if($psi_uts_lama!=$psi_uts_rev){
                      echo "PSI UTS: $psi_uts_lama&rarr;$psi_uts_rev";
                      echo "<br>";
                  }
                  if($psi_uas_lama!=$psi_uas_rev){
                      echo "PSI UAS: $psi_uas_lama&rarr;$psi_uas_rev";
                      echo "<br>";
                  }
            echo"</td>";
            echo"<td class='table-bordered'>{$row['ujian_rev_alasan']}</td>";
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
?>

