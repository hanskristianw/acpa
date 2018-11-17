<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    include ("../includes/db_con.php");

    if(!empty($_POST["bulan_id"])) {
        $query = "SELECT k_afektif_topik_nama, k_afektif_1, k_afektif_2, k_afektif_3, k_afektif_id
                FROM k_afektif, t_ajaran 
                WHERE k_afektif_t_ajaran_id = t_ajaran_id AND
                    t_ajaran_active = 1 AND 
                    k_afektif_bulan = {$_POST["bulan_id"]}";

        $query_k_afektif_info = mysqli_query($conn, $query);

        $temp_afek_nama ="-";
        $temp_afekif_id ="-";
        $temp_afek_1 ="-";
        $temp_afek_2 ="-";
        $temp_afek_3 ="-";
        
        
        if(!$query_k_afektif_info){
            die("QUERY FAILED".mysqli_error($conn));
        }
        while($row = mysqli_fetch_array($query_k_afektif_info)){
            $temp_afekif_id = $row['k_afektif_id'];
            $temp_afek_nama = $row['k_afektif_topik_nama'];
            $temp_afek_1 = $row['k_afektif_1'];
            $temp_afek_2 = $row['k_afektif_2'];
            $temp_afek_3 = $row['k_afektif_3'];
        }
        
        echo "<h7> <b>Topik Afektif:</b> ".$temp_afek_nama."</h4>";
        echo"<table class='table table-sm table-striped mb-2 mt-2' id='tabel_kriteria'>
            <thead>
                <tr>
                    <th>Indikator 1</th>
                    <th>Indikator 2</th>
                    <th>Indikator 3</th>
                </tr>
            </thead>
            <tbody>";
        
            echo"<input type='hidden' name='input_afektif_id' id='input_afektif_id' value='$temp_afekif_id'>";
            echo"<tr>";
            echo"<td>$temp_afek_1</td>";
            echo"<td>$temp_afek_2</td>";
            echo"<td>$temp_afek_3</td>";
            echo"</tr>";
        
        echo"</tbody>
            </table>";
    }
?>
