<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] == 4 || $_SESSION['guru_jabatan'] == 3 || $_SESSION['guru_jabatan'] == 5){
        header("Location: index.php");
    }
    include_once 'header.php';
?>

<script>
    var isPaused = false;        
    $(document).ready(function(){
        $("#kotak").hide();
        $(".checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#option_mapel").change(function () {

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
        $("#add-rapot-form").submit(function(evt){
            evt.preventDefault();

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
            
        });
        
    });
</script>



<div class="container col-6">
      <!-------------------------form cetak rapot----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
      <form method="POST" id="add-rapot-form" action="rapot_waka/display_rapot.php">
          <div class="form-group">
            <h4 class="mb-4"><u>Preview Rapot</u></h4>
            
            <?php
                $guru_id = $_SESSION['guru_id'];
                
                include 'includes/db_con.php';
                $sql3 = "SELECT *
                            FROM kelas
                            LEFT JOIN siswa
                            ON kelas_id = siswa_id_kelas
                            LEFT JOIN t_ajaran
                            ON kelas_t_ajaran_id = t_ajaran_id
                            WHERE t_ajaran_active = 1 AND kelas_wali_guru_id = $guru_id";
                $result3 = mysqli_query($conn, $sql3);
                
                echo '<input type="checkbox" id="checkAll" class="checkAll"> <b>PILIH SEMUA</b><hr/>';
                while ($row3 = mysqli_fetch_assoc($result3)) {
                    $kelas_id_temp = $row3['kelas_id'];
                    echo"<input type='checkbox' name='check_siswa_id[]' value={$row3['siswa_id']}> {$row3['siswa_nama_depan']} {$row3['siswa_nama_belakang']} <br>";
                }

                echo "<input type='hidden' value=$kelas_id_temp name='option_kelas'>";
            ?>
              
            <input type="submit" name="submit_siswa" class="btn btn-primary mt-3" value="Preview Rapot">
          </div>
      </form>
      </div >
      
      <div id='loadingDiv'><p style='text-align:center'><img src='pic/ajax-loader.gif' alt='please wait'></p></div>
      
      <!-------------------------tabel rapot----------------------->
        <div class= "p-3 mb-2 bg-light border border-primary rounded" id="kotak">
            <div id="show_rapot">

            </div>
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