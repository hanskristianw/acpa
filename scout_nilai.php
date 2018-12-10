<?php
    session_start();

    include ("includes/db_con.php");
    $ses_guru_id = $_SESSION['guru_id'];
    $query =    "SELECT *
                from t_ajaran
                WHERE t_ajaran_scout_id_guru = $ses_guru_id AND t_ajaran_active = 1";

    $query_afektif_info = mysqli_query($conn, $query);
    $resultCheck = mysqli_num_rows($query_afektif_info);

    if($resultCheck == 0){
      header("Location: index.php");
    }
    if(!isset($_SESSION['guru_jabatan'])){
      header("Location: index.php");
    }
    
    include_once 'header.php';


?>

<script>
    var isPaused = false; 
    $(document).ready(function(){
        
      $("#kotak_utama").hide();
      
      $("#add-scout-form").submit(function(evt){
          evt.preventDefault();

          var kelas_id = $("#kelas_option").val();
          
          if(kelas_id>0){
            var url = $(this).attr('action');
            
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#kotak_utama").show();
                        $("#kotak_utama").html(show);
                    }
                }
            });
              
          }else{
              alert("Pilih Kelas");
          }

      });
        
      var $loading = $('#loadingDiv').hide();
      $(document)
        .ajaxStart(function () {
          $loading.show();
        })
        .ajaxStop(function () {
          $loading.hide();
        });

    });
</script>

<?php

    include ("includes/db_con.php");

    $query = "SELECT * FROM t_ajaran where t_ajaran_active = 1";
    $query_t_ajaran_info = mysqli_query($conn, $query);

    if(!$query_t_ajaran_info){
        die("QUERY FAILED".mysqli_error($conn));
    }
    
    //tampilkan tabel pada container
    while($row = mysqli_fetch_array($query_t_ajaran_info)){
        $semester = $row['t_ajaran_semester'];
    }
?>


<div class="container col-6">
      
      <!-------------------------form kriteria----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          
          <div id="show_notif"></div>
          
          <h4><u>Nilai SCOUT</u></h4>

          <form method="POST" id="add-scout-form" action="scout/display_siswa_scout.php">
            <?php
              $guru_id = $_SESSION['guru_id'];
              
              include 'includes/db_con.php';
              $sql = "SELECT * FROM kelas 
                      LEFT JOIN t_ajaran
                      ON kelas_t_ajaran_id = t_ajaran_id
                      WHERE t_ajaran_active = 1";
              $result = mysqli_query($conn, $sql);

              $options = "<option value=0>Pilih kelas</option>";
              while ($row = mysqli_fetch_assoc($result)) {
                  $options .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
              }
            ?>

            <select class="form-control form-control-sm mb-2 mt-4" name="kelas_option" id="kelas_option">
                <?php
                    echo $options;
                ?>
            </select>
              
          <input type="submit" name="submit_kriteria" class="btn btn-primary mt-3" value="Proses">
      </form>
    </div>

    <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>

    <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak_utama">
        
    </div>
</div> 
      <div style="margin-top:200px;"></div>
      

<?php
   include_once 'footer.php'
?>