<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
?>

<?php

    include ("../includes/db_con.php");
   
    if(!empty($_POST["kelas_id"])) {

        $kelas_id = $_POST["kelas_id"];
        
        $query =    "SELECT ce_id, ce_aspek
                    FROM ce
                    LEFT JOIN t_ajaran
                    ON ce_t_ajaran_id = t_ajaran_id
                    WHERE t_ajaran_active = 1 AND ce_jenjang_id IN
                        (SELECT kelas_jenjang_id 
                         FROM kelas
                         WHERE kelas_id = $kelas_id)";

        $query_info = mysqli_query($conn, $query);
        
        $options = "<option value= 0>Pilih Topik</option>";
         while($row = mysqli_fetch_array($query_info)){
            $options .= "<option value={$row['ce_id']}>{$row['ce_aspek']}</option>";
         }
         
        echo"<select class='form-control form-control-sm mb-2' name='option_aspek' id='option_aspek'>";
            echo $options;
        echo"</select>";
         
    }
    
?>

<script>
    $(document).ready(function(){
        $("#option_aspek").change(function () {
            var ce_id = $("#option_aspek").val();
            if(ce_id >0){
                $("#containerDetailAspek").show();
                $.ajax({
                    url: 'detail_ce/update_combo_indikator.php',
                    data: 'ce_id='+ ce_id,
                    type:'POST',
                    success: function(show){
                        if(!show.error){
                            $("#containerDetailAspek").show();
                            $("#containerDetailAspek").html(show);
                        }
                    }
                });
            }
            else{
                $("#containerDetailAspek").hide();
            }
        });
    });
</script>