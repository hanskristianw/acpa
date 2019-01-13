<?php

  if($_POST['aksi']==2){
      include '../includes/db_con.php';
      $sql2 = "SELECT mapel_id, mapel_nama from mapel, t_ajaran where mapel_t_ajaran_id = t_ajaran_id and t_ajaran_active = 1";
      $result2 = mysqli_query($conn, $sql2);
      $options2 = "<option value=0>Pilih mapel yang ingin diedit</option>";
      while ($row2 = mysqli_fetch_assoc($result2)) {
          $options2 .= "<option value={$row2['mapel_id']}>{$row2['mapel_nama']}</option>";
      }
                    
      echo '<select class="form-control form-control-sm" name="mapel_id_option" id="mapel_id_option">';
      echo $options2;
      echo '</select>';
  }

?>
<script>
  $("#mapel_id_option").change(function () {

    var mapel_id = $("#mapel_id_option").val();

    //alert(mapel_id);

    if(mapel_id == 0){
        $("#show_form_update").hide();
    }else{
        $.ajax({
        url: 'mapel/display_form_mapel.php',
        data:'mapel_id='+ mapel_id,
        type: 'POST',
        success: function(show){
            if(!show.error){
                $("#show_form_update").show();
                $("#show_form_update").html(show);
            }
        }
        });
    }


  });
</script>