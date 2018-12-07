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
        $("#topikssp").hide();
        
        //ketika user menekan tombol submit
        $("#ssp-susul-form").submit(function(evt){
            evt.preventDefault();

            var siswa_id = $("#option_siswa").val();
            var ssp_id = $("#option_ssp").val();
            
            if (siswa_id != 0 && ssp_id !=0)
            {
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: "POST",
                    success:function(data){
                        if(!data.error){
                            $("#hasil").html(data);
                            $("#ssp-susul-form")[0].reset();
                            $("#topikssp").hide();
                        }
                    }
                });
            }
            else{
                alert("Pilihan harus benar");
            }
        });

        $("#option_ssp").change(function () {
            var ssp_id = $("#option_ssp").val();
            
            if(ssp_id >0){
                $("#topikssp").show();
                $.ajax({
                    url: 'superadmin/rubrik_ssp_susul.php',
                    data: 'ssp_id='+ ssp_id,
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            $("#topikssp").html(show);
                        }
                    }
                });
            }else{
                $("#topikssp").hide();
            }
        });
    });
</script>

<div class="container col-6">
      
    <?php
        $guru_id = $_SESSION['guru_id'];
        
        include 'includes/db_con.php';
        $sql3 = "SELECT siswa_id, siswa_nama_depan, siswa_nama_belakang, kelas_nama
                FROM siswa
                LEFT JOIN kelas
                ON siswa_id_kelas = kelas_id
                LEFT JOIN t_ajaran
                ON kelas_t_ajaran_id = t_ajaran_id
                WHERE t_ajaran_active = 1 AND siswa_id NOT IN (SELECT ssp_daftar_siswa_id as siswa_id from ssp_daftar)
                ORDER BY siswa_nama_depan";
        $result3 = mysqli_query($conn, $sql3);

        $options3 = "<option value= 0>Pilih Siswa Yang Tidak Terdaftar</option>";
        while ($row3 = mysqli_fetch_assoc($result3)) {

            $siswa_nama = $row3['siswa_nama_depan']." ".$row3['siswa_nama_belakang'];

            $options3 .= "<option value={$row3['siswa_id']}>$siswa_nama</option>";
        }

        $sql4 = "SELECT ssp_id, ssp_nama
                FROM ssp
                LEFT JOIN t_ajaran
                ON ssp_t_ajaran_id = t_ajaran_id
                WHERE t_ajaran_active = 1
                ORDER BY ssp_nama";
        $result4 = mysqli_query($conn, $sql4);

        $options4 = "<option value= 0>Pilih SSP</option>";
        while ($row4 = mysqli_fetch_assoc($result4)) {

            $ssp_nama = $row4['ssp_nama'];

            $options4 .= "<option value={$row4['ssp_id']}>$ssp_nama</option>";
        }
    ?>
    
    <!-------------------------form kriteria----------------------->
    <div class= "p-3 mb-2 bg-light border border-primary rounded">
        <div class="alert alert-warning alert-dismissible fade show">
          <button class="close" data-dismiss="alert" type="button">
              <span>&times;</span>
          </button>
          <strong>Info:</strong> Pilih Siswa dan SSP
        </div>
        <div id = "hasil">
        
        </div>
        <form method="POST" id="ssp-susul-form" action="superadmin/proses_ssp_susul.php">
           
            <div class="form-group"> 
                <select class="form-control form-control-sm mb-2" name="option_siswa" id="option_siswa">
                    <?php echo $options3;?>
                </select>
                
                <select class="form-control form-control-sm mb-2" name="option_ssp" id="option_ssp">
                    <?php echo $options4;?>
                </select>

                <div id="topikssp"></div>

            </div>
        </form>
    </div>
    
    
    
      
      <div style="margin-top:200px;"></div>
</div>

<!-------------------------modal----------------------->
<?php
   include_once 'footer.php'
?>