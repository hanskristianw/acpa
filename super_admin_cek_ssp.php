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
        
       $("#ssp_id_option").change(function () {

            var ssp_id = $("#ssp_id_option").val();
            
            if(ssp_id > 0){
                $.ajax({
                url: 'superadmin/option-topik-ssp.php',
                data: {ssp_id: ssp_id},
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#topikssp").html(show);
                    }
                }
                });
            }
        });
        
        
        //ketika user menekan tombol submit
        $("#cek-topik-form").submit(function(evt){
            evt.preventDefault();
            
            var ssp_id_option = $("#ssp_id_option").val();
            var option_topik = $("#option_topik").val();
            
            var url = $(this).attr('action');

            if(ssp_id_option > 0 && option_topik > 0){
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#hasil").html(show);
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
            <strong>Info:</strong> Fitur ini berguna untuk delete nilai SSP!
          </div>
      <form method="POST" id="cek-topik-form" action="superadmin/cek_ssp.php">
          <div class="form-group">
              <h4 class="mb-4"><u>DELETE NILAI SSP</u></h4>
              <!--Memasukkan piihan kelas-->
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM ssp,t_ajaran WHERE ssp_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result = mysqli_query($conn, $sql);
                
                $options2 = "<option value= 0>Pilih SSP</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options2 .= "<option value={$row['ssp_id']}>{$row['ssp_nama']}</option>";
                }
              ?>
              
              <label>SSP:</label>
              <select class="form-control form-control-sm mb-2" name="ssp_id_option" id="ssp_id_option">
                <?php echo $options2;?>
              </select>

              <div id="topikssp"></div>
              
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