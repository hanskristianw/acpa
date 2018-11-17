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
        
        $("#container-mapel").hide();
        
        //interval
         setInterval(function(){
            if(!isPaused)
                updateTable();
         },1000);
       
        //refresh table
        function updateTable(){
            var mapel_id = $("#mapel_id_option").val();
            //alert(mapel_id);
            $.ajax({
                url: 'mapel/display_mapel.php',
                data:'mapel_id='+ mapel_id,
                type: 'POST',
                success: function(show_mapel){
                    if(!show_mapel.error){
                        $("#show_mapel").html(show_mapel);
                    }
                }
            });
        }
        
        $.ajax({
            url: 'mapel/cek_option_mapel.php',
            type: 'POST',
            success: function(show_mapel){
                if(!show_mapel.error){
                    $("#mapel_id_option").html(show_mapel);

                }
            }
        });
        
        //menampilkan suggestion
        $('#mapel_nama_input').typeahead({
            source: function(query, result)
            {
                $.ajax({
                    url:"mapel/auto_mapel.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                        result($.map(data, function(item){
                            return item;
                        }));
                    }
                })
            }
        });
        
        //merubah button jika sudah ada nama mapel
        $("#mapel_nama_input").focusout(function(){
            var mapel_nama = $("#mapel_nama_input").val();
            $.ajax({
                url: "mapel/cek_nama_mapel.php",
                data:'mapel_nama='+ mapel_nama,
                dataType : 'json',
                type: "POST",
                success:function(data){
                    if(data['status'] == 1){
                        $("#sub_mapel").val("Tambah Detail Mapel");
                    }
                    else{
                        $("#sub_mapel").val("Tambah Nama + Detail Mapel");
                    }
                    $.ajax({
                        url: 'mapel/cek_option_kelas.php',
                        data:'mapel_nama='+ mapel_nama,
                        type: 'POST',
                        success: function(show_mapel){
                            if(!show_mapel.error){
                                //alert(show_mapel);
                                $("#kelas_id_option").html(show_mapel);
                            }
                        }
                    });
                }
            });
        });
        
        //ketika user menekan tombol submit
        $("#add-mapel-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_mapel").html(php_table_data);
                $("#add-mapel-form")[0].reset();
                $("#myModal").show();
            });
            
            $.ajax({
            url: 'mapel/cek_option_mapel.php',
            type: 'POST',
            success: function(show_mapel){
                if(!show_mapel.error){
                    $("#mapel_id_option").html(show_mapel);

                }
            }
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
      
      <!-------------------------form mapel----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
<!--          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Guru yang tampil pada daftar wali kelas HANYA guru aktif dengan jabatan wali kelas
          </div>-->
      <form method="POST" id="add-mapel-form" action="mapel/add_mapel.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH MAPEL</u></h4>
              <label>Nama mapel:</label>
              <input type="text" id="mapel_nama_input" name="mapel_nama_input" placeholder="Masukkan nama mapel" autocomplete="off" class="form-control form-control-sm mb-3" required>
  
              <label>Singkatan nama mapel (ex. MAT,KIM, FIS):</label>
              <input type="text" id="mapel_singkat_nama_input" name="mapel_singkat_nama_input" placeholder="Masukkan singkatan mapel" class="form-control form-control-sm mb-3" required>
              
              <?php
                include 'includes/db_con.php';
                $sql2 = "SELECT guru_id, guru_name FROM guru WHERE guru_active = 1";
                $result2 = mysqli_query($conn, $sql2);
                $options2 = "";
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $options2 .= "<option value={$row2['guru_id']}>{$row2['guru_name']}</option>";
                }
              ?>
              <label>Mapel Terdapat Pada Kelas:</label>
              <select class="form-control form-control-sm mb-3" name="kelas_id_option" id="kelas_id_option">
                
              </select>
              <label>Guru Mapel Pada Kelas Diatas:</label>
              <select class="form-control form-control-sm" name="guru_id_option">
                <?php echo $options2;?>
              </select>
              
              <input type="submit" name="submit_mapel" id="sub_mapel" class="btn btn-primary mt-3" value="Tambah Nama + Detail Mapel">
          </div>
      </form>
      </div >
      <!-------------------------end of form mapel----------------------->
      
      <!-------------------------tabel mapel----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          
          
          
          
          <!--action container mapel-->
          <div id="container-mapel" class= "p-3 mb-2 bg-light border border-primary rounded"></div>
          
          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Silahkan refresh page jika menambah mapel baru
          </div>
          
          <label>Mapel yang ingin ditampilkan:</label>
          <select class="form-control form-control-sm" id="mapel_id_option"></select>
          
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Terdapat Pada Kelas</th>
                    <th>Guru Pengajar</th>
                </tr>
            </thead>
            <tbody id="show_mapel">

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