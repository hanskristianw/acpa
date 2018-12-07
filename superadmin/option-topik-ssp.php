<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 6){
        header("Location: index.php");
    }
?>

<?php

    if(isset($_POST['ssp_id'])){
        
        
        include ("../includes/db_con.php");
        
        $ssp_id = $_POST['ssp_id'];
        
        if($ssp_id > 0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu
            $ssp_id = $_POST['ssp_id'];

            $query2 =   "SELECT d_ssp_id, d_ssp_kriteria
                        FROM d_ssp
                        WHERE d_ssp_ssp_id = $ssp_id";

            $query_info2 = mysqli_query($conn, $query2);

            $count = 0;

            $options = "<option value= 0>Pilih Rubrik</option>";
             while($row2 = mysqli_fetch_array($query_info2)){
                $count++;
                $topik_nama = $row2['d_ssp_kriteria'];

                $options .= "<option value={$row2['d_ssp_id']}>$topik_nama</option>";
             }

             if($count>0){
                echo "<label>Rubrik:</label>"; 
                echo "<select class='form-control form-control-sm mb-2' name='option_topik' id='option_topik'>";
                    echo $options;
                echo "</select>";
                echo '<input type="submit" name="submit_topik" class="btn btn-primary mt-3" value="Cari">';
             }
             elseif ($count<=0){
                 echo "<label>Rubrik:</label>"; 
                 echo '<p class="bg-warning">Topik belum ada silahkan set topik pada menu master</p>';
             }
        }
    }
?>

<script>
    $(document).ready(function(){
        
    });
</script>