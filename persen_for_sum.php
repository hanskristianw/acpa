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
        
        $("#add-persen-form").submit(function(evt){
            evt.preventDefault();

            var mapel_id = $("#option_mapel").val();
            var persen_for = $("#persen_for").val();
            var persen_sum = $("#persen_sum").val();
            var total_persen = parseInt(persen_for) + parseInt(persen_sum);
            
            if(mapel_id>0 && total_persen == 100){
                $.ajax({
                    url: 'persen_for_sum/proses_persen_for_sum.php',
                    data: $(this).serialize(),
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            alert(show);
                        }
                    }
                });
                
            }else{
                alert("Pilih Mapel dan Persentase Harus = 100");
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

      <form method="POST" id="add-persen-form" action="persen_for_sum/proses_persen_for_sum.php">
          <div class="form-group mt-4">
              
            <label>Mapel:</label>
            <select class="form-control form-control-sm mb-2" name="option_mapel" id="option_mapel">
                <?php echo $options3;?>
            </select>
              
            <div id="form_for_sum"></div>
              
          </div>
      </form>
</div>
   
<?php
   include_once 'footer.php'
?>