<?php

    if(isset($_POST['mapel_nama_input'])){
    
        $mapel_id = $_POST['mapel_id_option'];
        $d_mapel_id_kelas = $_POST['check_kelas_option'];
        $d_mapel_id_guru = $_POST['guru_id_option'];
        
        $mapel_nama_input = $_POST['mapel_nama_input'];
        $mapel_singkat_nama_input = $_POST['mapel_singkat_nama_input'];
        $mapel_kkm = $_POST['mapel_kkm'];
        $mapel_urutan = $_POST['mapel_urutan'];
        
        
        $result_id_kelas = array_filter($d_mapel_id_kelas, function ($v) {
            return $v > 0;
        });
        $result_id_guru = array_filter($d_mapel_id_guru);
        
        if(!isset($_POST['check_kelas_option'])){
            echo "Pilih kelas mengajar!";
        }
        elseif(count($result_id_guru)!=count($result_id_kelas)){
            echo "Jumlah guru dan kelas mengajar harus sama!";
        }
        else{
            include_once '../includes/db_con.php';
            $mapel_nama = mysqli_real_escape_string($conn, $_POST['mapel_nama_input']);
            $mapel_nama_singkatan = mysqli_real_escape_string($conn, $_POST['mapel_singkat_nama_input']);

            $mapel_kkm = $_POST['mapel_kkm'];
            $mapel_urutan = $_POST['mapel_urutan'];

            //mendapat tahun ajaran yang active
            $sql_cek_tahun = "SELECT * FROM t_ajaran WHERE t_ajaran_active = 1";
            $query_mapel_info = mysqli_query($conn, $sql_cek_tahun);
            $row = mysqli_fetch_array($query_mapel_info);
            $mapel_t_ajaran_id = $row['t_ajaran_id'];

//            $sql_cek_mapel_id = "SELECT mapel_id from mapel WHERE mapel_nama = '$mapel_nama' AND mapel_t_ajaran_id = '$mapel_t_ajaran_id'";
//            $query_d_mapel_info = mysqli_query($conn, $sql_cek_mapel_id);
//            
            //dapatkan yang dulunya dipilih
            $d_mapel_id_lama = array();
            $kelas_id_lama = array();
            $guru_id_lama = array();
            $d_mapel_id_kelas_ori_array = array();
            $d_mapel_id_guru_ori_array = array();

            $sql3 = "SELECT * FROM d_mapel WHERE d_mapel_id_mapel = $mapel_id";
            $result3 = mysqli_query($conn, $sql3);
            
            while ($row3 = mysqli_fetch_assoc($result3)) {
                array_push($d_mapel_id_lama,$row3['d_mapel_id']);
//                array_push($d_mapel_id_mapel_ori_array,$row3['d_mapel_id_mapel']);
                array_push($kelas_id_lama,$row3['d_mapel_id_kelas']);
                array_push($guru_id_lama,$row3['d_mapel_id_guru']);
            }
//            echo "LAMA: <br>";
//            for($i=0;$i<count($kelas_id_lama);$i++){
//                echo $d_mapel_id_lama[$i];
//                echo " ";
//                echo $kelas_id_lama[$i];
//                echo " ";
//                echo $guru_id_lama[$i];
//                echo "<br>";
//            }
//            echo "<br>BARU: <br>";
            $kelas_id_baru = array();
            $guru_id_baru = array();
            
            for ($i = 0; $i < count($d_mapel_id_guru); $i++)
            {
                if($d_mapel_id_guru[$i]>0 && $d_mapel_id_kelas[$i]>0){
                    array_push($kelas_id_baru,$d_mapel_id_kelas[$i]);
                    array_push($guru_id_baru,$d_mapel_id_guru[$i]);
                }
            }
            
//            for ($i = 0; $i < count($kelas_id_baru); $i++)
//            {
//                echo $kelas_id_baru[$i];
//                echo " ";
//                echo $guru_id_baru[$i];
//                echo "<br>";
//            }
            
//            echo "<br>UPDATE d_mapel<br>";
            //pisahkan
            $index_kelas_lama_update = array();
            $new_teacher_id = array();
            //echo count($kelas_id_lama);
            
            //bandingkan baru dengan lama
            //kalau data lama = baru berarti update
            for ($i = 0; $i < count($kelas_id_baru); $i++)
            {
                for ($j = 0; $j < count($kelas_id_lama); $j++)
                {
                    //jika kelasnya sama
                    if($kelas_id_baru[$i]==$kelas_id_lama[$j]){
                        //jika gurunya berbeda
                        if($guru_id_baru[$i]!=$guru_id_lama[$j]){
                            array_push($index_kelas_lama_update,$j);
                            array_push($new_teacher_id,$guru_id_baru[$i]);
                        }
                    }
                }
            }
            
//            for ($i = 0; $i < count($index_kelas_lama_update); $i++)
//            {
//                echo $d_mapel_id_lama[$index_kelas_lama_update[$i]];
//                echo " ";
//                echo $kelas_id_lama[$index_kelas_lama_update[$i]];
//                echo " ";
//                echo $new_teacher_id[$i];
//                echo "<br>";
//            }
//            echo "<br>";
            
            $sql_update = "UPDATE d_mapel SET d_mapel_id_guru = CASE d_mapel_id "; 
        
            for ($i = 0; $i < count($index_kelas_lama_update); $i++)
            {
                $sql_update .= "WHEN ";
                $sql_update .= $d_mapel_id_lama[$index_kelas_lama_update[$i]];
                $sql_update .= " THEN ";
                $sql_update .= $new_teacher_id[$i];
                $sql_update .= " ";
                if($i == count($index_kelas_lama_update)-1)
                {$sql_update .= "ELSE d_mapel_id_guru END";}
            }
            
            $sql_update .= " WHERE d_mapel_id in (";
        
            for ($i = 0; $i < count($index_kelas_lama_update); $i++)
            {
                $sql_update .= $d_mapel_id_lama[$index_kelas_lama_update[$i]];
                if($i != count($index_kelas_lama_update)-1)
                {$sql_update .= ",";}
            }

            $sql_update .= ")";
            //echo $sql_update;
            //echo "<br>";
            
            ///////////////////////////////////////////////
            //echo "<br>DELETE id d_mapel<br>";
            $index_kelas_lama_delete = array();
            $ketemu = 0;
            for ($i = 0; $i < count($kelas_id_lama); $i++)
            {
                for ($j = 0; $j < count($kelas_id_baru); $j++)
                {
                    if($kelas_id_lama[$i]==$kelas_id_baru[$j]){
                        $ketemu = 1;
                    }
                }
                if($ketemu == 0){
                    array_push($index_kelas_lama_delete,$i);
                }
                $ketemu = 0;
            }
            
//            for ($i = 0; $i < count($index_kelas_lama_delete); $i++)
//            {
//                echo $d_mapel_id_lama[$index_kelas_lama_delete[$i]];
//                echo "<br>";
//            }
            
            $sql_delete = "DELETE from d_mapel WHERE d_mapel_id IN ("; 
        
            for ($i = 0; $i < count($index_kelas_lama_delete); $i++)
            {
                $sql_delete .= $d_mapel_id_lama[$index_kelas_lama_delete[$i]];
                
                if($i < count($index_kelas_lama_delete)-1 && count($index_kelas_lama_delete)!=1)
                {$sql_delete .= ",";}
            }
            $sql_delete .= ")";
             
            //echo $sql_delete;
            //echo "<br>";
            
            /////////////////////////////////////////////////////
            //echo "<br>INSERT d_mapel id kelas nya<br>"; 
            
            //yang tidak ada di lama dari yang baru
            //bandingkan id kelas baru dengan kelas lama
            //jika id baru tidak ada di daftar kelas lama
            //maka insert ke index kelas baru
            $ketemu2 = 0;
            $index_kelas_baru_insert = array();
            
            for ($i = 0; $i < count($kelas_id_baru); $i++)
            {
                for ($j = 0; $j < count($kelas_id_lama); $j++)
                {
                    //jika kelasnya sama
                    if($kelas_id_baru[$i]==$kelas_id_lama[$j]){
                        $ketemu2 = 1;
                    }
                }
                if($ketemu2 == 0){
                    array_push($index_kelas_baru_insert,$i);
                }
                $ketemu2 = 0;
            }
//            for ($i = 0; $i < count($index_kelas_baru_insert); $i++)
//            {
//                echo $kelas_id_baru[$index_kelas_baru_insert[$i]];
//                echo " ";
//                echo $guru_id_baru[$index_kelas_baru_insert[$i]];
//                echo "<br>";
//            }

            $sql_insert = "INSERT INTO d_mapel(d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru) VALUES "; 
        
            for ($i = 0; $i < count($index_kelas_baru_insert); $i++)
            {
                $sql_insert .= "(";
                $sql_insert .= "$mapel_id,";
                $sql_insert .= $kelas_id_baru[$index_kelas_baru_insert[$i]];
                $sql_insert .= ",";
                $sql_insert .= $guru_id_baru[$index_kelas_baru_insert[$i]];
                if($i<count($index_kelas_baru_insert)-1)
                {$sql_insert .= "),";}
                else
                {$sql_insert .= ")";}
            }
            
//            echo $sql_insert;
//            echo "<br>";
            
            //mysqli_query($conn, $sql_update);
            //mysqli_query($conn, $sql_delete);
            
            $sql_update_mapel = "UPDATE mapel SET mapel_nama = '$mapel_nama_input', mapel_urutan = '$mapel_urutan', mapel_nama_singkatan = '$mapel_nama_singkatan', mapel_kkm = '$mapel_kkm' WHERE mapel_id = $mapel_id";
            
            if(count($index_kelas_lama_update)>0){
                if (!mysqli_query($conn, $sql_update))
                {
                    echo("<br> Error description: " . mysqli_error($conn));
                }
                else{
                        echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button>
                            <strong>'.count($index_kelas_lama_update).' Data berhasil diupdate</strong>
                        </div>';
                }
            }
            
            if(count($index_kelas_baru_insert)>0){
                if (!mysqli_query($conn, $sql_insert))
                {
                    echo("<br> Error description: " . mysqli_error($conn));
                }
                else{
                        echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button>
                            <strong>'.count($index_kelas_baru_insert).' Data berhasil diinput</strong>
                        </div>';
                }
            }
            
            if(count($index_kelas_lama_delete)>0){
                if (!mysqli_query($conn, $sql_delete))
                {
                    echo("<br> Error description: " . mysqli_error($conn));
                }
                else{
                        echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button>
                            <strong>'.count($index_kelas_lama_delete).' Data berhasil dihapus</strong>
                        </div>';
                }
            }
            
            if (!mysqli_query($conn, $sql_update_mapel))
            {
                echo("<br> Error description: " . mysqli_error($conn));
            }
            else
            {
                    echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>Mapel berhasil diupdate</strong>
                    </div>';
            }
            mysqli_close($conn);
            
            ///////////////////////////////////////////////
        }
        
        
    }