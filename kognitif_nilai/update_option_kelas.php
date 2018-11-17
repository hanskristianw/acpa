<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");
   
    if(!empty($_POST["mapel_id"])) {

        $guru_id = $_SESSION['guru_id'];
        $mapel_id = $_POST["mapel_id"];
        
        
        $query =    "SELECT d_mapel_id_mapel, d_mapel_id_kelas, kelas_nama
                    FROM d_mapel
                    LEFT JOIN kelas
                    ON d_mapel_id_kelas = kelas_id
                    LEFT JOIN t_ajaran
                    ON kelas_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1 AND d_mapel_id_guru = $guru_id AND d_mapel_id_mapel = $mapel_id";

        $query_info = mysqli_query($conn, $query);
        
        $options = "<option value= 0>Pilih Kelas</option>";
         while($row = mysqli_fetch_array($query_info)){
            $kelas_nama = $row['kelas_nama'];

            $options .= "<option value={$row['d_mapel_id_kelas']}>$kelas_nama</option>";
         }
        
        echo "<label>Kelas:</label>"; 
        echo"<select class='form-control form-control-sm mb-2' name='option_kelas' id='option_kelas'>";
            echo $options;
        echo"</select>";
         
         
         
    }
    
?>

<script>
    $(document).ready(function(){
        $("#option_kelas").change(function () {
           
           var kelas_id = $("#option_kelas").val();
           var mapel_id = $("#option_mapel").val();
//           
//           var data;
//           data.push({kelas_id: kelas_id, mapel_id: mapel_id});
//           
//           alert(data);
           
           $.ajax({
                url: 'kognitif_nilai/update_option_topik.php',
                data: {kelas_id: kelas_id, mapel_id: mapel_id},
                type: 'POST',
                success: function(show){
                    if(!show.error){
                        $("#container-option-topik").html(show);
                    }
                }
            });
            
        });
    });
</script>