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
        
        
        //ketika user menekan tombol submit
        $("#cek-topik-form").submit(function(evt){
            evt.preventDefault();
            
            $("#submit_topik").attr("disabled", true);
            
            var mapel_id = $("#mapel_id_option").val();
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            if(mapel_id > 0){
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
            <strong>Info:</strong> Fitur ini berguna untuk rekap nilai UTS dan UAS apakah ada yang dobel!
          </div>
      <form method="POST" id="cek-topik-form" action="superadmin/cek_ujian.php">
          <div class="form-group">
              <h4 class="mb-4"><u>CEK UTS dan UAS</u></h4>
              <!--Memasukkan piihan kelas-->
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM kelas,t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result = mysqli_query($conn, $sql);
                
                $options2 = "<option value= 0>Pilih Kelas</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options2 .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
                }
              ?>
              
              <label>Kelas:</label>
              <select class="form-control form-control-sm mb-2" name="kelas_id_option" id="kelas_id_option">
                <?php echo $options2;?>
              </select>
              
              <!--Memasukkan piihan mapel-->
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM mapel,t_ajaran WHERE mapel_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1 ORDER BY mapel_nama";
                $result = mysqli_query($conn, $sql);

                $options2 = "<option value= 0>Pilih Mapel</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options2 .= "<option value={$row['mapel_id']}>{$row['mapel_nama']}</option>";
                }
              ?>
              
              <label>Mapel:</label>
              <select class="form-control form-control-sm mb-2" name="mapel_id_option" id="mapel_id_option">
                <?php echo $options2;?>
              </select>
              
              <input type="submit" id="submit_topik" name="submit_topik" class="btn btn-primary mt-3" value="Tambah Kelas">
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