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
        
        $("#container-karakter").hide();
        
       //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){
            //alert(bulan_id);
            $.ajax({
                url: 'karakter/display_karakter.php',
                type: 'POST',
                success: function(show_kriteria){
                    if(!show_kriteria.error){
                        $("#show_kriteria").html(show_kriteria);
                    }
                }
            });
       }
        
        //ketika user menekan tombol submit
        $("#add-karakter-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_kriteria").html(php_table_data);
                $("#add-karakter-form")[0].reset();
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
          
      <form method="POST" id="add-karakter-form" action="karakter/add_karakter.php">
          <div class="form-group">
              <h4 class="mb-4 text-center"><u>KARAKTER MAPEL</u></h4>
              
              <input type="text" name="nama_karakter" placeholder="Masukkan nama karakter" class="form-control form-control-sm mb-2" required>
              <textarea class="form-control form-control-sm mb-2" rows="5" placeholder="Masukkan deskripsi jika A" name="kar_des_a" id="comment" required></textarea>
              <textarea class="form-control form-control-sm mb-2" rows="5" placeholder="Masukkan deskripsi jika B" name="kar_des_b" id="comment" required></textarea>
              <textarea class="form-control form-control-sm mb-2" rows="5" placeholder="Masukkan deskripsi jika C" name="kar_des_c" id="comment" required></textarea>
              <input type="number" name="urutan_cetak" placeholder="Masukkan urutan cetak pada rapor" class="form-control form-control-sm mb-2" required>
              
              <input type="submit" name="submit_kriteria" class="btn btn-primary mt-3" value="Tambah Karakter">
          </div>
      </form>
      </div >
      <!-------------------------end of form kriteria----------------------->
      
      <!-------------------------tabel kriteria----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <h4 class="mb-4 text-center"><u>DAFTAR KARAKTER</u></h4>
          <!--action container kriteria-->
          <div id="container-karakter" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          
          <div id="show_kriteria">
              
          </div>
          
          
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
                Data Berhasil Ditambahkan.
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