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
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });


        //ketika user menekan tombol submit
        $("#lapor-form").submit(function(evt){
            evt.preventDefault();

            var option_search_mapel = $("#option_search_mapel").val();
            
            if (option_search_mapel != 0)
            {
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: "POST",
                    success:function(data){
                        if(!data.error){
                            $("#laporan_box").html(data);
                            $("#lapor-form")[0].reset();
                        }
                    }
                });
            }
            else{
                alert("Pilih mapel terlebih dahulu");
            }
        });
        
        $("#option_search_mapel").change(function () {

            var mapel_id = $("#option_search_mapel").val();
            if(mapel_id > 0){
                $.ajax({
                    url: 'laporan/update_option_kelas.php',
                    data:'mapel_id='+ mapel_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#container-option-kelas").html(show);
                        }
                    }
                });
            }
            
        });
        
    });
</script>

<div class="container col-6">
      
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
    
    <!-------------------------form kriteria----------------------->
    <div class= "p-3 mb-2 bg-light border border-primary rounded">
        <div class="alert alert-warning alert-dismissible fade show">
          <button class="close" data-dismiss="alert" type="button">
              <span>&times;</span>
          </button>
          <strong>Info:</strong> Pelajaran yang tampil hanya yang diajar
        </div>
        <form method="POST" id="lapor-form" action="laporan/laporan_lanjut.php">
           
            <div class="form-group"> 
              <select class="form-control form-control-sm mb-2" name="option_search_mapel" id="option_search_mapel">
                <?php echo $options3;?>
              </select>
              
                <div id = "container-option-kelas">
                    
                </div>
                
              <input type="submit" name="submit_kriteria" class="btn btn-primary" value="Cari">
            </div>
        </form>
    </div>
    
    <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
     <div id = "laporan_box">
        
    </div>
    
      
      <div style="margin-top:200px;"></div>
</div>

<!-------------------------modal----------------------->
<?php
   include_once 'footer.php'
?>