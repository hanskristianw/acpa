<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }
    include_once 'header.php';

?>


<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });
          
          
        $('#container-notif').hide();
        $('#add_karakter_form_ajax').hide();
        //ketika user menekan tombol submit
        $("#detail-karakter").submit(function(evt){
            evt.preventDefault();

            var mapel_id = $("#option_mapel").val();
            
            if(mapel_id >0){
                $.ajax({
                    url: "d_karakter/proses_detail_karakter.php",
                    data: $(this).serialize(),
                    type: "POST",
                    success:function(data){
                        $('#container-notif').show();
                        $("#container-notif").html(data);
                        $("#detail-karakter")[0].reset();
                        $('#add_karakter_form_ajax').hide();
                    }
                });
            }
            else{
                alert("Pilih mapel dan karakter!");
            }
        });
        
        $("#option_mapel").change(function () {
            var mapel_id = $("#option_mapel").val();
            if(mapel_id >0){
                $.ajax({
                    url: "d_karakter/add_karakter_form_ajax.php",
                    data:'mapel_id='+ mapel_id,
                    type: "POST",
                    success:function(data){
                        $('#add_karakter_form_ajax').show();
                        $("#add_karakter_form_ajax").html(data);
                        $('#container-notif').hide();
                    }
                });
            }else{
                $('#add_karakter_form_ajax').hide();
            }
        });
        
    });
</script>

<div class="container col-8">
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="step1">
          
          <div id="container-notif">
            
          </div>
          
          <h4 class="mb-4 text-center"><u>KARAKTER PELAJARAN</u></h4>
            <?php
                include 'includes/db_con.php';
                $sql2 = "SELECT * FROM mapel, t_ajaran WHERE mapel_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result2 = mysqli_query($conn, $sql2);
                
                $options2 = "<option value= 0>Pilih Mapel</option>";
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $options2 .= "<option value={$row2['mapel_id']}>{$row2['mapel_nama']}</option>";
                }
            ?>
          
          
          <form method="POST" id="detail-karakter" action="d_karakter/proses_detail_karakter.php">
              <select class="form-control form-control-sm mb-4" name="option_mapel" id="option_mapel">
                <?php echo $options2;?>
              </select>
              
              <div id="add_karakter_form_ajax">
                  
              </div>
              
          </form>
          
      </div>
    
      <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      
      <div style="margin-top:200px;"></div>

    <!-------------------------modal----------------------->
</div>
<?php
   include_once 'footer.php'
?>
