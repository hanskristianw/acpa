<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] == 4 || $_SESSION['guru_jabatan'] == 3){
        header("Location: index.php");
    }
    include_once 'header.php'
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#container-jenjang").hide();
        $("#kotak").hide();
        $("#form2_ajax").hide();
        
        $("#print_rekap").click(function(){
            //$("#print_area").printMe();
//            $("#print_area").printMe({ "path": ["http://localhost/acpa/CSS/customCSS.css"] });
            $('#print_area').printThis({
                printDelay: 2000,
                importCSS: true,
                importStyle: true,//thrown in for extra measure
                //loadCSS: "http://cpasmp.nationstaracademy.sch.id/CSS/customCSS_preview.css"
                loadCSS: "http://192.168.16.253/cpasma/CSS/customCSS_preview.css"
            });
//            var myStyle = '<link rel="stylesheet" media="print" href="/CSS/customCSS"/>';
//            
//            w=window.open(null, 'print_area', 'scrollbars=yes');        
//            w.document.write(myStyle + jQuery('#print_area').html());
//            w.document.close();
//            w.print();
        }); 
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });

        $("#option_kelas").change(function () {

            var kelas_id = $("#option_kelas").val();
            
            $.ajax({
                url: 'rapot_waka/form2_ajax.php',
                data:'kelas_id='+ kelas_id,
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#form2_ajax").show();
                        $("#form2_ajax").html(show);
                    }
                }
            });
            
        });
        
        //ketika user menekan tombol submit
        $("#add-rapot-form").submit(function(evt){
            evt.preventDefault();

            var kelas_id = $("#option_kelas").val();
            
            if(kelas_id > 0){
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#show_rapot").html(show);
                            $("#kotak").hide();
                            $("#kotak").show();
                            $("#container-temp").hide();
                        }
                    }
                });
            }else{
                alert("Pilih Kelas");
            }
        });
        
    });
</script>



<div class="container col-6">
    <div id="container-temp">

    </div>
    
    <div id="container-temp2">

    </div>
    
    
    
    
    <?php
        
        include 'includes/db_con.php';
        $sql3 = "SELECT *
                    FROM kelas
                    LEFT JOIN t_ajaran
                    ON kelas_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1";
        $result3 = mysqli_query($conn, $sql3);
        
        $options3 = "<option value= 0>Pilih Kelas</option>";
        while ($row3 = mysqli_fetch_assoc($result3)) {
            $options3 .= "<option value={$row3['kelas_id']}>{$row3['kelas_nama']}</option>";
        }
    ?>
    
    
      <!-------------------------form cetak rapot----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          
      <form method="POST" id="add-rapot-form" action="rapot_waka/display_rapot.php">
          <div class="form-group">
            <h4 class="mb-4"><u>Cetak Rapot</u></h4>
            
            <select class="form-control form-control-sm mb-2" name="option_kelas" id="option_kelas">
                  <?php echo $options3;?>
            </select>
            
            <div id="form2_ajax">

            </div>
            
            <input type="submit" name="submit_siswa" class="btn btn-primary mt-3" value="Preview Rapot">
          </div>
      </form>
      </div>
      <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      <!-------------------------tabel rapot----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak">
        <div id="print_area">
            <div id="show_rapot">

            </div>
        </div>  
        <input type="button" name="print_rekap" id="print_rekap" class="btn btn-success mt-3" value="Print">
      </div >
      
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