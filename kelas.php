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
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#container-kelas").hide();
        
       //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){

            $.ajax({
                url: 'kelas/display_kelas.php',
                type: 'POST',
                success: function(show_kelas){
                    if(!show_kelas.error){
                        $("#show_kelas").html(show_kelas);
                    }
                }
            });
       }
        
        //ketika user menekan tombol submit
        $("#add-kelas-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_kelas").html(php_table_data);
                $("#add-kelas-form")[0].reset();
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

<div class="container col-6">
      <?php
            include 'includes/db_con.php';
            $sql3 = "SELECT t_ajaran_nama, t_ajaran_semester FROM t_ajaran WHERE t_ajaran_active = 1";
            $result3 = mysqli_query($conn, $sql3);
            $t_ajaran = "";
            while ($row = mysqli_fetch_assoc($result3)) {
                $t_ajaran = $row['t_ajaran_nama'];
                $t_ajaran_semester = $row['t_ajaran_semester'];
            }
      ?>

      
    
    
      <!-------------------------form kelas----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Guru yang tampil pada daftar wali kelas HANYA guru aktif dengan jabatan wali kelas
          </div>
      <form method="POST" id="add-kelas-form" action="kelas/add_kelas.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH KELAS</u></h4>
              <label>Nama Kelas:</label>
              <input type="text" name="kelas_nama_input" placeholder="Masukkan nama kelas" class="form-control form-control-sm mb-2" required>
              
              <!--Memasukkan piihan jenjang-->
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM jenjang";
                $result = mysqli_query($conn, $sql);

                $options2 = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options2 .= "<option value={$row['jenjang_id']}>{$row['jenjang_nama']}</option>";
                }
              ?>
              <label>Jenjang:</label>
              <select class="form-control form-control-sm mb-2" name="jenjang_id_option">
                <?php echo $options2;?>
              </select>
              
              
              <!--Memasukkan piihan guru yang wali kelas dan masih aktif-->
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM guru WHERE guru_active = 1 AND guru_jabatan = 2";
                $result = mysqli_query($conn, $sql);

                $options = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options .= "<option value={$row['guru_id']}>{$row['guru_name']}</option>";
                }
              ?>
              <label>Wali Kelas:</label>
              <select class="form-control form-control-sm" name="guru_id_option">
                <?php echo $options;?>
              </select>
              
              <input type="submit" name="submit_kelas" class="btn btn-primary mt-3" value="Tambah Kelas">
          </div>
      </form>
      </div >
      <!-------------------------end of form kelas----------------------->
      
      <!-------------------------tabel kelas----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <h4 class="mb-4"><u>Tahun Ajaran 
             <?php
                echo $t_ajaran .' Semester '.$t_ajaran_semester;
             ?>
          </u></h4>
          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Kelas yang tampil hanya kelas dengan tahun ajaran dan semester AKTIF
          </div>

          <!--action container kelas-->
          <div id="container-kelas" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                </tr>
            </thead>
            <tbody id="show_kelas">

            </tbody>
          </table>
      </div >
      
      
    <!-------------------------end of tabel kelas----------------------->
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