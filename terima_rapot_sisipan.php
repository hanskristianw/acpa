<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>  
    $(document).ready(function(){
        
        
        $("#add-t-ajaran-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                //$("#hasil_kelas").html(php_table_data);
                $("#add-t-ajaran-form")[0].reset();
                alert("Data Berhasil Diupdate");
            });
            
        });
    });
</script>

<div class="container col-4 mt-4 p-3 mb-2 bg-light border border-primary rounded">
      <!-------------------------tabel t_ajaran----------------------->
      <div class="alert alert-info mt-3 mb-4">
        <strong>Info: </strong> Pilih tanggal penerimaan rapor SISIPAN dan kepala sekolah.
      </div>
      
      <div id="show_t_ajaran">
          
      </div>
      
      <form method="POST" id="add-t-ajaran-form" action="rapot_waka/proses_terima_rapot_sisipan.php">
          <div class="form-group mt-4">
              
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM guru WHERE guru_active = 1";
                $result = mysqli_query($conn, $sql);

                $options = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options .= "<option value={$row['guru_id']}>{$row['guru_name']}</option>";
                }
              ?>
              <label>Kepala Sekolah (muncul pada rapor SISIPAN):</label>
              <select class="form-control form-control-sm mb-3" name="guru_id_option" id="guru_id_option">
                <?php echo $options;?>
              </select>
              
              <label>Tanggal Penerimaan Rapot:</label>
              <input type="date" name="tanggal_rapot" class="form-control form-control-sm" name="tanggal_rapot" id="tanggal_rapot">
              
              <input type="submit" name="submit_t_ajaran" class="btn btn-primary mt-3" value="UPDATE">
          </div>
      </form>
</div>
   
<?php
   include_once 'footer.php'
?>