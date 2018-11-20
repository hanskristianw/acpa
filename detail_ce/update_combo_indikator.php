<?php
  

  include ("../includes/db_con.php");
  
  $ce_id = $_POST['ce_id'];
  if(isset($ce_id)){
    $query =    "SELECT *
                FROM d_ce
                LEFT JOIN ce
                ON d_ce_ce_id = ce_id
                WHERE d_ce_ce_id = $ce_id";

    $query_info = mysqli_query($conn, $query);

    $options = "<option value= 0>Pilih Indikator</option>";
    while($row = mysqli_fetch_array($query_info)){
      $options .= "<option value={$row['d_ce_id']}>{$row['d_ce_nama']}</option>";
    }

    echo"<select class='form-control form-control-sm mb-2' name='option_indikator' id='option_indikator'>";
    echo $options;
    echo"</select>";
  }
  
?>