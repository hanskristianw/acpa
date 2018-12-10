<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }
  include_once 'header.php';
  include 'includes/db_con.php';
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
      $("#container-ssp").hide();
      
      //ketika user menekan tombol submit
      $("#add-ssp-form").submit(function(evt){
          evt.preventDefault();

          var postData = $(this).serialize();
          var url = $(this).attr('action');

          $.post(url,postData, function(php_table_data){
              $("#myModal").show();
          });
          
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

      <form method="POST" id="add-ssp-form" action="scout/update_pengajar.php">
          <div class="form-group">
              <h4 class="mb-4"><u>PENGAJAR SCOUT</u></h4>
              
              <?php
                $sql = "SELECT * FROM guru WHERE guru_active = 1 ORDER BY guru_name";
                $result = mysqli_query($conn, $sql);

                $options = "<option value=0>Pilih guru pengajar SCOUT</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options .= "<option value={$row['guru_id']}>{$row['guru_name']}</option>";
                }
              ?>

              <select class="form-control form-control-sm" name="guru_id_option">
                <?php echo $options;?>
              </select>
              
              <input type="submit" name="submit_ssp" class="btn btn-primary mt-3" value="Update Pengajar Scout">
          </div>
      </form>
      </div >
      
    <!-------------------------end of tabel kriteria----------------------->
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
                Pengajar Scout Berhasil Dirubah.
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