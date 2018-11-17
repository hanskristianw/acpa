<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }
    include_once 'header.php';

?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#container-guru").hide();
        
        //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){

            $.ajax({
                url: 'guru/display_guru.php',
                type: 'POST',
                success: function(show_guru){
                    if(!show_guru.error){
                        $("#show_guru").html(show_guru);
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
       
       //keyup function untuk search
       $('#search_guru_input').keyup(function(){
           
            
            var search = $('#search_guru_input').val();
            
            if(search)
                isPaused = true;
            else
                isPaused = false;
      
            $.ajax({
                url:'guru/search_guru.php',
                data:{search:search},
                type:'POST',
                success:function(data){
                    if(!data.error){
                        $('#show_guru').html(data);
                    }
                }
            });
        });
        
        //ketika user menekan tombol submit
        $("#add-guru-form").submit(function(evt){
            evt.preventDefault();

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
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_guru").html(php_table_data);
                $("#add-guru-form")[0].reset();
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
      
      <!-------------------------form guru----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">

      <form method="POST" id="add-guru-form" action="guru/add_guru.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH LOGIN GURU</u></h4>
              <label>Nama Guru:</label>
              <input type="text" name="guru_nama_input" placeholder="Masukkan nama" class="form-control form-control-sm mb-2" required>
              <label>Username:</label>
              <input type="text" name="guru_username_input" id="guru_username_input" placeholder="Masukkan username" class="form-control form-control-sm mb-2" required>
              <label>Password:</label>
              <input type="password" name="guru_password_input" placeholder="Masukkan password" class="form-control form-control-sm mb-2" required>
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM jabatan WHERE jabatan_active = 1";
                $result = mysqli_query($conn, $sql);

                $options = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options .= "<option value={$row['jabatan_id']}>{$row['jabatan_nama']}</option>";
                }

              ?>
              <label>Jabatan:</label>
              <select class="form-control form-control-sm" name="guru_jabatan_option">
                <?php echo $options;?>
              </select>
              <input type="submit" name="submit_guru" class="btn btn-primary mt-3" value="Tambah Guru">
          </div>
      </form>
      </div >
      <!-------------------------end of form guru----------------------->
      <!-------------------------tabel guru----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
      <!-------------------------input search guru----------------->
          <div class="form-group">
            <h4 class="mb-3"><u>SEARCH GURU</u></h4>
            <input type="text" name="search_guru_input" id="search_guru_input" class="form-control form-control-sm mb-3" placeholder="Masukkan nama">
          </div>
          <h4 class="mb-3 mt-3"><u>DAFTAR GURU</u></h4>
          <!--action container guru-->
          <div id="container-guru" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Username Guru</th>
                    <th>Jabatan Guru</th>
                </tr>
            </thead>
            <tbody id="show_guru">

            </tbody>
          </table>
      </div>
      
      
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
