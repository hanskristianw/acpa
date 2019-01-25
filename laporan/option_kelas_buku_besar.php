<?php
    if($_POST['t_ajaran_id']){
        $t_ajaran_id = $_POST['t_ajaran_id'];

        include '../includes/db_con.php';
        $sql2 = "SELECT GROUP_CONCAT(kelas_id ORDER BY t_ajaran_id) as kelas_id, UPPER(kelas_nama) as kelas_nama 
                FROM kelas 
                LEFT JOIN t_ajaran 
                ON kelas_t_ajaran_id = t_ajaran_id 
                WHERE t_ajaran_id IN (".$t_ajaran_id.") 
                GROUP BY kelas_nama
                ORDER BY t_ajaran_id";
        $result2 = mysqli_query($conn, $sql2);

        $options2 = "<option value= 0>Pilih Kelas</option>";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $options2 .= "<option value={$row2['kelas_id']}>{$row2['kelas_nama']}</option>";
        }
        echo '<select class="form-control form-control-sm mb-2" name="option_kelas" id="option_kelas">';
        echo $options2;
        echo '</select>';
    }

?>

<script>
     
    $(document).ready(function(){

        $("#option_kelas").change(function () {

            var kelas_id = $("#option_kelas").val();
            if(kelas_id != 0){
                //alert(kelas_id);
                $.ajax({
                    url: 'laporan/option_siswa_buku_besar.php',
                    data:'kelas_id='+ kelas_id,
                    type: 'POST',
                    success: function(show){
                        if(!show.error){
                            $("#container-option-siswa").html(show);
                        }
                    }
                });
            }
        });

    });
</script>