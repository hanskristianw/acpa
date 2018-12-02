<?php

    include ("../includes/db_con.php");
    include ("../includes/fungsi_lib.php");
    
    $kelas_id = $_POST['option_kelas'];
    $jenis_pilihan = $_POST['option_jenis'];
    
    if($kelas_id > 0 && $jenis_pilihan == 1) {
      //Nilai Emotional Awareness
        
      $query =    "SELECT * from emo_aware
                  LEFT JOIN siswa
                  ON emo_aware_siswa_id =  siswa_id
                  LEFT JOIN kelas
                  ON siswa_id_kelas =  kelas_id
                  WHERE kelas_id = $kelas_id";

      $query_info = mysqli_query($conn, $query);
      
      return_info_abjad_base4();

      echo"<div style='overflow-x:auto;'>
          <table class='table table-sm table-responsive table-striped table-bordered mt-3'><thead>";
      echo'<tr>
              <th>No</th>
              <th>Nama</th>
              <th>Expressive</th>
              <th>Self Control</th>
              <th>Negative Emotions</th>
              <th>Nilai Akhir</th>
            </tr>
      </thead>
      <tbody>';
          $absen = 1;
          while($row = mysqli_fetch_array($query_info)){
              $nama_belakang = $row['siswa_nama_belakang'];
              
              echo '<tr>';
                  echo"<td>{$absen}</td>";
                  
                  echo'<td>';
                  if(strlen($nama_belakang) > 0){
                      echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                  }else{
                      echo"{$row['siswa_nama_depan']}</td>";
                  }

                  echo "<td>".return_abjad_base4($row['emo_aware_ex'])." (".round($row['emo_aware_ex'],2).")</td>";
                  echo "<td>".return_abjad_base4($row['emo_aware_so'])." (".round($row['emo_aware_so'],2).")</td>";
                  echo "<td>".return_abjad_base4($row['emo_aware_ne'])." (".round($row['emo_aware_ne'],2).")</td>";
                  $total = $row['emo_aware_ex']+$row['emo_aware_so']+$row['emo_aware_ne'];
                  echo "<td>".return_abjad_base4($total/3)." (".round($total/3,2).")</td>";
              echo '</tr>';

              $absen++;
          }
      echo'</tbody></table></div>';

    }
    elseif($kelas_id > 0 && $jenis_pilihan == 2){


      //Nilai Spirituality
      $query =    "SELECT * from spirit
                  LEFT JOIN siswa
                  ON spirit_siswa_id =  siswa_id
                  LEFT JOIN kelas
                  ON siswa_id_kelas =  kelas_id
                  WHERE kelas_id = $kelas_id";

      $query_info = mysqli_query($conn, $query);

      return_info_abjad_base4();

      echo"<div style='overflow-x:auto;'>
      <table class='table table-sm table-responsive table-striped table-bordered mt-3'><thead>";
      echo'<tr>
          <th>No</th>
          <th>Nama</th>
          <th style="width: 150px;">Coping Adversities</th>
          <th style="width: 150px;>Emotional Resilience</th>
          <th style="width: 150px;>Grateful</th>
          <th style="width: 150px;>Reflective</th>
          <th>Nilai Akhir</th>
        </tr>
      </thead>
      <tbody>';
      $absen = 1;
      while($row = mysqli_fetch_array($query_info)){
          $nama_belakang = $row['siswa_nama_belakang'];
          
          echo '<tr>';
              echo"<td>{$absen}</td>";
              
              echo'<td>';
              if(strlen($nama_belakang) > 0){
                  echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
              }else{
                  echo"{$row['siswa_nama_depan']}</td>";
              }

              echo "<td>".return_abjad_base4($row['spirit_coping'])." (".round($row['spirit_coping'],2).")</td>";
              echo "<td>".return_abjad_base4($row['spirit_emo'])." (".round($row['spirit_emo'],2).")</td>";
              echo "<td>".return_abjad_base4($row['spirit_grate'])." (".round($row['spirit_grate'],2).")</td>";
              echo "<td>".return_abjad_base4($row['spirit_ref'])." (".round($row['spirit_ref'],2).")</td>";
              
              $total = $row['spirit_coping']+$row['spirit_emo']+$row['spirit_grate']+$row['spirit_ref'];
              echo "<td>".return_abjad_base4($total/4)." (".round($total/4,2).")</td>";
          echo '</tr>';

          $absen++;
      }
      echo'</tbody></table></div>';
    }
    elseif($kelas_id > 0 && $jenis_pilihan == 3){
      //NILAI CB
      $query2 =  "SELECT DISTINCT ce_id, ce_aspek
                  FROM ce_nilai
                  LEFT JOIN d_ce
                  ON ce_nilai_d_ce_id = d_ce_id
                  LEFT JOIN ce
                  ON d_ce_ce_id = ce_id
                  LEFT JOIN siswa
                  ON ce_nilai_siswa_id =  siswa_id
                  LEFT JOIN kelas
                  ON siswa_id_kelas =  kelas_id
                  WHERE kelas_id = $kelas_id
                  ORDER BY ce_id";

      $query_info2 = mysqli_query($conn, $query2);

      $aspek_col = array();
      while($row = mysqli_fetch_array($query_info2)){
        array_push($aspek_col, $row['ce_aspek']);
      }


      $query = "SELECT a.siswa_id, a.siswa_nama_depan, a.siswa_nama_belakang, GROUP_CONCAT(a.ce_aspek ORDER BY ce_id) as nama_aspek, GROUP_CONCAT(a.rata_aspek ORDER BY ce_id) as nilai_aspek, SUM(a.rata_aspek)/COUNT(a.rata_aspek) AS nilai_cb
                FROM
                (    
                    SELECT ce_id, siswa_id, siswa_nama_depan, siswa_nama_belakang, SUM(ce_nilai_angka)/COUNT(ce_nilai_d_ce_id) AS rata_aspek, d_ce_nama, d_ce_id, ce_aspek
                    FROM ce_nilai
                    LEFT JOIN d_ce
                    ON ce_nilai_d_ce_id = d_ce_id
                    LEFT JOIN ce
                    ON d_ce_ce_id = ce_id
                    LEFT JOIN siswa
                    ON ce_nilai_siswa_id =  siswa_id
                    LEFT JOIN kelas
                    ON siswa_id_kelas =  kelas_id
                    WHERE kelas_id = $kelas_id  
                    GROUP BY siswa_id, ce_id
                ) as a
                GROUP BY a.siswa_id
                ORDER BY a.siswa_id";

      $query_info = mysqli_query($conn, $query);
      
      return_info_abjad_base4();

      echo"<div style='overflow-x:auto;'>
          <table class='table table-sm table-responsive table-striped table-bordered mt-3'>
          <thead>";
      echo'<tr>
            <th>No</th>
            <th>Nama</th>';
            for($i=0;$i<count($aspek_col);$i++){
              echo'<th style="width: 150px;">'.$aspek_col[$i].'</th>';
            }
      echo' <th>Nilai CB Akhir</th></tr>
          </thead>
          <tbody>';
      $absen = 1;
      
      while($row = mysqli_fetch_array($query_info)){
          $nama_belakang = $row['siswa_nama_belakang'];
          
          echo '<tr>';
              echo"<td>{$absen}</td>";
              
              echo'<td>';
              if(strlen($nama_belakang) > 0){
                  echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
              }else{
                  echo"{$row['siswa_nama_depan']}</td>";
              }
              $nilai_kolom = explode(",",$row['nilai_aspek']);
              for($i=0;$i<count($aspek_col);$i++){
                echo'<td>'.return_abjad_base4($nilai_kolom[$i]).' ('.round($nilai_kolom[$i],2).')</td>';
              }

              echo "<td>".return_abjad_base4($row['nilai_cb'])." (".round($row['nilai_cb'],2).")</td>";
          echo '</tr>';

          $absen++;
      }
      echo'</tbody></table></div>';
    }

?>

<script>
        
    $(document).ready(function(){
        $("#add-nilai-form").submit(function(evt){
            evt.preventDefault();

            //alert("pilihan benar");
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            //input rubrik
            $.post(url,postData, function(php_table_data){
                $("#kotak_utama").hide();
                //$("#kotak_utama2").show();
                $("#feedback").html(php_table_data);
            });
        });
        
        $("#add-nilai-form-update").submit(function(evt){
            evt.preventDefault();

            //alert("pilihan benar");
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            //input rubrik
            $.post(url,postData, function(php_table_data){
                $("#kotak_utama").hide();
                //$("#kotak_utama2").show();
                $("#feedback").html(php_table_data);
            });
        });
        
    });
</script>