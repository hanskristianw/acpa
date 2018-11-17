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
        
        $("#container-jenjang").hide();
        
       //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){

            $.ajax({
                url: 'jenjang/display_jenjang.php',
                type: 'POST',
                success: function(show_jenjang){
                    if(!show_jenjang.error){
                        $("#show_jenjang").html(show_jenjang);
                    }
                }
            });
       }
        
        //ketika user menekan tombol submit
        $("#add-jenjang-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_jenjang").html(php_table_data);
                $("#add-jenjang-form")[0].reset();
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
    
    
      <!-------------------------form jenjang----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
      <form method="POST" id="add-jenjang-form" action="jenjang/add_jenjang.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH JENJANG</u></h4>
              <label>Nama Jenjang:</label>
              <input type="text" name="jenjang_nama_input" placeholder="Masukkan nama jenjang, cth: X, XI, XII" class="form-control form-control-sm mb-2" required>
              <input type="submit" name="submit_jenjang" class="btn btn-primary mt-3" value="Tambah Jenjang">
          </div>
      </form>
      </div >
      
      <!-------------------------tabel jenjang----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <h4 class="mb-4"><u>TABEL DAFTAR JENJANG</u></h4>
          <!--action container jenjang-->
          <div id="container-jenjang" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Nama Jenjang</th>
                </tr>
            </thead>
            <tbody id="show_jenjang">

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