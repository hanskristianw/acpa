<?php

  if($_POST['aksi']==1){
    include '../includes/db_con.php';
  
    $sql3 = "SELECT guru_id, guru_name FROM guru WHERE guru_active = 1";
    $result3 = mysqli_query($conn, $sql3);
    $options3 = "<option value = 0>Pilih Guru Pengajar</option>";
    while ($row3 = mysqli_fetch_assoc($result3)) {
        $options3 .= "<option value={$row3['guru_id']}>{$row3['guru_name']}</option>";
    }

    $sql2 = "SELECT kelas_id, kelas_nama FROM kelas, t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
    $result2 = mysqli_query($conn, $sql2);
    $options2 = "";
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $options2 .= "<div class='form-group row'>";
        //$options2 .= "<div class='col-sm-4'><input type='checkbox' name='check_kelas_option[]' value={$row2['kelas_id']}> {$row2['kelas_nama']}</div>";
        $options2 .= "<div class='col-sm-4'><input type='hidden' name='check_kelas_option[]' value=-{$row2['kelas_id']}><input type='checkbox' onclick='this.previousSibling.value=-1*this.previousSibling.value'> {$row2['kelas_nama']}</div>";
        $options2 .= "<div class='col-sm-8'><select class='form-control form-control-sm' name='guru_id_option[]'>".$options3."</select></div>";
        $options2 .= "</div>";
    }
    
    echo '<h4 class="mb-4">Kelas dan guru pengajar</h4>';
    echo $options2;
  }
                  
  
?>
