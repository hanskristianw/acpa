<?php

  if($_POST['mapel_id']){
    include ("../includes/db_con.php"); 
    $mapel_id = $_POST['mapel_id'];
    $siswa_id = $_POST['siswa_id'];

    $query_mapel = "SELECT topik_nama,
                           kog_quiz, kog_ass, kog_test, kog_quiz_persen, kog_ass_persen, kog_test_persen,
                           psi_quiz, psi_ass, psi_test, psi_quiz_persen, psi_ass_persen, psi_test_persen
                    FROM kog_psi
                    LEFT JOIN topik
                    ON kog_psi_topik_id = topik_id
                    WHERE topik_mapel_id = $mapel_id AND kog_psi_siswa_id = $siswa_id";

    $query_mapel_info = mysqli_query($conn, $query_mapel);        
    if(!$query_mapel_info){
      die("QUERY FAILED".mysqli_error($conn));
    }

    $kog_quiz = array();
    $kog_ass = array();
    $kog_test = array();
    $kog_quiz_persen = array();
    $kog_ass_persen = array();
    $kog_test_persen = array();

    $psi_quiz = array();
    $psi_ass = array();
    $psi_test = array();
    $psi_quiz_persen = array();
    $psi_ass_persen = array();
    $psi_test_persen = array();
    $topik_nama = array();

    while($row_mapel = mysqli_fetch_array($query_mapel_info)){
      array_push($topik_nama, $row_mapel['topik_nama']);
      array_push($kog_quiz, $row_mapel['kog_quiz']);
      array_push($kog_ass, $row_mapel['kog_ass']);
      array_push($kog_test, $row_mapel['kog_test']);
      array_push($kog_quiz_persen, $row_mapel['kog_quiz_persen']);
      array_push($kog_ass_persen, $row_mapel['kog_ass_persen']);
      array_push($kog_test_persen, $row_mapel['kog_test_persen']);

      array_push($psi_quiz, $row_mapel['psi_quiz']);
      array_push($psi_ass, $row_mapel['psi_ass']);
      array_push($psi_test, $row_mapel['psi_test']);
      array_push($psi_quiz_persen, $row_mapel['psi_quiz_persen']);
      array_push($psi_ass_persen, $row_mapel['psi_ass_persen']);
      array_push($psi_test_persen, $row_mapel['psi_test_persen']);
    }

    echo"<table class='table table-sm table-striped'>
          ";
          
          $total_akhir = 0;
          for($i=0;$i<count($kog_quiz);$i++){
      
            //$jumlah_topik;
             $total_topik = (($kog_quiz[$i]*$kog_quiz_persen[$i])/100)+(($kog_ass[$i]*$kog_ass_persen[$i])/100)+(($kog_test[$i]*$kog_test_persen[$i])/100);
            // echo "((".$kog_quiz[$i]."*".$kog_quiz_persen[$i].")/100)"."+((".$kog_ass[$i]."*".$kog_ass_persen[$i].")/100)"."+((".$kog_test[$i]."*".$kog_test_persen[$i].")/100)";
            // echo "<br>".($kog_quiz[$i]*$kog_quiz_persen[$i])/100 ."+".(($kog_ass[$i]*$kog_ass_persen[$i])/100)."+".(($kog_test[$i]*$kog_test_persen[$i])/100);
            // echo "<br>".$total_topik ."->".round($total_topik);
            // echo "<br>";

            echo "
            <tr>
              <th colspan='4'><u>".$topik_nama[$i]."</u></th>
              <tr>
                <td></td>
                <th>Quiz</th>
                <th>Assignment</th>
                <th>Test</th>
              </tr>
              <tr>
                <td></td>
                <th>Nilai*Persen +</th>
                <th>Nilai*Persen +</th>
                <th>Nilai*Persen</th>
              </tr>
              <tr>
                <td>=</td>
                <td>((".$kog_quiz[$i]."*".$kog_quiz_persen[$i].")/100)+</td>
                <td>((".$kog_ass[$i]."*".$kog_ass_persen[$i].")/100)+</td>
                <td>((".$kog_test[$i]."*".$kog_test_persen[$i].")/100)</td>
              </tr>
              <tr>
                <td>=</td>
                <td>".(($kog_quiz[$i]*$kog_quiz_persen[$i])/100)."</td>
                <td>".(($kog_ass[$i]*$kog_ass_persen[$i])/100)."</td>
                <td>".(($kog_test[$i]*$kog_test_persen[$i])/100)."</td>
              </tr>
              <tr>
                <td>=</td>
                <td>".$total_topik ."&#8594;".round($total_topik)."</td>
              </tr>
            </tr>";
            
            $total_akhir += round($total_topik);

          }
          echo"<tr>
              <td><b>Total:</b></td>
              <td>".$total_akhir."/".count($kog_quiz)."= ".$total_akhir/count($kog_quiz)."&#8594;".round($total_akhir/count($kog_quiz))."</td>
            </tr>";

    echo "</table>";
  }

?>