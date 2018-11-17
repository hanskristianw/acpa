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
        $("#add-ce-form").submit(function(evt){
            evt.preventDefault();

            var kelas_id = $("#option_kelas").val();
            var aspek_id = $("#option_aspek").val();
            if(kelas_id>0 && aspek_id>0){
                $.ajax({
                    url: 'ce_nilai/display_ce_nilai.php',
                    data: $(this).serialize(),
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            //alert(show);
                            $("#kotak_utama").show();
                            $("#kotak_utama").html(show);
                        }
                    }
                });
                
            }else{
                alert("Pilih Kelas dan Aspek");
            }

        });
        
        $("#option_kelas").change(function () {
            $("#kotak-utama").hide();
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
          
          <div id="feedback"></div>
          <h4 class="mb-4"><u>NILAI CHARACTER EDUCATION</u></h4>
          
        <form method="POST" id="add-ce-form" action="ce_nilai/display_ce_nilai.php">
          
                <?php
                    include ("includes/fungsi_lib.php"); 
                    return_combo_kelas(0);
                    
                    include ("includes/db_con.php");

                    $query_Aspek ="SELECT *
                            FROM ce
                            LEFT JOIN t_ajaran
                            ON ce_t_ajaran_id = t_ajaran_id
                            WHERE t_ajaran_active = 1";

                    $query_info = mysqli_query($conn, $query_Aspek);

                    $options = "<option value= 0>Pilih Aspek</option>";
                     while($row = mysqli_fetch_array($query_info)){
                        $kelas_nama = $row['ce_aspek'];

                        $options .= "<option value={$row['ce_id']}>$kelas_nama</option>";
                     }

                    echo "<label>Pilih Aspek:</label>"; 
                    echo"<select class='form-control form-control-sm mb-2' name='option_aspek' id='option_aspek'>";
                        echo $options;
                    echo"</select>";
                ?>
              
            <input type="submit" name="submit_kriteria" class="btn btn-primary mt-3" value="Proses">
      </form>
    </div>
      
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