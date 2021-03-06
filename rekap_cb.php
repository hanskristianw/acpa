<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>
    var isPaused = false; 
    $(document).ready(function(){
        
        $("#kotak_utama").hide();
        $("#containerDetailAspek").hide();
        
        $("#add-ce-form").submit(function(evt){
            evt.preventDefault();

            var kelas_id = $("#option_kelas").val();
            var jenis_id = $("#option_jenis").val();
            if(kelas_id>0 && jenis_id>0){
                $.ajax({
                    url: 'rekap_cb/display_rekap.php',
                    data: $(this).serialize(),
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            //alert(show);
                            $("#kotak_utama").show();
                            $("#kotak_utama").html(show);
                        }
                    }
                });
                
            }else{
                alert("Pilih Kelas dan Jenis Rekap");
            }

        });

        $("#option_kelas").change(function () {
            $("#kotak-utama").hide();
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
          
          <div id="feedback"></div>
          <h4 class="mb-4"><u>Rekap Nilai</u></h4>
          
        <form method="POST" id="add-ce-form" action="rekap_cb/display_rekap.php">
          
                <?php
                    return_combo_kelas(0);
                ?>

              <select class='form-control form-control-sm mb-2' name='option_jenis' id='option_jenis'>";
                <option value = 0>Pilih Jenis Rekap</option>
                <option value = 1>Nilai Emotional Awareness</option>
                <option value = 2>Nilai Spirituality</option>
                <option value = 3>Character Building</option>
              </select>
            <input type="submit" name="submit_kriteria" class="btn btn-primary" value="Proses">
      </form>
    </div>
      
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak_utama">
          
      </div>
</div> 
      <div style="margin-top:200px;"></div>

<?php
   include_once 'footer.php'
?>