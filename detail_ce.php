<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 4){
        header("Location: index.php");
    }
  include_once 'header.php';
?>

<script>
    $(document).ready(function(){
        
        $("#container-ssp").hide();

            
        $("#option_tema_ce2").change(function () {
            var ce_id = $("#option_tema_ce2").val();
            $.ajax({
                url: "detail_ce/display_detail.php",
                data:'ce_id='+ ce_id,
                type: "POST",
                success:function(data){
                    $("#show_ssp").html(data);
                }
            });
        });

        //ketika user menekan tombol submit
        $("#add-ssp-form").submit(function(evt){
            evt.preventDefault();
            var tema_ce = $("#option_tema_ce1").val();

            if(tema_ce>0){
                var postData = $(this).serialize();
                var url = $(this).attr('action');

                $.post(url,postData, function(php_table_data){
                    $("#hasil_ssp").html(php_table_data);
                    $("#add-ssp-form")[0].reset();
                    $("#myModal").show();
                });
            }else{
                alert("Pilih tema terlebih dahulu");
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
          <?php 
              $pesan = return_alert("Masukkan Indikator Untuk Tema","warning");
              echo $pesan;
          ?>
      <form method="POST" id="add-ssp-form" action="detail_ce/add_detail_ce.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH INDIKATOR UNTUK TEMA CB</u></h4>
              <?php 
                  return_combo_tema_ce("option_tema_ce1");
              ?>
              <input type="text" name="indikator_nama" placeholder="Masukkan indikator untuk tema" class="form-control form-control-sm mb-2" required>
              <textarea class="form-control form-control-sm mb-2" rows="5" name="indikator_a" id="comment" placeholder="Deskripsi Jika A"></textarea>
              <textarea class="form-control form-control-sm mb-2" rows="5" name="indikator_b" id="comment" placeholder="Deskripsi Jika B"></textarea>
              <textarea class="form-control form-control-sm mb-2" rows="5" name="indikator_c" id="comment" placeholder="Deskripsi Jika C"></textarea>
              <input type="submit" name="submit_ssp" class="btn btn-primary" value="Tambah Indikator CB">
          </div>
      </form>
      </div >
      
      <!-------------------------tabel ssp----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <h4 class="mb-3 mt-3"><u>DAFTAR INDIKATOR</u></h4>
          <?php 
              return_combo_tema_ce("option_tema_ce2");
          ?>
          <!--action container guru-->
          <div id="container-ssp" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Nama Indikator</th>
                    <th>Deskripsi Jika A</th>
                    <th>Deskripsi Jika B</th>
                    <th>Deskripsi Jika C</th>
                </tr>
            </thead>
            <tbody id="show_ssp">

            </tbody>
          </table>
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