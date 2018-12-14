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
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });

        $("#add-ce-form").submit(function(evt){
            evt.preventDefault();
            
            $("#submit_kriteria").attr("disabled", true);
            
            var kelas_id = $("#option_kelas").val();
            var aspek_id = $("#option_aspek").val();
            var indikator_id = $("#option_indikator").val();
            if(kelas_id>0 && aspek_id>0 && indikator_id>0){
                $.ajax({
                    url: 'ce_nilai/display_ce_nilai.php',
                    data: $(this).serialize(),
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            //alert(show);
                            $("#kotak_utama").show();
                            $("#kotak_utama").html(show);
                            $("#submit_kriteria").attr("disabled", false);
                        }
                    }
                });
                
            }else{
                alert("Pilih Kelas, Topik dan Indikator");
            }

        });
        
        $("#option_kelas").change(function () {
            $("#kotak-utama").hide();
            var kelas_id = $("#option_kelas").val();
            if(kelas_id>0){
                $.ajax({
                    url: 'ce_nilai/update_option_topik.php',
                    data: 'kelas_id='+ kelas_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#containerTopik").show();
                            $("#kotak_utama").hide();
                            $("#containerTopik").html(show);
                            $("#containerDetailAspek").hide();
                        }
                    }
                });
                
            }
        });

        $("#option_aspek").change(function () {
            var ce_id = $("#option_aspek").val();
            if(ce_id >0){
                $("#containerDetailAspek").show();
                $.ajax({
                    url: 'detail_ce/update_combo_indikator.php',
                    data: 'ce_id='+ ce_id,
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            $("#containerDetailAspek").html(show);
                        }
                    }
                });
            }
            else{
                $("#containerDetailAspek").hide();
            }
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
          <h4 class="mb-4"><u>NILAI CHARACTER BUILDING</u></h4>
          
        <form method="POST" id="add-ce-form" action="ce_nilai/display_ce_nilai.php">
          
                <?php
                    return_combo_kelas(0);
                ?>
                
                <div id="containerTopik">
                
                </div>

                <div id="containerDetailAspek">
                
                </div>


            <input type="submit" name="submit_kriteria" id="submit_kriteria" class="btn btn-primary" value="Proses">
      </form>
    </div>
      
    <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak_utama">
          
      </div>
</div> 
      <div style="margin-top:200px;"></div>
    <!-------------------------modal----------------------->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Infomation</h5>
              <button class="close" id="close_modal">&times;</button>
            </div>
            <div class="modal-body">
                Data Berhasil Ditambahkan.
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" id="close_modal2">Close</button>
            </div>
          </div>
        </div>
    </div>
    
    <!-------------------------modal----------------------->

<?php
   include_once 'footer.php'
?>