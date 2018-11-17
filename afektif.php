<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] == 4){
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
        
        $("#container-afektif").hide();
        $("#step2").hide();
        
        $("#option_bulan_afektif").change(function () {

            var bulan_id = $("#option_bulan_afektif").val();
            $("#option_utama").val("0");
            $("#container-afektif").hide();
            $("#container-hasil").hide();
                //alert(bulan_id);
            $.ajax({
                url: 'afektif/display_kriteria.php',
                data:'bulan_id='+ bulan_id,
                type: 'POST',
                success: function(show_kriteria){
                    if(!show_kriteria.error){
                        $("#show_kriteria").html(show_kriteria);
                    }
                }
            });
            
            if(bulan_id > 0){
                $("#step2").show();
            }else{
                $("#step2").hide();
            }
                
        });
        
        $("#option_utama").change(function () {
            
            $('.main-table').empty();
            var mapel_kelas = $('#option_utama').val();
            var bulan_id = $("#option_bulan_afektif").val();
            
            if(bulan_id !=0)
            {
                mapel_kelas += '_' + bulan_id;
            }
            if(mapel_kelas != 0 && bulan_id !=0){
                $("#container-hasil").hide();
                $("#container-afektif").show();
                var arr = mapel_kelas.split('_');
                
                $.ajax({
                    url: 'afektif/display_afektif.php',
                    data:'arr='+ arr,
                    type:'POST',
                    success: function(show_afektif){
                        if(!show_afektif.error){
                            $("#show_afektif").html(show_afektif);
                            //$(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
                        }
                    }
                });
            }
            if(mapel_kelas <= 0){
                $("#container-afektif").hide();
            }
        });
       
        
        //ketika user menekan tombol submit
        $("#add-guru-form").submit(function(evt){
            evt.preventDefault();

            var guru_username = $("#guru_username_input").val();
            $.ajax({
                url: "guru/cek_username_guru.php",
                data:'guru_username='+ guru_username,
                dataType : 'json',
                type: "POST",
                success:function(data){
                    //alert(data['status']);
                    if(data['status'] == 1){
                        $("#myModal2").show();
                        $('#guru_username_input').val('');
                    }
                }
            });
            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                $("#hasil_guru").html(php_table_data);
                $("#add-guru-form")[0].reset();
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

<div class="container col-8">
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="step1">
          
          <div id="container-hasil">
            
          </div>
          
          <!-------------------------option bulan afektif----------------------->
            <?php
                include 'includes/db_con.php';
                $sql2 = "SELECT * FROM k_afektif, t_ajaran WHERE k_afektif_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
                $result2 = mysqli_query($conn, $sql2);
                
                $options2 = "<option value= 0>Pilih Bulan</option>";
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    
                    $nama_bulan = "";
                    if($row2['k_afektif_bulan'] == 1){$nama_bulan = "Januari";}
                    elseif($row2['k_afektif_bulan'] == 2){$nama_bulan = "Februari";}
                    elseif($row2['k_afektif_bulan'] == 3){$nama_bulan = "Maret";}
                    elseif($row2['k_afektif_bulan'] == 4){$nama_bulan = "April";}
                    elseif($row2['k_afektif_bulan'] == 5){$nama_bulan = "Mei";}
                    elseif($row2['k_afektif_bulan'] == 6){$nama_bulan = "Juni";}
                    elseif($row2['k_afektif_bulan'] == 7){$nama_bulan = "Juli";}
                    elseif($row2['k_afektif_bulan'] == 8){$nama_bulan = "Agustus";}
                    elseif($row2['k_afektif_bulan'] == 9){$nama_bulan = "September";}
                    elseif($row2['k_afektif_bulan'] == 10){$nama_bulan = "Oktober";}
                    elseif($row2['k_afektif_bulan'] == 11){$nama_bulan = "November";}
                    elseif($row2['k_afektif_bulan'] == 12){$nama_bulan = "Desember";}
                    
                    $options2 .= "<option value={$row2['k_afektif_bulan']}>$nama_bulan</option>";
                }
            ?>
          
            <select class="form-control form-control-sm mb-2" name="option_bulan_afektif" id="option_bulan_afektif">
              <?php echo $options2;?>
            </select>
            
          <div id="show_kriteria">
              
          </div>
            
      </div>
      
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="step2">
          <!-------------------------option mapel/kelas----------------------->
            <?php
                $afektif_guru_id = $_SESSION['guru_id'];
                include 'includes/db_con.php';
                $sql = "SELECT mapel_id, mapel_nama, kelas_id, kelas_nama
                    FROM mapel
                    LEFT JOIN d_mapel
                    ON mapel_id = d_mapel_id_mapel
                    LEFT JOIN kelas
                    ON d_mapel_id_kelas = kelas_id
                    LEFT JOIN t_ajaran
                    ON mapel_t_ajaran_id = t_ajaran_id
                    WHERE d_mapel_id_guru = {$_SESSION['guru_id']} AND t_ajaran_active = 1
                    ORDER BY mapel_nama, kelas_nama";
                $result = mysqli_query($conn, $sql);

                $options = "<option value= 0>Pilih Mapel/Kelas</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options .= "<option value={$row['mapel_id']}_{$row['kelas_id']}>{$row['mapel_nama']} / {$row['kelas_nama']}</option>";
                }
            ?>

            <select class="form-control form-control-sm mb-2" name="option_utama" id="option_utama">
              <?php echo $options;?>
            </select>
      </div>
    
    <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
    
      <!-------------------------tabel afektif----------------------->
      <div style='overflow-x:auto;' class= "p-3 mb-2 bg-light border border-primary rounded" id="container-afektif">
          
        <div id="show_afektif">
            
            
        </div>
        
      </div>
      
      <div style="margin-top:200px;"></div>

    <!-------------------------modal----------------------->
</div>
<?php
   include_once 'footer.php'
?>
