<?php

  if($_POST['mapel_id']){
    include ("../includes/db_con.php"); 
    $mapel_id = $_POST['mapel_id'];
    $siswa_id = $_POST['siswa_id'];

    $query_mapel = "SELECT topik_nama,
                           psi_quiz, psi_ass, psi_test, psi_quiz_persen, psi_ass_persen, psi_test_persen
                    FROM kog_psi
                    LEFT JOIN topik
                    ON kog_psi_topik_id = topik_id
                    WHERE topik_mapel_id = $mapel_id AND kog_psi_siswa_id = $siswa_id";

    $query_mapel_info = mysqli_query($conn, $query_mapel);        
    if(!$query_mapel_info){
      die("QUERY FAILED".mysqli_error($conn));
    }

    $psi_quiz = array();
    $psi_ass = array();
    $psi_test = array();
    $psi_quiz_persen = array();
    $psi_ass_persen = array();
    $psi_test_persen = array();
    $topik_nama = array();

    while($row_mapel = mysqli_fetch_array($query_mapel_info)){
      array_push($topik_nama, $row_mapel['topik_nama']);

      array_push($psi_quiz, $row_mapel['psi_quiz']);
      array_push($psi_ass, $row_mapel['psi_ass']);
      array_push($psi_test, $row_mapel['psi_test']);
      array_push($psi_quiz_persen, $row_mapel['psi_quiz_persen']);
      array_push($psi_ass_persen, $row_mapel['psi_ass_persen']);
      array_push($psi_test_persen, $row_mapel['psi_test_persen']);
    }

    $total_akhir = 0;
    for($i=0;$i<count($psi_quiz);$i++){

      //$jumlah_topik;
      echo "<b><u>".$topik_nama[$i].":</b></u><br>";
      echo "QUIZ + ASSIGNMENT + TEST<br>";
      $total_topik = (($psi_quiz[$i]*$psi_quiz_persen[$i])/100)+(($psi_ass[$i]*$psi_ass_persen[$i])/100)+(($psi_test[$i]*$psi_test_persen[$i])/100);
      echo "((".$psi_quiz[$i]."*".$psi_quiz_persen[$i].")/100)"."+((".$psi_ass[$i]."*".$psi_ass_persen[$i].")/100)"."+((".$psi_test[$i]."*".$psi_test_persen[$i].")/100)";
      echo "<br>".($psi_quiz[$i]*$psi_quiz_persen[$i])/100 ."+".(($psi_ass[$i]*$psi_ass_persen[$i])/100)."+".(($psi_test[$i]*$psi_test_persen[$i])/100);
      echo "<br>".$total_topik ."->".round($total_topik);
      echo "<br>";

      $total_akhir += round($total_topik);
    }

    echo "<br><b><u>TOTAL: </u></b><br>".$total_akhir."/".count($psi_quiz)."= ".$total_akhir/count($psi_quiz)."->".round($total_akhir/count($psi_quiz))."<br><br>";
  }

?>