<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }
  include_once 'header.php';
  include ("includes/db_con.php");
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#container-kriteria").hide();

        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });
          
       function updateTableUjian(){

            $.ajax({
                url: 'revisi/display_rev_ujian.php',
                type: 'POST',
                success: function(show_guru){
                    if(!show_guru.error){
                        $("#tabel_ujian").html(show_guru);
                    }
                }
            });
       }
       function updateTableTes(){

            $.ajax({
                url: 'revisi/display_revisi_tes.php',
                type: 'POST',
                success: function(show_guru){
                    if(!show_guru.error){
                        $("#tabel_tes").html(show_guru);
                    }
                }
            });
       }
       
       function updateTableSSP(){

            $.ajax({
                url: 'revisi/display_revisi_ssp.php',
                type: 'POST',
                success: function(show_guru){
                    if(!show_guru.error){
                        $("#tabel_ssp").html(show_guru);
                    }
                }
            });
       }
       
       updateTableUjian();
       updateTableTes(); 
       updateTableSSP(); 
       
        //ketika user menekan tombol submit
        $("#revisi_tabel").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#container-hasil").html(php_table_data);
                $("#revisi_tabel")[0].reset();
                updateTableUjian();
//                $("#myModal").show();
            });
            
        });
        
        //ketika user menekan tombol submit
        $("#revisi_tes").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#container-hasil").html(php_table_data);
                $("#revisi_tes")[0].reset();
                updateTableTes();
//                $("#myModal").show();
            });
            
        });
        
        $("#revisi_ssp").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#container-hasil").html(php_table_data);
                $("#revisi_ssp")[0].reset();
                updateTableSSP();
//                $("#myModal").show();
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
    
    <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      <!-------------------------tabel kriteria----------------------->
      <div id="container-hasil">
            
      </div>
      
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          <h4 class="text-center mb-3"><u>Daftar Revisi nilai UTS dan UAS</u></h4>
          <?php
            $result = mysqli_query($conn, "SELECT count(*) FROM kog_psi_ujian_revisi WHERE ujian_rev_status= 0");
            $row_ujian = mysqli_fetch_row($result);
            $count_ujian = $row_ujian[0];
            if($count_ujian>0) {
                echo'<form method="POST" id="revisi_tabel" class="revisi_tabel" action="revisi/proses_revisi.php">


                      <div id="tabel_ujian">

                      </div>
                      </form>';
            }else{
              echo'<h6 class="text-center bg-success">-Data Pengajuan Revisi Ujian Tidak ada-</h2>';
            }
          ?>
      </div >
      
      <div class= "p-3 mb-2 mt-3 bg-light border border-primary rounded">
          <h4 class="text-center mb-3"><u>Daftar Revisi nilai Assignment, Test, Quiz</u></h4>
          <?php
            $result2 = mysqli_query($conn, "SELECT count(*) FROM kog_psi_revisi WHERE rev_status= 0");
            $row_rev = mysqli_fetch_row($result2);
            $count_rev = $row_rev[0];
            if($count_rev>0) {
                echo'<form method="POST" id="revisi_tes" class="revisi_tes" action="revisi/proses_revisi_tes.php">


                      <div id="tabel_tes">

                      </div>
                      </form>';
            }else{
              echo'<h6 class="text-center bg-success">Data Pengajuan Revisi Tes Tidak ada</h2>';
            }
          ?>
      </div>
      
      <div class= "p-3 mb-2 mt-3 bg-light border border-primary rounded">
          <h4 class="text-center mb-3"><u>Daftar Revisi SSP</u></h4>
          <?php
            $result3 = mysqli_query($conn, "SELECT count(*) FROM ssp_revisi WHERE ssp_rev_status= 0");
            $row_rev_ssp = mysqli_fetch_row($result3);
            $count_rev_ssp = $row_rev_ssp[0];
            if($count_rev_ssp>0) {
                echo'<form method="POST" id="revisi_ssp" class="revisi_ssp" action="revisi/proses_revisi_ssp.php">


                      <div id="tabel_ssp">

                      </div>
                      </form>';
            }else{
              echo'<h6 class="text-center bg-success">Data Pengajuan Revisi SSP Tidak ada</h2>';
            }
          ?>
      </div>
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