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

        $("#option_t_ajaran").change(function () {

            var t_ajaran_id = $("#option_t_ajaran").val();
            if(t_ajaran_id != 0){
                $.ajax({
                    url: 'laporan/option_kelas_buku_besar.php',
                    data:'t_ajaran_id='+ t_ajaran_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#container-option-kelas").html(show);
                        }
                    }
                });
            }
        });

        //ketika user menekan tombol submit
        $("#buku-induk-form").submit(function(evt){
            evt.preventDefault();

            var option_siswa = $("#option_siswa").val();
            
            if (option_siswa != 0)
            {
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: "POST",
                    success:function(data){
                        if(!data.error){
                            $("#laporan_box").html(data);
                            $("#buku-induk-form")[0].reset();
                        }
                    }
                });
            }
            else{
                alert("Pilih siswa terlebih dahulu");
            }
        });
        
    });
</script>

<div class="container">
      
    <?php
        include 'includes/db_con.php';
        $sql2 = "SELECT GROUP_CONCAT(t_ajaran_id ORDER BY t_ajaran_id) as t_ajaran_id, t_ajaran_nama
                FROM t_ajaran
                GROUP BY t_ajaran_nama
                ORDER BY t_ajaran_id";
        $result2 = mysqli_query($conn, $sql2);

        $options2 = "<option value= 0>Pilih Tahun Ajaran</option>";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $options2 .= "<option value={$row2['t_ajaran_id']}>{$row2['t_ajaran_nama']}</option>";
        }
    ?>

    <!-------------------------form kriteria----------------------->
    <div class= "p-3 mb-2 bg-light border border-primary rounded">
        <div class="alert alert-warning alert-dismissible fade show">
          <button class="close" data-dismiss="alert" type="button">
              <span>&times;</span>
          </button>
          <strong>Info:</strong> Pilih Tahun Ajaran, Kelas, dan Siswa
        </div>
        <form method="POST" id="buku-induk-form" action="laporan/buku_induk_lanjut.php">
           
            <div class="form-group"> 

                <select class="form-control form-control-sm mb-2" name="option_t_ajaran" id="option_t_ajaran">
                    <?php echo $options2;?>
                </select>
                
                <div id="container-option-kelas">
                
                </div>

                <div id="container-option-siswa">
                
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