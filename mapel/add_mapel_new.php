<?php

    if(isset($_POST['mapel_nama_input'])){
    
        $d_mapel_id_kelas = $_POST['check_kelas_option'];
        $d_mapel_id_guru = $_POST['guru_id_option'];
        
        
        $result_id_kelas = array_filter($d_mapel_id_kelas, function ($v) {
            return $v > 0;
        });
        $result_id_guru = array_filter($d_mapel_id_guru);
        
//        echo count($result_id_guru);
//        echo count($result_id_kelas);
        
//        for($i=0;$i<count($result_id_guru);$i++){
//            
//            echo $result_id_guru[$i];
//            echo "<br>";
//        }
//
//        for($i=0;$i<count($result_id_kelas);$i++){
//            echo $result_id_kelas[$i];
//            echo "<br>";
//        }
        
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

            $sql_cek_mapel_id = "SELECT mapel_id from mapel WHERE mapel_nama = '$mapel_nama' AND mapel_t_ajaran_id = '$mapel_t_ajaran_id'";
            $query_d_mapel_info = mysqli_query($conn, $sql_cek_mapel_id);


            if(mysqli_num_rows($query_d_mapel_info)>0){
                echo "<div class='p-3 mb-2 bg-danger text-white'>Nama Mapel Sudah ada</div>";
//            //dapatkan id mapel
//            $row2 = mysqli_fetch_array($query_d_mapel_info);
//            $d_mapel_id_mapel = $row2['mapel_id'];
//            
//            //sudah ada nama mapel, cukup tambahkan detail di kelas mana dan diajar siapa
//            $sql_insert2 = "INSERT INTO d_mapel(d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru) VALUES('$d_mapel_id_mapel','$d_mapel_id_kelas','$d_mapel_id_guru')";
//            mysqli_query($conn, $sql_insert2);
            }
            else{
                //echo "<div class='p-3 mb-2 bg-success text-white'>Data Berhasil Ditambahkan</div>";
                
                //belum ada nama mapel
                //insert ke tabel mapel
                $sql_insert = "INSERT INTO mapel(mapel_nama, mapel_urutan, mapel_nama_singkatan, mapel_kkm, mapel_t_ajaran_id)
                               VALUES('$mapel_nama',$mapel_urutan,'$mapel_nama_singkatan',$mapel_kkm,'$mapel_t_ajaran_id')";
                mysqli_query($conn, $sql_insert);
                //insert ke tabel d_mapel
                $d_mapel_id_mapel = mysqli_insert_id($conn);
                
                //$d_mapel_id_mapel = 0;
                
                $pertama = 0;
                $sql_insert2 = "INSERT INTO d_mapel(d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru) VALUES "; 
                for ($i = 0; $i < count($d_mapel_id_guru); $i++)
                {
                    if($d_mapel_id_guru[$i]>0 && $d_mapel_id_kelas[$i]>0){
                        if($i>0 && $i!=count($d_mapel_id_guru) && $pertama!=0)
                        {$sql_insert2 .= ",";}
                        
                        $sql_insert2 .= "(";
                        $sql_insert2 .= "$d_mapel_id_mapel,";
                        $sql_insert2 .= "$d_mapel_id_kelas[$i],";
                        $sql_insert2 .= "$d_mapel_id_guru[$i]";
                        $sql_insert2 .= ")";
                        
                        $pertama = 1;
                    }
                    
                }
                
                //echo $sql_insert2;
                
    //            $sql_insert2 = "INSERT INTO d_mapel(d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru) VALUES('$d_mapel_id_mapel','$d_mapel_id_kelas','$d_mapel_id_guru')";
                mysqli_query($conn, $sql_insert2);
                echo '<div class="alert alert-success alert-dismissible fade show">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button>
                            <strong>Data berhasil diinput</strong>
                        </div>';
            }
        }
        
        
    }