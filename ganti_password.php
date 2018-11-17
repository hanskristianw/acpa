<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    include_once 'header.php';

?>


<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        //ketika user menekan tombol submit
        $("#ganti-pass-form").submit(function(evt){
            evt.preventDefault();

            var pass_baru1 = $("#guru_password_baru").val();
            var pass_baru2 = $("#guru_password_baru2").val();

            if(pass_baru1!=pass_baru2){
                $("#myModal").show();
            }
            else if(pass_baru1==pass_baru2){
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#show_notif").html(show);
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

<div class="container col-8">
      <!-------------------------form ganti password----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
      
          <div id="show_notif"></div>    
          
      <?php
      
            $guru_id = $_SESSION['guru_id'];
            include 'includes/db_con.php';
            $sql = "SELECT *
                    FROM guru  
                    WHERE guru_id = {$_SESSION['guru_id']} AND guru_active = 1";
            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $guru_name = $row['guru_name'];
            }
      ?>
          
      <form method="POST" id="ganti-pass-form" action="ganti_pass/ganti_pass_proses.php">
          <div class="form-group">
              <h4 class="mb-4"><u>Profile Guru</u></h4>
              <input type="hidden" id="guru_id" name="guru_id" value="<?php echo $guru_id; ?>">
              <label>Nama Lengkap Beserta Gelar:</label>
              <input type="text" name="guru_name" value="<?php echo $guru_name; ?>" placeholder="Masukkan nama lengkap" class="form-control form-control-sm mb-2" required>
              <label>Password Lama:</label>
              <input type="password" id="guru_password" name="guru_password" placeholder="Masukkan password lama" class="form-control form-control-sm mb-2" required>
              <label>Password Baru:</label>
              <input type="password" id="guru_password_baru" name="guru_password_baru" placeholder="Masukkan password baru" class="form-control form-control-sm mb-2" required>
              <label>Password Baru (Lagi):</label>
              <input type="password" id="guru_password_baru2" name="guru_password_baru2" placeholder="Masukkan password baru (lagi)" class="form-control form-control-sm mb-2" required>
             
              <input type="submit" name="submit_password" class="btn btn-primary mt-3" value="Rubah Profile">
          </div>
      </form>
      </div>
</div>

    <div class="modal" id="myModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Infomation</h5>
              <button class="close" id="close_modal">&times;</button>
            </div>
            <div class="modal-body">
                Password baru tidak sama.
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" id="close_modal2">Close</button>
            </div>
          </div>
        </div>
    </div>

<?php
   include_once 'footer.php'
?>
