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
        
        $("#container-ssp").hide();
        
       //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){
            //alert(bulan_id);
            $.ajax({
                url: 'aspek_ce/display_aspek.php',
//                data:'bulan_id='+ bulan_id,
                type: 'POST',
                success: function(show_ssp){
                    if(!show_ssp.error){
                        $("#show_ssp").html(show_ssp);
                    }
                }
            });
       }
        
        //ketika user menekan tombol submit
        $("#add-ssp-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_ssp").html(php_table_data);
                $("#add-ssp-form")[0].reset();
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
            <strong>Info:</strong> Masukkan Topik Character Building 
          </div>
      <form method="POST" id="add-ssp-form" action="aspek_ce/add_aspek_ce.php">
          <div class="form-group">
              <h4 class="mb-4"><u>TAMBAH TOPIK CB</u></h4>
              
              <input type="text" name="aspek_nama" placeholder="Masukkan topik CB" class="form-control form-control-sm mb-3" required>
              <?php
                return_combo_jenjang("option_jenjang");
              ?>
              <textarea class="form-control form-control-sm mb-2" rows="5" name="aspek_a" id="comment" placeholder="Jika A"></textarea>
              <textarea class="form-control form-control-sm mb-2" rows="5" name="aspek_b" id="comment" placeholder="Jika B"></textarea>
              <textarea class="form-control form-control-sm mb-2" rows="5" name="aspek_c" id="comment" placeholder="Jika C"></textarea>
              <input type="submit" name="submit_ssp" class="btn btn-primary mt-3" value="Tambah TOPIK CB">
          </div>
      </form>
      </div >
      
      <!-------------------------tabel ssp----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <h4 class="mb-3 mt-3"><u>DAFTAR TOPIK</u></h4>
          <!--action container guru-->
          <div id="container-ssp" class= "p-3 mb-2 bg-light border border-primary rounded">
            
          </div>
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Jenjang</th>
                    <th>Nama Topik</th>
                    <th>Jika A</th>
                    <th>Jika B</th>
                    <th>Jika C</th>
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