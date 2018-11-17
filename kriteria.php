<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 4){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#container-kriteria").hide();
        
       //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },3000);
       
       //refresh table
       function updateTable(){
            var bulan_id = $("#kriteria_bulan_option").val();
            //alert(bulan_id);
            $.ajax({
                url: 'afektif/display_kriteria.php',
                data:'bulan_id='+ bulan_id,
                type: 'POST',
                success: function(show_kriteria){
                    if(!show_kriteria.error){
                        $("#show_kriteria").html(show_kriteria);
                    }
                }
            });
       }
        
        //ketika user menekan tombol submit
        $("#add-kriteria-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_kriteria").html(php_table_data);
                $("#add-kriteria-form")[0].reset();
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
          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Pilih bulan, masukkan 3 indikator afektif dan judul topik afektif
          </div>
      <form method="POST" id="add-kriteria-form" action="afektif/add_kriteria.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH/UPDATE INDIKATOR</u></h4>
              <select class="form-control form-control-sm mb-3" name="kriteria_bulan_option">
                  <?php
                    if($semester == 1){
                        echo"<option value = 7>Juli</option>
                            <option value = 8>Agustus</option>
                            <option value = 9>September</option>
                            <option value = 10>Oktober</option>
                            <option value = 11>November</option>
                            <option value = 12>Desember</option>";
                    }
                    else if($semester == 2){
                        echo"<option value = 1>Januari</option>
                            <option value = 2>Februari</option>
                            <option value = 3>Maret</option>
                            <option value = 4>April</option>
                            <option value = 5>Mei</option>
                            <option value = 6>Juni</option>";
                    }
                  ?>
              </select>
              
              <input type="text" name="judul_topik" placeholder="Masukkan judul topik" class="form-control form-control-sm mb-3" required>
              <input type="text" name="kriteria_1_input" placeholder="Masukkan indikator pertama" class="form-control form-control-sm mb-3" required>
              <input type="text" name="kriteria_2_input" placeholder="Masukkan indikator kedua" class="form-control form-control-sm mb-3" required>
              <input type="text" name="kriteria_3_input" placeholder="Masukkan indikator ketiga" class="form-control form-control-sm mb-3" required>
              
              <input type="submit" name="submit_kriteria" class="btn btn-primary mt-3" value="Tambah/Update Indikator">
          </div>
      </form>
      </div >
      <!-------------------------end of form kriteria----------------------->
      
      <!-------------------------tabel kriteria----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">

          <!--action container kriteria-->
          <div id="container-kriteria" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          
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
          
          <h4 class="mb-4"><u>Tahun Ajaran 
                 <?php
                    echo $t_ajaran .' Semester '.$t_ajaran_semester;
                 ?>
          </u></h4>
          
          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Tunggu 3 detik setelah memilih bulan
          </div>
          
          <select class="form-control form-control-sm mb-3" id="kriteria_bulan_option">
                  <?php
                    if($semester == 1){
                        echo"<option value = 7>Juli</option>
                            <option value = 8>Agustus</option>
                            <option value = 9>September</option>
                            <option value = 10>Oktober</option>
                            <option value = 11>November</option>
                            <option value = 12>Desember</option>";
                    }
                    else if($semester == 2){
                        echo"<option value = 1>Januari</option>
                            <option value = 2>Februari</option>
                            <option value = 3>Maret</option>
                            <option value = 4>April</option>
                            <option value = 5>Mei</option>
                            <option value = 6>Juni</option>";
                    }
                  ?>
          </select>
          
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