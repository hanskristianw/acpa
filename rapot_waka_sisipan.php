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
        $("#pil1").hide();
        $("#pil2").hide();
        $("#form2_ajax").hide();
        
        $("#option_pil").change(function () {

            var option_pil = $("#option_pil").val();
            
            if(option_pil == 1){
                $("#pil1").show();
                $("#pil2").hide();
            }else if(option_pil == 2){
                $("#pil1").hide();
                $("#pil2").show();
            }else{
                $("#pil1").hide();
                $("#pil2").hide();
            }
            
        });
        
        $("#option_kelas").change(function () {

            var kelas_id = $("#option_kelas").val();
            
            $.ajax({
                url: 'rapot_waka/update_option_siswa.php',
                data:'kelas_id='+ kelas_id,
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#container-option-siswa").html(show);
                    }
                }
            });
            
        });
        
        $("#option_kelas2").change(function () {

            var kelas_id = $("#option_kelas2").val();
            
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
        
        $("#print_rekap").click(function(){
            //$("#print_area").printMe();
//            $("#print_area").printMe({ "path": ["http://localhost/acpa/CSS/customCSS.css"] });
            $('#print_area').printThis({
                printDelay: 2000,
                importCSS: true,
                importStyle: true,//thrown in for extra measure
                loadCSS: "http://localhost/acpa/CSS/customCSS_preview.css"
            });
//            var myStyle = '<link rel="stylesheet" media="print" href="/CSS/customCSS"/>';
//            
//            w=window.open(null, 'print_area', 'scrollbars=yes');        
//            w.document.write(myStyle + jQuery('#print_area').html());
//            w.document.close();
//            w.print();
        }); 
        
        //ketika user menekan tombol submit
        $("#add-rapot-form").submit(function(evt){
            evt.preventDefault();

            var siswa_id = $("#option_siswa").val();
            
            if(siswa_id > 0){
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
                alert("Pilih Kelas dan Siswa");
            }
        });
        
        $("#add-rapot-form2").submit(function(evt){
            evt.preventDefault();

            var kelas_id = $("#option_kelas2").val();
            
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
                            $("#add-rapot-form2")[0].reset();
                            $("#form2_ajax").hide();
                        }
                    }
                });
            }else{
                alert("Pilih Kelas");
            }
        });
        
    });
</script>



<div class="container">
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
          <h4 class="mb-4"><u>Cetak Rapor</u></h4>
          <select class="form-control form-control-sm mb-2" name="option_pil" id="option_pil">
              <option value= 0>Pilih salah satu</option>
              <option value= 1>Cetak Perkelas</option>
          </select>
          
          <div id="pil1">
            <form method="POST" id="add-rapot-form2" action="rapot_waka/display_rapot_sisipan2.php">
                <div class="form-group">
                  
                    <select class="form-control form-control-sm mb-2" name="option_kelas2" id="option_kelas2">
                          <?php echo $options3;?>
                    </select>
                    
                    <div id="form2_ajax">
                        
                    </div>
                  <input type="submit" name="submit_siswa" class="btn btn-primary mt-3" value="Preview Rapor">
                </div>
            </form>
          </div>  
      </div>
      
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


<?php
   include_once 'footer.php'
?>