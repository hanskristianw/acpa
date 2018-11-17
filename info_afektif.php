<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 4 && $_SESSION['guru_jabatan'] != 5){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#kotak_utama").hide();
        $("#option_kelas").hide();
        $("#show_detail_kriteria").hide();
        var cek_pil = 0;
        
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });
        
        $("#print_rekap").click(function(){
            $('#print_area').printThis({
                importCSS: false,
                printDelay: 2000,
                loadCSS: "http://cpasmp.nationstaracademy.sch.id/CSS/customCSS.css",
                importStyle: false//thrown in for extra measure
            });
        }); 
        
        //ketika user menekan tombol submit
        $("#add-kriteria-form").submit(function(evt){
            $("#option_kelas").hide();
            evt.preventDefault();

            var bulan_id = $("#option_bulan_afektif").val();
            var kriteria = $("#kriteria_bulan_option").val();
            
            if (bulan_id != 0 && kriteria != 0)
            {

                var postData = $(this).serialize();
                var url = $(this).attr('action');
                var url2 = "afektif/display_info_afektif2.php";

                
                    $.post(url,postData, function(php_table_data){
                        $("#show_kriteria").html(php_table_data);
                        $("#add-kriteria-form")[0].reset();
                        $("#kotak_utama").show();
                    });
                
                if(cek_pil == 1){
                    $.post(url2,postData, function(php_table_data){
                        $("#show_detail_kriteria").show();
                        $("#show_detail_kriteria").html(php_table_data);
                    });
                }
                else{
                    $("#show_detail_kriteria").hide();
                }
            }
            else{
                
                $("#kriteria_bulan_option").val(0).change();
                $("#myModal").show();
            }
        });
        
        $("#kriteria_bulan_option").change(function () {
            cek_pil = 0;
            var pilihan_kriteria = $("#kriteria_bulan_option").val();
            
            if(pilihan_kriteria == 2){
                $("#option_kelas").show();
                cek_pil = 1;
                
            }else if(pilihan_kriteria == 3){
                $("#option_kelas").hide();
                
            }else{
                $("#option_kelas").hide();
            }
        });
        
        $('#close_modal').click(function(){
            $("#myModal").hide();
        });
        $('#close_modal2').click(function(){
            $("#myModal").hide();
        });
        
        $('#close_modal3').click(function(){
            $("#myModal2").hide();
        });
        $('#close_modal4').click(function(){
            $("#myModal2").hide();
        });
    });
</script>

<div class="container col-6">
      
      <!-------------------------form kriteria----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
        <div class="alert alert-warning alert-dismissible fade show">
          <button class="close" data-dismiss="alert" type="button">
              <span>&times;</span>
          </button>
          <strong>Info:</strong> Bulan yang tampil hanya pada tahun ajaran yang aktif dan yang telah memiliki kriteria
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
      
        
        <?php
            include 'includes/db_con.php';
            $sql3 = "SELECT * FROM kelas, t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
            $result3 = mysqli_query($conn, $sql3);

            $options3 = "";
            while ($row3 = mysqli_fetch_assoc($result3)) {

                $kelas_nama = $row3['kelas_nama'];

                $options3 .= "<option value={$row3['kelas_id']}>$kelas_nama</option>";
            }
        ?>
        
        <form method="POST" id="add-kriteria-form" action="afektif/display_info_afektif.php">
           
            <div class="form-group"> 
                <select class="form-control form-control-sm mb-2" name="option_bulan_afektif" id="option_bulan_afektif">
                    <?php echo $options2;?>
                </select>   

                <select class="form-control form-control-sm mb-2" name="kriteria_bulan_option" id="kriteria_bulan_option">
                    <option value = 0>Pilih Jenis Laporan</option>
<!--                    <option value = 1>Murid dengan nilai <= 60</option>-->
                    <option value = 2>Rekap Nilai Afektif</option>
                    <option value = 3>Rekap Pengumpulan nilai guru</option>
                </select>

                <select class="form-control form-control-sm mb-2" name="option_kelas" id="option_kelas">
                    <?php echo $options3;?>
                </select> 
                <input type="submit" name="submit_kriteria" class="btn btn-primary" value="Tampilkan">
            </div>
        </form>
    </div>
      <!-------------------------end of form kriteria----------------------->
      <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      <!-------------------------tabel kriteria----------------------->
      <div class= "p-3 mb-2"  id="kotak_utama">
          
          <div id="print_area">
            <div id="show_detail_kriteria" class= "mb-2">

            </div>
          
            <table style="overflow-x:auto;" class="table table-sm table-responsive table-striped table-bordered mt-3" id="show_kriteria">
            
            </table>
          </div>
          <input type="button" name="print_rekap" id="print_rekap" class="btn btn-success" value="Print">
      </div >
    <!-------------------------end of tabel kriteria----------------------->
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
            Pilih bulan dan kriteria.
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
          <h5 class="modal-title">Detail Nilai</h5>
          <button class="close" id="close_modal3">&times;</button>
        </div>
        <div class="modal-body">
            <div id='myAlertbox'></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" id="close_modal4">Close</button>
        </div>
      </div>
    </div>
</div>
<!-------------------------modal----------------------->
<?php
   include_once 'footer.php'
?>