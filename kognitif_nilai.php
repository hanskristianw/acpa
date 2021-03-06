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
        
        $("#container-jenjang").hide();
        $("#kotak").hide();
        
        $("#option_mapel").change(function () {

            $("#kotak").hide();
            var mapel_id = $("#option_mapel").val();
            
            $.ajax({
                url: 'kognitif_nilai/update_option_kelas.php',
                data:'mapel_id='+ mapel_id,
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#container-option-kelas").html(show);
                    }
                }
            });
            
        });
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });
          
        //ketika user menekan tombol submit
        $("#add-jenjang-form").submit(function(evt){
            evt.preventDefault();

            var mapel_id = $("#option_mapel").val();
            var kelas_id = $("#option_kelas").val();
            var topik_id = $("#option_topik").val();
            
            
            if(mapel_id > 0 && kelas_id > 0 && topik_id > 0){
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#show_kognitif").html(show);
                            $("#kotak").hide();
                            $("#kotak").show();
                            $("#container-temp").hide();
                        }
                    }
                });
            }else if(mapel_id <= 0){
                alert("Pilih mapel")
            }else if(kelas_id <= 0){
                alert("Pilih kelas")
            }else if(topik_id <= 0){
                alert("Pilih topik")
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



<div class="container">
    <div id="container-temp">

    </div>
    
    <div id="container-temp2">

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
    
    
      <!-------------------------form cari mapel----------------------->
        <div class= "p-3 mb-2 bg-light border border-primary rounded">
            <form method="POST" id="add-jenjang-form" action="kognitif_nilai/display_kognitif.php">
                <div class="form-group col-sm">
                    <h4 class="mb-4 mt-4"><u>Nilai Pengetahuan dan Keterampilan</u></h4>

                    <label>Nama Mapel:</label>
                    <select class="form-control form-control-sm mb-2" name="option_mapel" id="option_mapel">
                        <?php echo $options3;?>
                    </select>
                    
                    
                    <div id="container-option-kelas">

                    </div>
                    
                    <div id="container-option-topik">

                    </div>
                </div>
            </form>
        </div>
      <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      <!-------------------------tabel jenjang----------------------->
        <div id="kotak" class="p-3 mb-2 bg-light border border-primary rounded">
            <h4 class="mb-4 mt-4"><u>Nilai Kognitif</u></h4>
            
            <!-------------------------tabel kognitif psikomotor----------------------->
            
            <div id="show_kognitif">

            </div>
            
        </div>
      
      
    <!-------------------------end of tabel kelas----------------------->
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
    
    <div class="modal" id="myModal2">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Infomation</h5>
              <button class="close" id="close_modal_username">&times;</button>
            </div>
            <div class="modal-body">
                Username sudah dipakai.
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" id="close_modal_username2">Close</button>
            </div>
          </div>
        </div>
    </div>
    
    <!-------------------------modal----------------------->


<?php
   include_once 'footer.php'
?>