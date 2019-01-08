<?php

    include ("../includes/db_con.php");
    include ("../includes/fungsi_lib.php");
    if(!empty($_POST["arr"])) {

        $arr = $_POST["arr"];
        
        $cek_array = explode(',', $_POST["arr"]);
        if(isset($cek_array[2]))
        {
            list($mapel_id, $kelas_id, $bulan_id) = explode(',', $_POST["arr"]);
        }
        $resultCheck = -1;
        if(isset($mapel_id) && isset($kelas_id) && isset($bulan_id))
        {
            //dapatkan afektif id pada tahun ajaran aktif
            $query = "SELECT k_afektif_id
                    FROM k_afektif, t_ajaran 
                    WHERE k_afektif_t_ajaran_id = t_ajaran_id AND
                        t_ajaran_active = 1 AND 
                        k_afektif_bulan = {$bulan_id}";
            $query_k_afektif_info = mysqli_query($conn, $query);
            if(!$query_k_afektif_info){
                die("QUERY FAILED".mysqli_error($conn));
            }
            while($row = mysqli_fetch_array($query_k_afektif_info)){
                $afektif_id = $row['k_afektif_id'];
            }


            //cek apakah sudah pernah input nilai sebelumnya
            $query =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_agama, siswa_nama_belakang, afektif_nilai, afektif_id 
                        from afektif, siswa
                        WHERE afektif_k_afektif_id = $afektif_id AND afektif_mapel_id = {$mapel_id} AND afektif_siswa_id = siswa_id AND siswa_id_kelas = {$kelas_id} ORDER BY siswa_nama_depan";

            $query_afektif_info = mysqli_query($conn, $query);
            $resultCheck = mysqli_num_rows($query_afektif_info);
        }
        
        if($resultCheck == 0){
            //belum pernah isi nilai
//            $query =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, afektif_nilai
//                    FROM siswa
//                    LEFT JOIN afektif
//                    ON siswa_id_kelas = afektif_siswa_id
//                    LEFT JOIN k_afektif
//                    ON afektif_k_afektif_id = k_afektif_id
//                    LEFT JOIN t_ajaran
//                    ON k_afektif_t_ajaran_id = t_ajaran_id
//                    WHERE siswa_id_kelas = {$kelas_id}";
            
            $query =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, siswa_agama
                    FROM siswa
                    WHERE siswa_id_kelas = {$kelas_id} ORDER BY siswa_nama_depan";

            $query_afektif_info = mysqli_query($conn, $query);
            
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai afektif untuk kelas dan bulan ini, silahkan TEKAN SAVE untuk menyimpan nilai
                </div>';

            echo return_info_warna_agama();

            
            echo'<div id="table-scroll" class="table table-sm table-responsive table-bordered">
        <div class="table-wrap">
          <table class="table main-table">
            <thead>
              <tr>
                <th class="fixed-side" scope="col"></th>
                <th class="fixed-side" scope="col"></th>
                <th colspan="3">Minggu 1 <select class="form-control form-control-sm mb-2" name="option_minggu1" id="option_minggu1">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 2<select class="form-control form-control-sm mb-2" name="option_minggu2" id="option_minggu2">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 3<select class="form-control form-control-sm mb-2" name="option_minggu3" id="option_minggu3">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 4<select class="form-control form-control-sm mb-2" name="option_minggu4" id="option_minggu4">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 5<select class="form-control form-control-sm mb-2" name="option_minggu5" id="option_minggu5">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th class="fixed-side" scope="col">No</th>
                <th class="fixed-side" scope="col">Nama</th>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
              </tr>
            </thead>
            <tbody>';
                $no = 0;
                while($row = mysqli_fetch_array($query_afektif_info)){
                    $no++;
                    echo '<tr class="'.return_warna_tabel_agama($row['siswa_agama']).'">';
                    echo'<td class="fixed-side">';
                    echo"{$no}</td>";
                    echo'<td class="fixed-side">';
                    $snb = $row['siswa_nama_belakang'];
                    if(strlen($snb) > 0){
                        echo"{$row['siswa_nama_depan']} $snb[0]</td>";
                    }else{
                        echo"{$row['siswa_nama_depan']}</td>";
                    }
                    
                    echo "<div id = 'input_afek'>";
                    //minggu 1
                    for($a=1; $a<=3; $a++){
                        echo "<td><input type = 'number' required style='width: 32px;' class='minggu1' id='{$row['siswa_id']},1' value='3' min='1' max='3'></td>";
                    }
                    //minggu 2
                    for($a=1; $a<=3; $a++){
                        echo "<td><input type = 'number' required style='width: 32px;' class='minggu2' id='{$row['siswa_id']},2' value='3' min='1' max='3'></td>";
                    }
                    //minggu 3
                    for($a=1; $a<=3; $a++){
                        echo "<td><input type = 'number' required style='width: 32px;' class='minggu3' id='{$row['siswa_id']},3' value='3' min='1' max='3'></td>";
                    }
                    //minggu 4
                    for($a=1; $a<=3; $a++){
                        echo "<td><input type = 'number' required style='width: 32px;' class='minggu4' id='{$row['siswa_id']},4' value='3' min='1' max='3'></td>";
                    }
                    //minggu 5
                    for($a=1; $a<=3; $a++){
                        echo "<td><input type = 'number' required style='width: 32px;' class='minggu5' id='{$row['siswa_id']},5' value='3' min='1' max='3'></td>";
                    }
                    echo "</div>";
                    echo '</tr>';
                }
            echo'</tbody>
            
          </table>
        </div>
      </div>';
            
            echo '<input type = "button" id="proses_nilai" value="SAVE" class="btn btn-success mt-2">';
        }
        elseif($resultCheck > 1){
            //sudah pernah isi nilai
            echo '<div class="alert alert-success alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Tekan UPDATE setelah melakukan proses edit nilai 
                </div>';
                
            $query_afektif_info = mysqli_query($conn, $query);
            
            echo return_info_warna_agama();

            echo'<div id="table-scroll" class="table table-sm table-responsive table-bordered">
        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th class="fixed-side" scope="col"></th>
                <th class="fixed-side" scope="col"></th>
                <th colspan="3">Minggu 1 <select class="form-control form-control-sm mb-2" name="option_minggu1" id="option_minggu1">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 2<select class="form-control form-control-sm mb-2" name="option_minggu2" id="option_minggu2">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 3<select class="form-control form-control-sm mb-2" name="option_minggu3" id="option_minggu3">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 4<select class="form-control form-control-sm mb-2" name="option_minggu4" id="option_minggu4">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
                <th colspan="3">Minggu 5<select class="form-control form-control-sm mb-2" name="option_minggu5" id="option_minggu5">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                </th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th class="fixed-side" scope="col">No</th>
                <th class="fixed-side" scope="col">Nama</th>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
                <td>A 1</td>
                <td>A 2</td>
                <td>A 3</td>
              </tr>
            </thead>
            <tbody>';
                $no2 = 0;
                while($row = mysqli_fetch_array($query_afektif_info)){
                    $no2++;
                    
                    echo '<tr class="'.return_warna_tabel_agama($row['siswa_agama']).'">';
                    echo'<td class="fixed-side">';
                    echo"{$no2}</td>";
                    echo'<td class="fixed-side">';
                    $snb = $row['siswa_nama_belakang'];
                    if(strlen($snb) > 0){
                        echo"{$row['siswa_nama_depan']} $snb[0]</td>";
                    }else{
                        echo"{$row['siswa_nama_depan']}</td>";
                    }
                    
                    //Pisahkan nilai dari row['afektif_nilai']
                    
                    $tags = explode('/', $row['afektif_nilai']);
                    
                    
                    echo "<div id = 'input_afek'>";
                    
                    $count = 1;
                    foreach( $tags as $link){
                        //echo $link;
                        $nilai = explode('_', $link);
                        foreach($nilai as $link){
                            //echo $link;
                            //echo " ";
                            if($count == 1){
                                echo "<td><input type = 'number' required style='width: 32px;' class='minggu1' id='{$row['afektif_id']},1' value=$link min='1' max='3'></td>";
                            }
                            elseif($count == 2){
                                echo "<td><input type = 'number' required style='width: 32px;' class='minggu2' id='{$row['afektif_id']},2' value=$link min='1' max='3'></td>";
                            }
                            elseif($count == 3){
                                echo "<td><input type = 'number' required style='width: 32px;' class='minggu3' id='{$row['afektif_id']},3' value=$link min='1' max='3'></td>";
                            }
                            elseif($count == 4){
                                echo "<td><input type = 'number' required style='width: 32px;' class='minggu4' id='{$row['afektif_id']},4' value=$link min='1' max='3'></td>";
                            }
                            elseif($count == 5){
                                echo "<td><input type = 'number' required style='width: 32px;' class='minggu5' id='{$row['afektif_id']},5' value=$link min='1' max='3'></td>";
                            }
                        }
                        $count++; 
                     }
                     
                    echo "</div>";
                    echo '</tr>';
                }
            echo'</tbody>
            
          </table>
        </div>
      </div>';
            
            echo '<input type = "button" id="proses_update" value="UPDATE" class="btn btn-success mt-2">';
        }

        
    }
?>

<script>
        
    $(document).ready(function(){
        
        var $loading = $('#loadingDiv').hide();
        $(document)
          .ajaxStart(function () {
            $loading.show();
          })
          .ajaxStop(function () {
            $loading.hide();
          });
        
        //atur max dan min nilai untuk setiap number
        $('input[type=number]').each(function(){
            $(this).keydown(function(e)
            {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    // numbers   
                       key >= 48 && key <= 57 ||
                    // Numeric keypad
                       key >= 96 && key <= 105 ||
                    // Backspace and Tab and Enter
                       key == 8 || key == 9 || key == 13 ||
                    // Home and End
                       key == 35 || key == 36 ||
                    // left and right arrows
                       key == 37 || key == 39 ||
                    // Del and Ins
                       key == 46 || key == 45);
            });
            
            $(this).change(function () {
                var max = parseInt($(this).attr('max'));
                var min = parseInt($(this).attr('min'));
                if ($(this).val() > max)
                {
                    $(this).val(max);
                }
                else if ($(this).val() < min)
                {
                    $(this).val(min);
                } 
            });
        }); 
        
        $("#proses_nilai").click(function () {
            $("#proses_nilai").attr("disabled", true);
            var arr = [];
            var afek = [];
            var siswa_id = [];
            var insertafektif = "insertafektif";
            $('input[type=number]').each(function(){
               //pisahkan nama dan masukkan kedalam arr 
               var pisah = $(this).attr("id").split(',');
               
               var siswa_id = pisah[0];
               var nilai_afektif_ke = $(this).val();
               var minggu_ke = pisah[1];
               //0 = siswa_id, 1 = nilai afektif ke, 2 = minggu ke
               arr.push([siswa_id, nilai_afektif_ke, minggu_ke]); 
                 
               //console.log(arr);
            });
            
            
            
            var sid;
            sid = 0;
            
            var temp_sid;
            var mke;
            mke = 1;
            
            var temp_mingguke;
            var str_nilai ="";
            for (var i = 0; i < arr.length; i++)
            {
                //tampung id siswa
                temp_sid = arr[i][0];
                temp_mingguke = arr[i][2];
                
                if(sid == temp_sid)
                {
                    //berarti id siswa sama dengan sebelumnya
                    //cukup gabungkan nilai saja
                    if(mke == temp_mingguke)
                    {
                        //nilai masih dalam minggu yang sama tandai dengan _
                        str_nilai += '_';
                    }
                    else{
                        //nilai berbeda minggu tandai dengan /
                        str_nilai += '/';
                    }
                    str_nilai = str_nilai.concat(arr[i][1]);
                    
                    //jika ini siswa terakhir maka push saja
                    if(i==arr.length-1)
                    {
                        siswa_id.push(sid);
                        //str_nilai += '+' + sid;
                        afek.push(str_nilai);
                    }
                }
                else
                {
                    //berarti id siswa tidak sama dengan sebelumnya
                    //push nilai sebelumnya ke array
                    if(i!=0)
                    {
                        siswa_id.push(sid);
                        //str_nilai += '+' + sid;
                        afek.push(str_nilai);
                    }
                    //kosongkan string
                    str_nilai = "";
                    //set sid ke siswa dengan id baru
                    sid = temp_sid;
                    //gabungkan nilai
                    str_nilai = str_nilai.concat(arr[i][1]);
                    
                }
                //minggu sebelumnya sama dengan minggu skrng
                mke = temp_mingguke;
                //console.log(arr[i][0], arr[i][1], arr[i][2]);
            }
            
            var mapel_kelas = $('#option_utama').val();
            var bulan_id = $("#option_bulan_afektif").val();
            
            if(mapel_kelas !=0 && bulan_id !=0)
            {
                $.post("afektif/proses_afektif.php",{afek: afek, mapel_kelas: mapel_kelas, bulan_id:bulan_id, siswa_id:siswa_id, insertafektif:insertafektif}, function(data){
                $("#container-hasil").html(data);
                if(data){
                    $("#proses_nilai").attr("disabled", false);
                }
                $("#option_utama").val("0").change();
                $("#container-afektif").hide();
                $("#container-hasil").show();
                });
            }
            //alert($("#input_afektif_id").val());
        });
        
        $("#proses_update").click(function () {
            $("#proses_update").attr("disabled", true);
            var arr = [];
            var afek = [];
            var siswa_id = [];
            var updateafektif = "updateafektif";
            $('input[type=number]').each(function(){
               //pisahkan nama dan masukkan kedalam arr 
               var pisah = $(this).attr("id").split(',');
               
               var siswa_id = pisah[0];
               var nilai_afektif_ke = $(this).val();
               var minggu_ke = pisah[1];
               //0 = siswa_id, 1 = nilai afektif ke, 2 = minggu ke
               arr.push([siswa_id, nilai_afektif_ke, minggu_ke]); 
                 
               //console.log(arr);
            });
            
            var sid;
            sid = 0;
            
            var temp_sid;
            var mke;
            mke = 1;
            
            var temp_mingguke;
            var str_nilai ="";
            for (var i = 0; i < arr.length; i++)
            {
                //tampung id siswa
                temp_sid = arr[i][0];
                temp_mingguke = arr[i][2];
                
                if(sid == temp_sid)
                {
                    //berarti id siswa sama dengan sebelumnya
                    //cukup gabungkan nilai saja
                    if(mke == temp_mingguke)
                    {
                        //nilai masih dalam minggu yang sama tandai dengan _
                        str_nilai += '_';
                    }
                    else{
                        //nilai berbeda minggu tandai dengan /
                        str_nilai += '/';
                    }
                    str_nilai = str_nilai.concat(arr[i][1]);
                    
                    //jika ini siswa terakhir maka push saja
                    if(i==arr.length-1)
                    {
                        siswa_id.push(sid);
                        //str_nilai += '+' + sid;
                        afek.push(str_nilai);
                    }
                }
                else
                {
                    //berarti id siswa tidak sama dengan sebelumnya
                    //push nilai sebelumnya ke array
                    if(i!=0)
                    {
                        siswa_id.push(sid);
                        //str_nilai += '+' + sid;
                        afek.push(str_nilai);
                    }
                    //kosongkan string
                    str_nilai = "";
                    //set sid ke siswa dengan id baru
                    sid = temp_sid;
                    //gabungkan nilai
                    str_nilai = str_nilai.concat(arr[i][1]);
                    
                }
                //minggu sebelumnya sama dengan minggu skrng
                mke = temp_mingguke;
                //console.log(arr[i][0], arr[i][1], arr[i][2]);
            }
            
            var mapel_kelas = $('#option_utama').val();
            var bulan_id = $("#option_bulan_afektif").val();
            
            if(mapel_kelas !=0 && bulan_id !=0)
            {
                $.post("afektif/proses_afektif.php",{afek: afek, mapel_kelas: mapel_kelas, bulan_id:bulan_id, siswa_id:siswa_id, updateafektif:updateafektif}, function(data){
                $("#container-hasil").html(data);
                if(data){
                        $("#proses_update").attr("disabled", false);
                }
                $("#option_utama").val("0").change();
                $("#container-afektif").hide();
                $("#container-hasil").show();
                });
            }
            //alert($("#input_afektif_id").val());
        });
        
        $("#option_minggu1").change(function () {

            var aktif1 = $("#option_minggu1").val();
            
            if(aktif1 == 0){
                $('input[type=number].minggu1').val('0')
                $('input[type=number].minggu1').attr("disabled", true);

            }else{
                $('input[type=number].minggu1').val('3')
                $('input[type=number].minggu1').attr("disabled", false);
            } 
        });
        
        $("#option_minggu2").change(function () {

            var aktif2 = $("#option_minggu2").val();
            
            if(aktif2 == 0){
                $('input[type=number].minggu2').val('0')
                $('input[type=number].minggu2').attr("disabled", true);
            }else{
                $('input[type=number].minggu2').val('3')
                $('input[type=number].minggu2').attr("disabled", false);
            } 
        });
        
        $("#option_minggu3").change(function () {

            var aktif3 = $("#option_minggu3").val();
            
            if(aktif3 == 0){
                $('input[type=number].minggu3').val('0')
                $('input[type=number].minggu3').attr("disabled", true);
            }else{
                $('input[type=number].minggu3').val('3')
                $('input[type=number].minggu3').attr("disabled", false);
            } 
        });
        
        $("#option_minggu4").change(function () {

            var aktif4 = $("#option_minggu4").val();
            
            if(aktif4 == 0){
                $('input[type=number].minggu4').val('0')
                $('input[type=number].minggu4').attr("disabled", true);
            }else{
                $('input[type=number].minggu4').val('3')
                $('input[type=number].minggu4').attr("disabled", false);
            } 
        });
        
        $("#option_minggu5").change(function () {

            var aktif5 = $("#option_minggu5").val();
            
            if(aktif5 == 0){
                $('input[type=number].minggu5').val('0')
                $('input[type=number].minggu5').attr("disabled", true);
            }else{
                $('input[type=number].minggu5').val('3')
                $('input[type=number].minggu5').attr("disabled", false);
            } 
        });
        
        
        var cek_pilihan = $("#option_utama").val();
        
        if(cek_pilihan)
        {
            var cek_m1 = $(".minggu1");
            //alert($(cek_m1[0]).val());
            if($(cek_m1[0]).val() == 0){
                $('#option_minggu1').val(0).change();
            }
            
            var cek_m2 = $(".minggu2");
            if($(cek_m2[0]).val() == 0){
                $('#option_minggu2').val(0).change();
            }
            
            var cek_m3 = $(".minggu3");
            if($(cek_m3[0]).val() == 0){
                $('#option_minggu3').val(0).change();
            }
            
            var cek_m4 = $(".minggu4");
            if($(cek_m4[0]).val() == 0){
                $('#option_minggu4').val(0).change();
            }
            
            var cek_m5 = $(".minggu5");
            if($(cek_m5[0]).val() == 0){
                $('#option_minggu5').val(0).change();
            }
        }
        
    });
</script>