<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
       $("#container-kelas").hide();
        
       $("#mapel_id_option").change(function () {

            var mapel_id = $("#mapel_id_option").val();
            var kelas_id = $("#kelas_id_option").val();
            
            if(mapel_id > 0){
                $.ajax({
                url: 'superadmin/option-topik.php',
                data: {mapel_id: mapel_id, kelas_id: kelas_id},
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#delete-option-topik").html(show);
                    }
                }
                });
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

        //ketika user menekan tombol submit
        $("#cek-topik-form").submit(function(evt){
            evt.preventDefault();
            
            $("#submit_topik").attr("disabled", true);
            
            var kelas_id = $("#option_kelas").val();
            var aspek_id = $("#option_aspek").val();
            var indikator_id = $("#option_indikator").val();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            if(kelas_id > 0 && aspek_id > 0 && indikator_id > 0){
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#hasil").html(show);
                            $("#submit_topik").attr("disabled", false);
                        }
                    }
                });
            }
            
        });
        
        $('#close_modal').click(function(){
            $("#myModal").hide();
        });
        $('#close_modal2').click(function(){
            $("#myModal").hide();
        });
        
        $('#close_modal_username').click(function(){
            $("#myModal2").hide();
        });
        $('#close_modal_username2').click(function(){
            $("#myModal2").hide();
        });
    });
</script>

<div class="container col-6">

      <!-------------------------form hapus topik----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <div class="alert alert-danger alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Fitur ini berguna untuk rekap nilai CB apakah ada yang dobel!
          </div>
      <form method="POST" id="cek-topik-form" action="superadmin/cek_cb.php">
          <div class="form-group">
              <h4 class="mb-4"><u>CEK CB</u></h4>
                <!--Memasukkan piihan kelas-->
                <?php
                    return_combo_kelas(0);
                ?>

                <div id="containerTopik">
                
                </div>

                <div id="containerDetailAspek">
                
                </div>

              <input type="submit" id="submit_topik" name="submit_topik" class="btn btn-primary mt-3" value="Cek CB">
          </div>
      </form>
          <div id="hasil"></div>
          
          <div id="feedback"></div>
      </div >
      
      <div style="margin-top:200px;"></div>
</div>
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
    
    <div class="modal" id="myModal2">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Infomation</h5>
              <button class="close" id="close_modal_username">&times;</button>
            </div>
            <div class="modal-body">
                Username sudah dipakai.
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" id="close_modal_username2">Close</button>
            </div>
          </div>
        </div>
    </div>
    
    <!-------------------------modal----------------------->


<?php
   include_once 'footer.php'
?>