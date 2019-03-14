<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: index.php");
    }
  include_once 'header.php'
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        
        $("#kotak_lanjut").hide();
        
        
        //ketika user menekan tombol submit
        $("#hapus-siswa-form").submit(function(evt){
            evt.preventDefault();

            var option_siswa = $("#option_siswa").val();
            var option_mapel = $("#option_mapel").val();
            
            if (option_siswa != 0 && option_mapel != 0)
            {
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    data: $(this).serialize(),
                    type: "POST",
                    success:function(data){
                        if(!data.error){
                            $("#alert_container").show();
                            $("#alert_container").html(data);
                            $("#hapus-siswa-form")[0].reset();
                        }
                    }
                });
            }
            else{
                alert("Pilih mapel dan siswa terlebih dahulu");
            }
        });
        

        $("#option_mapel").change(function () {

            var mapel_id = $("#option_mapel").val();
            if(mapel_id > 0){
                $.ajax({
                    url: 'mapel_khusus_daftar/update_option_siswa.php',
                    data:'mapel_id='+ mapel_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#container-option-daftarsiswa").html(show);
                        }
                    }
                });
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

<?php

    include ("includes/db_con.php");

    $query = "SELECT * FROM t_ajaran where t_ajaran_active = 1";
    $query_t_ajaran_info = mysqli_query($conn, $query);

    if(!$query_t_ajaran_info){
        die("QUERY FAILED".mysqli_error($conn));
    }
    
    //tampilkan tabel pada container
    while($row = mysqli_fetch_array($query_t_ajaran_info)){
        $semester = $row['t_ajaran_semester'];
        $t_ajaran_active = $row['t_ajaran_id'];
    }
?>


<div class="container">
    <div id="pesan"></div>
      <!-------------------------form kriteria----------------------->
    <div class= "p-3 mb-2 bg-light border border-primary rounded">
        <div class="alert alert-warning alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>&times;</span>
            </button>
            <strong>Info:</strong> Pilih kelas dan nama mapel khusus
        </div>

        <div id="alert_container"></div>

        <form method="POST" id="hapus-siswa-form" action="mapel_khusus_daftar/hapus_siswa.php">
            <div class="form-group">
                <h4 class="mb-4"><u>Hapus Siswa Dari Mapel Khusus</u></h4>

                <?php
                    include 'includes/db_con.php';
                    $sql = "SELECT mapel_k_m_id, mapel_k_m_nama
                            FROM mapel_khusus_master
                            LEFT JOIN t_ajaran
                            ON mapel_k_m_t_ajaran_id = t_ajaran_id
                            WHERE t_ajaran_active = 1";
                    $result = mysqli_query($conn, $sql);

                    $options = "<option value=0>Pilih Mapel Khusus</option>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $options .= "<option value={$row['mapel_k_m_id']}>{$row['mapel_k_m_nama']}</option>";
                    }
                ?>
                
                <select class="form-control form-control-sm" name="option_mapel" id="option_mapel">
                    <?php echo $options;?>
                </select>

                <div id="container-option-daftarsiswa"></div>

                <input type="submit" name="submit_mapel_khusus" class="btn btn-danger mt-3" value="Hapus">
            </div>
        </form>
    </div >
    
    <!-------------------------tabel ssp----------------------->
    
    <div class= "p-3 mb-2 bg-light" id="kotak_lanjut">
        <form method="POST" id="add-siswa-lanjut-form" action="mapel_khusus_daftar/add_siswa_mapel_khusus.php">
            <div id="siswa_checkbox">
            
            </div>
            <input type="submit" name="submit_mapel_khusus" class="btn btn-primary mt-3" value="Daftarkan Siswa">
        </form>
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
                Data Berhasil Ditambahkan.
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" id="close_modal2">Close</button>
            </div>
          </div>
        </div>
    </div>
    
    <!-------------------------modal----------------------->

<?php
   include_once 'footer.php'
?>