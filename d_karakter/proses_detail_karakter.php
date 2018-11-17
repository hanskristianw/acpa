<?php

    if($_POST['option_mapel']){
        include '../includes/db_con.php';
        $mapel_id = $_POST['option_mapel'];        
        
        //hapus yang tidak ada dalam pilihan
        if(isset($_POST['check_karakter'])){
            //hapus semua
            $sql_delete = "DELETE FROM d_karakter
                           WHERE d_karakter_mapel_id = $mapel_id";
            
            $arr_karakter = $_POST['check_karakter'];
            $string_karakter_id = "";
            for($i=0;$i<sizeof($arr_karakter);$i++){
                $string_karakter_id .= "(";
                $string_karakter_id .= $mapel_id.",";
                $string_karakter_id .= $arr_karakter[$i];
                $string_karakter_id .= ")";
                if($i!=sizeof($arr_karakter)-1){
                    $string_karakter_id .= ",";
                }
            }
            
            $sql_insert = "INSERT INTO d_karakter(d_karakter_mapel_id, d_karakter_k_id) VALUES ".$string_karakter_id;
            
            if(mysqli_query($conn, $sql_delete)){
                if(mysqli_query($conn, $sql_insert)){
                    echo'<div class="alert alert-success">
                            <strong>Karakter berhasil diupdate</strong> .
                         </div>';
                }
            }
        }else{
            $sql_delete = "DELETE FROM d_karakter
                           WHERE d_karakter_mapel_id = $mapel_id";
            if(mysqli_query($conn, $sql_delete)){
                echo'<div class="alert alert-success">
                        <strong>Karakter berhasil diupdate</strong> .
                     </div>';
            }
        }
        
        
        
//        $result3 = mysqli_query($conn, $sql3);
//        $options3 ="";
//        while ($row3 = mysqli_fetch_assoc($result3)) {
//            $options3 .= "<div class='form-group row'>";
//            if($row3['d_karakter_k_id']){
//                $options3 .= "<div class='col-sm-4 pl-4'><input type='checkbox' name='check_karakter[]' value={$row3['karakter_id']} checked> {$row3['karakter_nama']}</div>";
//            }
//            else{
//                $options3 .= "<div class='col-sm-4 pl-4'><input type='checkbox' name='check_karakter[]' value={$row3['karakter_id']}> {$row3['karakter_nama']}</div>";
//            }
//            
//            $options3 .= "</div>";
//        }
//        
//        echo $options3;
    }
