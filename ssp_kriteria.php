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
        
        $("#container-kriteria").hide();
        $("#container-rubrik").hide();
        //interval
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
        
        function updateTable(){
            var ssp_id = $("#ssp_option").val();
            //alert(ssp_id);
            if(ssp_id != 0){
                    $.ajax({
                    url: 'ssp_nilai/display_rubrik.php',
                    data:'ssp_id='+ ssp_id,
                    type: 'POST',
                    success: function(show_rubrik_ssp){
                        if(!show_rubrik_ssp.error){
                            $("#kotak_utama2").html(show_rubrik_ssp);
                        }
                    }
                });
            }
       }
       
        
        $("#kotak_utama").hide();
        $("#kotak_utama2").hide();
        //ketika user menekan tombol submit
        $("#add-ssp-form").submit(function(evt){
            evt.preventDefault();

            var cek_pil1 = $("#ssp_option").val();
            var cek_pil2 = $("#ssp_option2").val();

            if(cek_pil1>0 && cek_pil2>0){
                //alert("pilihan benar");
                var postData = $(this).serialize();
                var url = $(this).attr('action');
                
                if(cek_pil2 == 1){
                    //input rubrik
                    $.post(url,postData, function(php_table_data){
                        $("#kotak_utama").show();
                        $("#kotak_utama2").show();
                        $("#kotak_utama").html(php_table_data);
                        //$("#add-ssp-form")[0].reset();
                    });
                }
                else if(cek_pil2 == 3){
                    //daftarkan siswa
                    $.post(url,postData, function(php_table_data){
                        $("#kotak_utama").show();
                        $("#kotak_utama2").hide();
                        $("#container-rubrik").hide();
                        $("#kotak_utama").html(php_table_data);
                        $("#add-ssp-form")[0].reset();
                    });
                }
            }else{
                alert("pilihan harus benar");
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
          <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Pilih SSP yang diajar
          </div>
          <form method="POST" id="add-ssp-form" action="ssp_nilai/ssp_kriteria_ajax.php">
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
          
            <select class="form-control form-control-sm" name="ssp_option2" id="ssp_option2">
                <option value= 0>Pilih Yang Akan Diinput</option>
                <option value= 1>Input Rubrik/Kriteria</option>
                <option value= 3>Daftarkan Siswa ke SSP</option>
            </select>
              
            <input type="submit" name="submit_kriteria" class="btn btn-primary mt-3" value="Proses">
      </form>
    </div>
      
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak_utama">
          
      </div>
      <div id="container-rubrik" class= "p-3 mb-2 bg-light border border-primary rounded">
            
      </div>
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak_utama2">
          <div id="show_rubrik_ssp">
              
          </div>
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