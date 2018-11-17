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
    $(document).ready(function(){
       //interval
        setInterval(function(){
                updateTable();
        },1000);
       
       //refresh table
       function updateTable(){

            $.ajax({
                url: 't_ajaran/display_t_ajaran.php',
                type: 'POST',
                success: function(show_t_ajaran){
                    if(!show_t_ajaran.error){
                        $("#show_t_ajaran").html(show_t_ajaran);
                        
                    }
                }
            });
       }
        
        $("#add-t-ajaran-form").submit(function(evt){
            evt.preventDefault();

            var postData = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url,postData, function(php_table_data){
                //$("#hasil_kelas").html(php_table_data);
                $("#add-t-ajaran-form")[0].reset();
            });
            
        });
    });
</script>

<div class="container col-4 mt-4 p-3 mb-2 bg-light border border-primary rounded">
      <!-------------------------tabel t_ajaran----------------------->
      <div class="alert alert-info mt-3 mb-4">
        <strong>Info: </strong> Tekan aktif untuk mengaktifkan tahun ajaran.
      </div>
      
      <div id="show_t_ajaran">
          
      </div>
      
      <form method="POST" id="add-t-ajaran-form" action="t_ajaran/proses_t_ajaran.php">
          <div class="form-group mt-4">
              
              <label>Pilih Tahun Ajaran:</label>
              <select class="form-control form-control-sm mb-3" name="t_ajaran_nama_option">
                <option>2017/2018</option>
                <option>2018/2019</option>
                <option>2019/2020</option>
                <option>2020/2021</option>
                <option>2021/2022</option>
              </select>
              
              <label>Semester:</label>
              <select class="form-control form-control-sm mb-3" name="t_ajaran_semester_option">
                  <option value = 1>Ganjil</option>
                  <option value = 2>Genap</option>
              </select>
              <input type="submit" name="submit_t_ajaran" class="btn btn-primary mt-3" value="SET AKTIF">
          </div>
      </form>
</div>
   
<?php
   include_once 'footer.php'
?>