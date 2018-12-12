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

        $kog_q_lama = 0;
        $kog_a_lama = 0;
        $kog_t_lama = 0;
        $psi_q_lama = 0;
        $psi_a_lama = 0;
        $psi_t_lama = 0;
        $kog_q_rev = 0;
        $kog_a_rev = 0;
        $kog_t_rev = 0;
        $psi_q_rev = 0;
        $psi_a_rev = 0;
        $psi_t_rev = 0;

        include '../includes/db_con.php';
        $sql3 = "SELECT * from kog_psi_revisi
                LEFT JOIN kog_psi 
                ON kog_psi_id_fk = kog_psi_id
                LEFT JOIN topik 
                ON kog_psi_topik_id = topik_id
                LEFT JOIN mapel
                ON topik_mapel_id = mapel_id
                LEFT JOIN siswa
                ON kog_psi_siswa_id = siswa_id
                LEFT JOIN kelas
                ON siswa_id_kelas = kelas_id
                WHERE rev_status = 0";
        $result3 = mysqli_query($conn, $sql3);
        
    echo'<tbody>';
        while ($row = mysqli_fetch_assoc($result3)) {
            $kog_psi_id = $row['kog_psi_id_fk'];
            
            $nama_guru = $row['rev_guru_name'];
            
            $kog_q_lama = $row['kog_quiz_lama'];
            $kog_a_lama = $row['kog_ass_lama'];
            $kog_t_lama = $row['kog_test_lama'];
            $psi_q_lama = $row['psi_quiz_lama'];
            $psi_a_lama = $row['psi_ass_lama'];
            $psi_t_lama = $row['psi_test_lama'];
            
            $kog_q_rev = $row['kog_quiz_rev'];
            $kog_a_rev = $row['kog_ass_rev'];
            $kog_t_rev = $row['kog_test_rev'];
            $psi_q_rev = $row['psi_quiz_rev'];
            $psi_a_rev = $row['psi_ass_rev'];
            $psi_t_rev = $row['psi_test_rev'];

            echo"<tr>";
            echo"<td class='table-bordered'><input type='hidden' name=kog_psi_rev_id[] value={$row['kog_psi_rev_id']}>{$row['kelas_nama']}<br>({$nama_guru})</td>";
            echo"<td class='table-bordered'>{$row['siswa_nama_depan']}<br>{$row['siswa_nama_belakang']}</td>";

            echo"<input type='hidden' name=kog_psi_id[] value=$kog_psi_id>";
            echo"<input type='hidden' name=kog_q_rev[] value=$kog_q_rev>";
            echo"<input type='hidden' name=kog_a_rev[] value=$kog_a_rev>";
            echo"<input type='hidden' name=kog_t_rev[] value=$kog_t_rev>";
            echo"<input type='hidden' name=psi_q_rev[] value=$psi_q_rev>";
            echo"<input type='hidden' name=psi_a_rev[] value=$psi_a_rev>";
            echo"<input type='hidden' name=psi_t_rev[] value=$psi_t_rev>";

            echo"<td>";
                if($kog_q_lama!=$kog_q_rev){
                    echo "KOG QUIZ: $kog_q_lama&rarr;$kog_q_rev";
                    echo "<br>";
                }
                if($kog_a_lama!=$kog_a_rev){
                    echo "KOG ASS: $kog_a_lama&rarr;$kog_a_rev";
                    echo "<br>";
                }
                if($kog_t_lama!=$kog_t_rev){
                    echo "KOG TEST: $kog_t_lama&rarr;$kog_t_rev";
                    echo "<br>";
                }
                if($psi_q_lama!=$psi_q_rev){
                    echo "PSI QUIZ: $psi_q_lama&rarr;$psi_q_rev";
                    echo "<br>";
                }
                if($psi_a_lama!=$psi_a_rev){
                    echo "PSI ASS: $psi_a_lama&rarr;$psi_a_rev";
                    echo "<br>";
                }
                if($psi_t_lama!=$psi_t_rev){
                    echo "PSI TEST: $psi_t_lama&rarr;$psi_t_rev";
                    echo "<br>";
                }
            echo"</td>";
            echo"<td class='table-bordered'>{$row['rev_alasan']}</td>";
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
    echo'<input type="submit" name="submit_revisi" class="btn btn-primary" value="Proses Revisi Tes">';
?>

