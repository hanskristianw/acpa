<?php

    if($_POST['mapel_id']){
        include '../includes/db_con.php';
        $mapel_id = $_POST['mapel_id'];        
        
        $sql3 = "SELECT * FROM
                (
                        SELECT karakter_id, karakter_nama FROM karakter
                )AS satu
                LEFT JOIN(
                    SELECT d_karakter_k_id, d_karakter_mapel_id from d_karakter 
                        LEFT JOIN karakter
                        ON  d_karakter_k_id = karakter_id
                        WHERE d_karakter_mapel_id = $mapel_id
                )as dua
                ON satu.karakter_id=dua.d_karakter_k_id
                ORDER BY karakter_id";
        $result3 = mysqli_query($conn, $sql3);
        $options3 ="";
        while ($row3 = mysqli_fetch_assoc($result3)) {
            $options3 .= "<div class='form-group row'>";
            if($row3['d_karakter_k_id']){
                $options3 .= "<div class='col-sm-4 pl-4'><input type='checkbox' name='check_karakter[]' value={$row3['karakter_id']} checked> {$row3['karakter_nama']}</div>";
            }
            else{
                $options3 .= "<div class='col-sm-4 pl-4'><input type='checkbox' name='check_karakter[]' value={$row3['karakter_id']}> {$row3['karakter_nama']}</div>";
            }
            
            $options3 .= "</div>";
        }
        
        echo $options3;
        
        echo'<input type="submit" name="submit_karakter" class="btn btn-primary" value="Tambah Karakter">';
    }
