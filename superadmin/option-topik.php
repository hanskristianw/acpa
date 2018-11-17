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

    if(isset($_POST['mapel_id'])){
        
        
        include ("../includes/db_con.php");
        
        $mapel_id = $_POST['mapel_id'];
        $kelas_id = $_POST['kelas_id'];
        
        if($mapel_id > 0){
            //dapatkan topik apa saja dari jenjang itu dan mapel itu
            $mapel_id = $_POST['mapel_id'];

            $query2 =   "SELECT topik_id, topik_nama
                        FROM topik
                        WHERE topik_mapel_id = $mapel_id AND topik_jenjang_id IN (SELECT kelas_jenjang_id FROM kelas WHERE kelas_id = {$kelas_id})";

            $query_info2 = mysqli_query($conn, $query2);

            $count = 0;

            $options = "<option value= 0>Pilih Topik</option>";
             while($row2 = mysqli_fetch_array($query_info2)){
                $count++;
                $topik_nama = $row2['topik_nama'];

                $options .= "<option value={$row2['topik_id']}>$topik_nama</option>";
             }

             if($count>0){
                echo "<label>Topik:</label>"; 
                echo"<select class='form-control form-control-sm mb-2' name='option_topik' id='option_topik'>";
                    echo $options;
                echo"</select>";
             }
             elseif ($count<=0){
                 echo "<label>Topik:</label>"; 
                 echo'<p class="bg-warning">Topik belum ada silahkan set topik pada menu master</p>';
             }
        }
    }
?>

<script>
    $(document).ready(function(){
        
    });
</script>