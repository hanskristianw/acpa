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
        $("#option_kelas").hide();
        $("#container-topik").hide();
        $("#refresh_tabel").hide();
        setInterval(function(){
            if(!isPaused)
                updateTable();
        },1000);
        
        function updateTable(){
            var mapel_id = $("#option_search_mapel").val();
            //alert(kelas_id);
            $.ajax({
                url: 'kognitif/display_kognitif.php',
                data:'mapel_id='+ mapel_id,
                type: 'POST',
                success: function(show_topik){
                    if(!show_topik.error){
                        $("#show_topik").html(show_topik);
                    }
                }
            });
       }
        
        //ketika user menekan tombol submit
        $("#add-topik-form").submit(function(evt){
            $("#option_kelas").hide();
            evt.preventDefault();

            var option_mapel = $("#option_mapel").val();
            
            if (option_mapel != 0)
            {
                var postData = $(this).serialize();
                var url = $(this).attr('action');

                $.post(url,postData, function(php_table_data){
                    //$("#show_kriteria").html(php_table_data);
                    $("#add-topik-form")[0].reset();
                    //$("#kotak_utama").show();
                    $("#myModal2").show();
                    updateTable();
                });
            }
            else{
                //$("#kriteria_bulan_option").val(0).change();
                $("#myModal").show();
            }
        });
        
        $("#option_search_mapel").change(function () {

            var mapel_id = $("#option_search_mapel").val();
            if(mapel_id > 0){
                $("#refresh_tabel").show();
                updateTable();
            }else{
                $("#refresh_tabel").hide();
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
      
    <?php
        $guru_id = $_SESSION['guru_id'];
        
        include 'includes/db_con.php';
        $sql3 = "SELECT DISTINCT d_mapel_id_mapel, mapel_nama
                    FROM d_mapel
                    LEFT JOIN mapel
                    ON d_mapel_id_mapel = mapel_id
                    LEFT JOIN t_ajaran
                    ON mapel_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1 AND d_mapel_id_guru = $guru_id";
        $result3 = mysqli_query($conn, $sql3);

        $options3 = "<option value= 0>Pilih Mapel</option>";
        while ($row3 = mysqli_fetch_assoc($result3)) {

            $mapel_nama = $row3['mapel_nama'];

            $options3 .= "<option value={$row3['d_mapel_id_mapel']}>$mapel_nama</option>";
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
        <form method="POST" id="add-topik-form" action="kognitif/add_topik_kognitif.php">
           
            <div class="form-group"> 
              <h4 class="mb-4"><u>TAMBAH TOPIK KOGNITIF</u></h4>
              <label>Mapel:</label>
              <select class="form-control form-control-sm mb-2" name="option_mapel" id="option_mapel">
                    <?php echo $options3;?>
              </select>
              <label>Nama Topik:</label>
              <input type="text" name="topik_nama_input" placeholder="Masukkan topik" class="form-control form-control-sm mb-2" required>
              
              <!--Memasukkan piihan jenjang-->
              <?php
                include 'includes/db_con.php';
                $sql = "SELECT * FROM jenjang";
                $result = mysqli_query($conn, $sql);

                $options2 = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $options2 .= "<option value={$row['jenjang_id']}>{$row['jenjang_nama']}</option>";
                }
              ?>
              <label>Topik berada pada jenjang:</label>
              <select class="form-control form-control-sm mb-2" name="jenjang_id_option">
                <?php echo $options2;?>
              </select>
              
              <label>Urutan topik PADA JENJANG diatas:</label>
              <input type="number" name="topik_urutan" placeholder="Masukkan urutan topik (misal: 1)" class="form-control form-control-sm mb-2" required>
              
              <input type="submit" name="submit_kriteria" class="btn btn-primary" value="Insert">
            </div>
        </form>
    </div>
    
      
      <div class= "p-3 mb-2 bg-light border border-primary rounded">
          
        <div id="container-topik" class= "p-3 mb-2 bg-light border border-primary rounded">

        </div>
      <!-------------------------input search mapel----------------->
      <h4 class="mb-4"><u>DAFTAR TOPIK</u></h4>
      
          <select class="form-control form-control-sm mb-2" name="option_search_mapel" id="option_search_mapel">
                <?php echo $options3;?>
          </select>
      
        <div class="alert alert-warning alert-dismissible fade show">
          <button class="close" data-dismiss="alert" type="button">
              <span>&times;</span>
          </button>
          <strong>Info:</strong> Klik sekali pada nama topik yang ingin dirubah
        </div>
      
          <table class="table table-sm table-striped mb-5">
            <thead>
                <tr>
                    <th>Urutan</th>
                    <th>Kelas</th>
                    <th>Nama Topik</th>
                </tr>
            </thead>
            <tbody id="show_topik">

            </tbody>
          </table>
      </div>
      
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
            Nama Topik dan Mapel Tidak boleh kosong.
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
          <button class="close" id="close_modal3">&times;</button>
        </div>
        <div class="modal-body">
            Data berhasil ditambahkan
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