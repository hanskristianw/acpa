<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>
    var isPaused = false; 
    $(document).ready(function(){
        
        $("#kotak_utama").hide();
//        $("#kotak_utama2").hide();
//        //ketika user menekan tombol submit
        $("#add-ssp-form").submit(function(evt){
            evt.preventDefault();

            var ssp_id = $("#ssp_option").val();
            var rubrik_id = $("#option_rubrik").val();
            if(ssp_id>0 && rubrik_id>0){
                //alert("pilihan benar");
                var postData = $(this).serialize();
                var url = $(this).attr('action');
                
                //input rubrik
                $.post(url,postData, function(php_table_data){
                    $("#kotak_utama").show();
                    //$("#kotak_utama2").show();
                    $("#kotak_utama").html(php_table_data);
                    $("#add-ssp-form")[0].reset();
                });
                
            }else{
                alert("pilihan harus benar");
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

        $("#ssp_option").change(function () {
            //alert("a");
            
            var ssp_id = $("#ssp_option").val();
            
            if(ssp_id != 0){
                $.ajax({
                    url: 'ssp_nilai_input/update_option_topik_ssp.php',
                    data:'ssp_id='+ ssp_id,
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            $("#container-daftar-rubrik").html(show);
                        }
                    }
                });
            }else{
                alert("Pilihan harus benar!");
            }
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
            <strong>Info:</strong> Pilih SSP yang diajar
          </div>
          
          
          <div id="feedback"></div>
          
          <form method="POST" id="add-ssp-form" action="ssp_nilai_input/display_siswa_ssp.php">
          <?php
            $guru_id = $_SESSION['guru_id'];
            
            include 'includes/db_con.php';
            $sql = "SELECT * FROM ssp
                    LEFT JOIN guru
                    ON ssp_guru_id = guru_id
                    LEFT JOIN t_ajaran
                    ON ssp_t_ajaran_id = t_ajaran_id
                    WHERE guru_id = $guru_id AND t_ajaran_active = 1";
            $result = mysqli_query($conn, $sql);

            $options = "<option value=0>Pilih SSP</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $options .= "<option value={$row['ssp_id']}>{$row['ssp_nama']}</option>";
            }
          ?>
            <select class="form-control form-control-sm mb-2" name="ssp_option" id="ssp_option">
                <?php
                    echo $options;
                ?>
            </select>
          
            <div id="container-daftar-rubrik"></div>
              
            <input type="submit" name="submit_kriteria" class="btn btn-primary mt-3" value="Proses">
      </form>
    </div>
    <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak_utama">
          
      </div>
</div> 
      <div style="margin-top:200px;"></div>
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