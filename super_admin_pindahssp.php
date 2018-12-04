<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
  include_once 'header.php';
  include_once 'includes/fungsi_lib.php';
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
       $("#container-kelas").hide();
       
        //ketika user menekan tombol submit
        $("#cek-topik-form").submit(function(evt){
            evt.preventDefault();
            
            $("#submit_topik").attr("disabled", true);
            
            var ssp_asal = $("#ssp_asal_option").val();
            var ssp_tujuan = $("#ssp_tujuan_option").val();
            var kelas_id = $("#kelas_id_option").val();
            var siswa_id = $("#siswa_id_option").val();
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            if(ssp_asal != ssp_tujuan && kelas_id>0 && siswa_id >0 && ssp_asal > 0 && ssp_tujuan > 0){
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
            else{
                $("#submit_topik").attr("disabled", false);
                alert("Pilihan harus benar");
            }
            
        });
        
        $("#kelas_id_option").change(function () {
            var kelas_id = $("#kelas_id_option").val();

            $.ajax({
                url: 'superadmin/update_option_siswa.php',
                data:'kelas_id='+ kelas_id,
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_siswa").html(show);
                    }
                }
            });

        });

    });
</script>

<div class="container col-6">

      <!-------------------------form hapus topik----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
        <?php
          echo return_alert("Pilih SSP awal dan akhir","danger");
        ?>
      <form method="POST" id="cek-topik-form" action="superadmin/pindah_ssp.php">
          <div class="form-group">
              <h4 class="mb-4"><u>PINDAH SSP</u></h4>
              <h6 class="mb-4"><u>SSP ASAL</u></h4>
              <!--Memasukkan piihan kelas-->
              <?php
                include 'includes/db_con.php';
                $sql2 = "SELECT * FROM kelas,t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result2 = mysqli_query($conn, $sql2);
                
                $options3 = "<option value= 0>Pilih Kelas</option>";
                while ($row = mysqli_fetch_assoc($result2)) {
                    $options3 .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
                }
              ?>
              <select class="form-control form-control-sm mb-2" name="kelas_id_option" id="kelas_id_option">
                <?php echo $options3;?>
              </select>
              
              <div id="show_siswa"></div>

              <div id="show_ssp_siswa"></div>

              <h6 class="mb-4 mt-4"><u>SSP TUJUAN</u></h4>

              <?php
                include 'includes/db_con.php';
                $sql2 = "SELECT * FROM ssp,t_ajaran WHERE ssp_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result2 = mysqli_query($conn, $sql2);
                
                $options3 = "<option value= 0>Pilih SSP</option>";
                while ($row = mysqli_fetch_assoc($result2)) {
                    $options3 .= "<option value={$row['ssp_id']}>{$row['ssp_nama']}</option>";
                }
              ?>
              <select class="form-control form-control-sm mb-2" name="ssp_tujuan_option" id="ssp_tujuan_option">
                <?php echo $options3;?>
              </select>

              <input type="submit" id="submit_topik" name="submit_topik" class="btn btn-primary mt-3" value="Pindah SSP">
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