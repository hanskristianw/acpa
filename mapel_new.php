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
        $("#form_insert_mapel").hide();
        $("#form_update_mapel").hide();
        $("#option_aksi").change(function () {

            var aksi = $("#option_aksi").val();
            
            if(aksi == 0){
                $("#form_insert_mapel").hide();
                $("#form_update_mapel").hide();
                $("#update-mapel-form-ajax").hide();
                $("#show_form_update").hide();
            }else if (aksi == 1){
                $("#form_insert_mapel").show();
                $("#form_update_mapel").hide();
                $("#update-mapel-form-ajax").hide();
                $("#show_form_update").hide();
                
                $.ajax({
                    url: 'mapel/add-mapel-form-ajax.php',
                    data:'aksi='+ aksi,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            //$("#show_form_update").show();
                            $("#add-mapel-form-ajax").html(show);
                        }
                    }
                });

                $("#notif").hide();
            }else if (aksi == 2){
                $("#form_insert_mapel").hide();
                $("#form_update_mapel").show();
                $("#update-mapel-form-ajax").hide();
                $("#show_form_update").hide();

                $.ajax({
                    url: 'mapel/update-mapel-form-ajax.php',
                    data:'aksi='+ aksi,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#update-mapel-form-ajax").show();
                            $("#update-mapel-form-ajax").html(show);
                            
                        }
                    }
                });

                $("#notif").hide();
            }
                
        });
        
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });
        //ketika user menekan tombol submit
        $("#add-mapel-form").submit(function(evt){
            evt.preventDefault();
            //var postData = $(this).serialize();
            
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_notif").html(show);
                        //$("#add-mapel-form")[0].reset();
                        $('#option_aksi').val('0').change();
                        $('#add-mapel-form').trigger("reset");
                        //$("#form_insert_mapel").hide();
                    }
                }
            });
            
        });
        
        $("#update-mapel-form").submit(function(evt){
            evt.preventDefault();
            //var postData = $(this).serialize();
            
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                data: $(this).serialize(),
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#show_notif2").html(show);
                        //$("#update-mapel-form")[0].reset();
                        $('#mapel_id_option').val('0').change();
                        $('#option_aksi').val('0').change();
                        $('#update-mapel-form').trigger("reset");
                        //$("#form_update_mapel").hide();
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
      
        <div class= "p-3 mb-2 bg-light border border-primary rounded">
            <h4 class="mb-4"><u>Pilih TAMBAH atau UPDATE mapel</u></h4>
            
            
            <div class="mt-4" id="show_notif2">

            </div>
            <div class="mt-4" id="show_notif">

            </div>
            
            
            <select class="form-control form-control-sm mb-3" name="option_aksi" id="option_aksi">
                <option value= 0>Pilih salah satu</option>
                <option value= 1>Tambah Mapel</option>
                <option value= 2>Update Mapel</option>
            </select>
        </div>
      
        <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>

        <div id ="form_insert_mapel" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          <form method="POST" id="add-mapel-form" action="mapel/add_mapel_new.php">
              <div class="form-group">
                  <h4 class="mb-4"><u>TAMBAH MAPEL</u></h4>
                  
                  <input type="text" id="mapel_nama_input" name="mapel_nama_input" placeholder="Masukkan nama lengkap mapel (digunakan dalam cetak rapot)" class="form-control form-control-sm mb-3" required>
                  <input type="text" id="mapel_singkat_nama_input" name="mapel_singkat_nama_input" placeholder="Singkatan nama mapel (ex. MAT,KIM,FIS)" class="form-control form-control-sm mb-3" required>
                  <input type="number" id="mapel_kkm" name="mapel_kkm" placeholder="Masukkan KKM mapel (ex. 70,75,77)" class="form-control form-control-sm mb-3" required>
                  <input type="number" id="mapel_urutan" name="mapel_urutan" placeholder="Masukkan urutan cetak dalam rapot (ex. 1,2,3)" class="form-control form-control-sm mb-3" required>
                  
                  <div id="add-mapel-form-ajax">
                  
                  </div>
                  <input type="submit" name="submit_mapel" id="sub_mapel" class="btn btn-primary mt-3" value="Tambah Mapel">
              </div>
          </form>
            
          
        </div>
    
        <div id ="form_update_mapel" class= "p-3 mb-2 bg-light border border-primary rounded">
            <form method="POST" id="update-mapel-form" action="mapel/update_mapel_new.php">
                <div class="form-group">
                    <h4 class="mb-4"><u>UPDATE MAPEL</u></h4>

                    <div id="update-mapel-form-ajax">
                  
                    </div>
                    
                    <div id="show_form_update">

                    </div>
                </div>
            </form>
        </div>
      
      
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