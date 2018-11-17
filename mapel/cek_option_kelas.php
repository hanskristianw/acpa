<?php
require_once("../includes/db_con.php");

$a = 0;
if(!empty($_POST["mapel_nama"])) {
    
    $a = mysqli_real_escape_string($conn, $_POST['mapel_nama']);
    
    $result = mysqli_query($conn, "SELECT mapel_id FROM mapel, t_ajaran WHERE mapel_nama='" . $_POST["mapel_nama"] . "' AND mapel_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1");
    $row = mysqli_fetch_array($result);
    $mapel_id = $row['mapel_id'];
    
    //echo $mapel_id;
    
    if($mapel_id){
        $sql = "SELECT kelas_id, kelas_nama
        FROM kelas, t_ajaran
        WHERE kelas_t_ajaran_id = t_ajaran_id
        AND t_ajaran_active = 1
        AND kelas_id NOT IN
            (SELECT d_mapel_id_kelas
             FROM d_mapel WHERE d_mapel_id_mapel = {$mapel_id})";
    
        $result2 = mysqli_query($conn, $sql);
        $options = "";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $options .= "<option value={$row2['kelas_id']}>{$row2['kelas_nama']}</option>";
        }
        echo $options;
    }
    else{
        $sql = "SELECT kelas_id, kelas_nama FROM kelas, t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
        $result = mysqli_query($conn, $sql);
        $options = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value={$row['kelas_id']}>{$row['kelas_nama']}</option>";
        }
        echo $options;
    }
    
    
}
?>