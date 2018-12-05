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
        $("#containerDetailAspek").hide();
        
        $("#add-ce-form").submit(function(evt){
            evt.preventDefault();

            var kelas_id = $("#option_kelas").val();
            var aspek_id = $("#option_aspek").val();
            var indikator_id = $("#option_indikator").val();
            if(kelas_id>0 && aspek_id>0 && indikator_id>0){
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
                alert("Pilih Kelas, Topik dan Indikator");
            }

        });
        
        $("#option_kelas").change(function () {
            $("#kotak-utama").hide();
            var kelas_id = $("#option_kelas").val();
            if(kelas_id>0){
                $.ajax({
                    url: 'ce_nilai/update_option_topik.php',
                    data: 'kelas_id='+ kelas_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#containerTopik").show();
                            $("#kotak_utama").hide();
                            $("#containerTopik").html(show);
                            $("#containerDetailAspek").hide();
                        }
                    }
                });
                
            }
        });

        $("#option_mapel").change(function () {
            var mapel_id = $("#option_mapel").val();
            
            if(mapel_id >0){
                $.ajax({
                    url: 'persen_for_sum/update_form.php',
                    data: 'mapel_id='+ mapel_id,
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            $("#form_for_sum").html(show);
                        }
                    }
                });
            }
        });
    });
</script>

<div class="container col-4 mt-4 p-3 mb-2 bg-light border border-primary rounded">
      <!-------------------------tabel t_ajaran----------------------->
      <div class="alert alert-info mt-3 mb-4">
        <strong>Info: </strong> Set persentase formative dan summative.
      </div>
      
      <div id="show_t_ajaran">
          
      </div>
      
        <?php
            $guru_id = $_SESSION['guru_id'];

            include 'includes/db_con.php';
            $sql3 = "SELECT DISTINCT d_mapel_id_mapel, mapel_nama
                        FROM d_mapel
                        LEFT JOIN mapel
                        ON d_mapel_id_mapel = mapel_id
                        LEFT JOIN t_ajaran
                        ON mapel_t_ajaran_id = t_ajaran_id
                        WHERE t_ajaran_active = 1 AND d_mapel_id_guru = $guru_id";
            $result3 = mysqli_query($conn, $sql3);

            $options3 = "<option value= 0>Pilih Mapel</option>";
            while ($row3 = mysqli_fetch_assoc($result3)) {

                $mapel_nama = $row3['mapel_nama'];

                $options3 .= "<option value={$row3['d_mapel_id_mapel']}>$mapel_nama</option>";
            }
        ?>

      <form method="POST" id="add-persen-form" action="persen_for_sum/proses_for_sum.php">
          <div class="form-group mt-4">
              
            <label>Mapel:</label>
            <select class="form-control form-control-sm mb-2" name="option_mapel" id="option_mapel">
                <?php echo $options3;?>
            </select>
              
            <div id="form_for_sum"></div>
              
            <input type="submit" name="submit_t_ajaran" class="btn btn-primary mt-3" value="UPDATE">
          </div>
      </form>
</div>
   
<?php
   include_once 'footer.php'
?>