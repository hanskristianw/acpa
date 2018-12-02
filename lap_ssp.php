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
        
        //ketika user menekan tombol submit
        $("#lapor-form").submit(function(evt){
            evt.preventDefault();

            var option_search_ssp = $("#option_search_ssp").val();
            
            if (option_search_ssp != 0)
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
                alert("Pilih jenis laporan");
            }
        });
    });
</script>

<div class="container col-6">
      
    <?php
        $guru_id = $_SESSION['guru_id'];
        
        include 'includes/db_con.php';
        $sql3 = "SELECT * 
                    FROM ssp
                    LEFT JOIN t_ajaran
                    ON ssp_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1";
        $result3 = mysqli_query($conn, $sql3);

        $options3 = "<option value= 0>Pilih Laporan SSP</option>";
        $options3 .= "<option value=-1>SISWA YANG TIDAK TERDAFTAR</option>";
        while ($row3 = mysqli_fetch_assoc($result3)) {

            $ssp_nama = $row3['ssp_nama'];

            $options3 .= "<option value={$row3['ssp_id']}>$ssp_nama</option>";
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
        <form method="POST" id="lapor-form" action="laporan_ssp/laporan_ssp_lanjut.php">
           
            <div class="form-group"> 
              <select class="form-control form-control-sm mb-2" name="option_search_ssp" id="option_search_ssp">
                <?php echo $options3;?>
              </select>
                
              <input type="submit" name="submit_kriteria" class="btn btn-primary" value="Cari">
            </div>
        </form>
    </div>
    
    <div id = "laporan_box">
        
    </div>
    
      
      <div style="margin-top:200px;"></div>
</div>

<!-------------------------modal----------------------->
<?php
   include_once 'footer.php'
?>