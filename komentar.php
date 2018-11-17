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
        $("#komentar_form").hide();
        $("#container-jenjang").hide();
        $("#notif_box").hide();
        $("#option_siswa").change(function () {
            $("#notif_box").hide();
            var siswa_id = $("#option_siswa").val();
            
            $.ajax({
                url: 'rapot/komentar_form.php',
                data:'siswa_id='+ siswa_id,
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#komentar_form").show();
                        $("#komentar_form").html(show);
                    }
                }
            });
            
        });
        
        $("#print_rekap").click(function(){
            //$("#print_area").printMe();
//            $("#print_area").printMe({ "path": ["http://localhost/acpa/CSS/customCSS.css"] });
            $('#print_area').printThis({
                importCSS: false,
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
        $("#add-komen-form").submit(function(evt){
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
                            $("#notif_box").show();
                            $("#notif_box").html(show);
                            $("#komentar_form").hide();
                            $("#add-komen-form")[0].reset();
                        }
                    }
                });
            }else{
                alert("Pilih Siswa");
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
        
        $options3 = "<option value= 0>Pilih Siswa</option>";
        while ($row3 = mysqli_fetch_assoc($result3)) {
            $var_kelas_id = $row3['kelas_id'];
            $siswa_nama = $row3['siswa_nama_depan'] .' '. $row3['siswa_nama_belakang'] .' (No Induk: '.$row3['siswa_no_induk'].')';

            $options3 .= "<option value={$row3['siswa_id']}>$siswa_nama</option>";
        }
    ?>
    
    
      <!-------------------------form input komentar----------------------->
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
      <div id="notif_box"></div>
          
      <form method="POST" id="add-komen-form" action="rapot/komentar_update.php">
          <div class="form-group">
            <h4 class="mb-4"><u>Input Komentar dan Absensi</u></h4>
            
            <input id="kelas_id" name="kelas_id" type="hidden" value=<?php echo $var_kelas_id;?>>
            
            <select class="form-control form-control-sm mb-2" name="option_siswa" id="option_siswa">
                  <?php echo $options3;?>
            </select>
              
            <div id="komentar_form"></div>
            
            <input type="submit" name="submit_siswa" class="btn btn-primary" value="Update Komentar & Absensi">
          </div>
      </form>
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