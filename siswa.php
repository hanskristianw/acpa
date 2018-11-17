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
        
        $("#container-siswa").hide();
        
       //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){
            var kelas_id = $("#search_kelas_nama_option").val();
            //alert(kelas_id);
            $.ajax({
                url: 'siswa/display_siswa.php',
                data:'kelas_id='+ kelas_id,
                type: 'POST',
                success: function(show_siswa){
                    if(!show_siswa.error){
                        $("#show_siswa").html(show_siswa);
                    }
                }
            });
       }
       //keyup function untuk cek username live
       $("#guru_username_input").focusout(function(){
            var guru_username = $("#guru_username_input").val();
            $.ajax({
                url: "guru/cek_username_guru.php",
                data:'guru_username='+ guru_username,
                dataType : 'json',
                type: "POST",
                success:function(data){
                    //alert(data['status']);
                    if(data['status'] == 1){
                        $("#myModal2").show();
                        $('#guru_username_input').val('');
                    }
                }
            });
        });
        
        //ketika user menekan tombol submit
        $("#add-siswa-form").submit(function(evt){
            evt.preventDefault();
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_siswa").html(php_table_data);
                $("#add-siswa-form")[0].reset();
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
      
      <!-------------------------form siswa----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          
      <form method="POST" id="add-siswa-form" action="siswa/add_siswa.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH SISWA</u></h4>
              <label>No Induk:</label>
              <input type="text" name="siswa_no_induk" placeholder="Masukkan no induk" class="form-control form-control-sm mb-2" required>
              <label>Nama Depan:</label>
              <input type="text" name="siswa_nama_depan" placeholder="Masukkan nama depan" class="form-control form-control-sm mb-2" required>
              <label>Nama Belakang:</label>
              <input type="text" name="siswa_nama_belakang" placeholder="Masukkan nama belakang" class="form-control form-control-sm mb-2" required>
             <?php
                include 'includes/db_con.php';
                $sql = "SELECT kelas_id, kelas_nama, guru_id, guru_name, t_ajaran_id
                        FROM kelas 
                        LEFT JOIN guru 
                        ON kelas_wali_guru_id = guru_id 
                        LEFT JOIN t_ajaran 
                        ON kelas_t_ajaran_id = t_ajaran_id 
                        WHERE t_ajaran_active = 1";
                $result = mysqli_query($conn, $sql);

                $options = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
                }

              ?>
              <label>Kelas:</label>
              <select class="form-control form-control-sm" name="kelas_nama_option">
                <?php echo $options;?>
              </select>
              <input type="submit" name="submit_siswa" class="btn btn-primary mt-3" value="Tambah Siswa">
          </div>
      </form>
      </div >
      <!-------------------------form guru----------------------->
      
      <!-------------------------tabel guru----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          
      <?php
            include 'includes/db_con.php';
            $sql3 = "SELECT kelas_id, kelas_nama FROM kelas,t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
            $result3 = mysqli_query($conn, $sql3);
            $options3 = "";
            while ($row = mysqli_fetch_assoc($result3)) {
                $options3 .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
            }
      ?>    
          
      <!-------------------------input search guru----------------->
        
        <div class="alert alert-info">
          <strong>Siswa yang tampil hanya SISWA PADA TAHUN AJARAN yang aktif, silahkan RUBAH TAHUN AJARAN AKTIF jika ingin melihat siswa pada tahun ajaran lain</strong> .
        </div>
      
        <h4 class="mb-3"><u>DATA SISWA</u></h4>
          <form class="form-inline mb-4">
            <div class="form-group">
              <select class="form-control form-control-sm" id="search_kelas_nama_option" name="search_kelas_nama_option">
                <?php echo $options3;?>
              </select>
            </div>
          </form>
        
          <!--action container guru-->
          <div id="container-siswa" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>No induk</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody id="show_siswa">

            </tbody>
          </table>
      </div >
      
      
    <!-------------------------tabel guru----------------------->
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
