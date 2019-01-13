<?php

if(isset($_POST['mapel_id'])){
    $mapel_id = $_POST['mapel_id'];
    
    include '../includes/db_con.php';
    $sql = "SELECT mapel_nama, mapel_urutan, mapel_nama_singkatan, mapel_kkm from mapel where mapel_id = $mapel_id";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $v_mapel_nama = $row['mapel_nama'];
        $v_mapel_urutan = $row['mapel_urutan'];
        $v_mapel_nama_singkatan = $row['mapel_nama_singkatan'];
        $v_mapel_kkm = $row['mapel_kkm'];
//        $options .= "<option value={$row['mapel_id']}>{$row['mapel_nama']}</option>";
    }
    //cetak textbox dll
    echo'<label class="mt-3">Nama Mapel:</label><input type="text" value="'.$v_mapel_nama.'" id="mapel_nama_input" name="mapel_nama_input" placeholder="Masukkan nama lengkap mapel (digunakan dalam cetak rapot)" class="form-control form-control-sm mb-2" required>
        <label>Mapel Singkatan:</label><input type="text" value="'.$v_mapel_nama_singkatan.'" id="mapel_singkat_nama_input" name="mapel_singkat_nama_input" placeholder="Singkatan nama mapel (ex. MAT,KIM,FIS)" class="form-control form-control-sm mb-2" required>
        <label>Mapel KKM:</label><input type="number" value='.$v_mapel_kkm.' id="mapel_kkm" name="mapel_kkm" placeholder="Masukkan KKM mapel (ex. 70,75,77)" class="form-control form-control-sm mb-2" required>
        <label>Mapel Urutan:</label><input type="number" value='.$v_mapel_urutan.' id="mapel_urutan" name="mapel_urutan" placeholder="Masukkan urutan cetak dalam rapot (ex. 1,2,3)" class="form-control form-control-sm mb-2" required>';
    
    //memasukkan array
    $sql4 = "SELECT d_mapel_id_kelas, d_mapel_id_guru FROM d_mapel WHERE d_mapel_id_mapel = $mapel_id";
    $result4 = mysqli_query($conn, $sql4);
    
    $kelas_array = array();
    $guru_array = array();
    
    while ($row4 = mysqli_fetch_assoc($result4)) {
        array_push($kelas_array,$row4['d_mapel_id_kelas']);
        array_push($guru_array,$row4['d_mapel_id_guru']);
        //$options4 .= "<option value={$row4['guru_id']}>{$row4['guru_name']}</option>";
    }
    
//    for($i=0;$i<count($kelas_array);$i++){
//        echo $kelas_array[$i];
//        echo "<br>";
//    }
    
    //query untuk cetak kelas dan guru pengajar
    $guru_id_ori_array = array();
    $guru_name_ori_array = array();
    
    $sql3 = "SELECT guru_id, guru_name FROM guru WHERE guru_active = 1";
    $result3 = mysqli_query($conn, $sql3);
//    $options3 = "<option value = 0>Pilih Guru Pengajar</option>";
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($guru_id_ori_array,$row3['guru_id']);
        array_push($guru_name_ori_array,$row3['guru_name']);
//        if (in_array($row3['guru_id'], $guru_array)){
//            $options3 .= "<option value={$row3['guru_id']}>{$row3['guru_name']}</option>";
//        }else{
//            $options3 .= "<option value={$row3['guru_id']}>{$row3['guru_name']}</option>";
//        }
//        
    }
    

    $sql2 = "SELECT kelas_id, kelas_nama FROM kelas, t_ajaran WHERE kelas_t_ajaran_id = t_ajaran_id AND t_ajaran_active = 1";
    $result2 = mysqli_query($conn, $sql2);
    $options2 = "";
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $options2 .= "<div class='form-group row'>";
        
        if(in_array($row2['kelas_id'],$kelas_array)){
            $options2 .= "<div class='col-sm-4'><input type='hidden' name='check_kelas_option[]' value={$row2['kelas_id']}><input type='checkbox' checked='checked' onclick='this.previousSibling.value=-1*this.previousSibling.value'> {$row2['kelas_nama']}</div>";
        
            $index_array_guru = array_search($row2['kelas_id'],$kelas_array);
            
            $options3 = "<option value = 0>Pilih Guru Pengajar</option>";
            
            for($i=0;$i<count($guru_id_ori_array);$i++){
                if ($guru_id_ori_array[$i] == $guru_array[$index_array_guru]){
                    $options3 .= "<option value={$guru_id_ori_array[$i]} selected='selected'>{$guru_name_ori_array[$i]}</option>";
                }
                else{
                    $options3 .= "<option value={$guru_id_ori_array[$i]}>{$guru_name_ori_array[$i]}</option>";
                }
            }
            
//            $hitung = 0;
//            while ($row3 = mysqli_fetch_assoc($result3)) {
//                if ($row3['guru_id'] == $guru_array[$index_array_guru]){
//                    $options3 .= "<option value={$row3['guru_id']} selected='selected'>{$row3['guru_name']}</option>";
//                }else{
//                    $options3 .= "<option value={$row3['guru_id']}>{$row3['guru_name']}</option>";
//                }
//                $hitung++;
//            }
            
        }
        else{
            $options2 .= "<div class='col-sm-4'><input type='hidden' name='check_kelas_option[]' value=-{$row2['kelas_id']}><input type='checkbox' onclick='this.previousSibling.value=-1*this.previousSibling.value'> {$row2['kelas_nama']}</div>";
        
            $options3 = "<option value = 0>Pilih Guru Pengajar</option>";
            for($i=0;$i<count($guru_id_ori_array);$i++){
                $options3 .= "<option value={$guru_id_ori_array[$i]}>{$guru_name_ori_array[$i]}</option>";
            }
        }
        
        $options2 .= "<div class='col-sm-8'><select class='form-control form-control-sm' name='guru_id_option[]'>".$options3."</select></div>";
        $options2 .= "</div>";
    }
    
    echo"<h4 class='mb-4 mt-4'>Kelas dan guru pengajar</h4>";
    echo $options2;
    echo '<input type="submit" name="submit_update_mapel" id="sub_mapel" class="btn btn-primary mt-3" value="UPDATE MAPEL">';
}