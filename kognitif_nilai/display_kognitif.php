<?php

    include ("../includes/db_con.php");
    include ("../includes/fungsi_lib.php");
    
    $kelas_id = $_POST['option_kelas'];
    $mapel_id = $_POST['option_mapel'];
    $topik_id = $_POST['option_topik'];
    $resultCheck = -1;
    if($kelas_id > 0 && $mapel_id >0 && $topik_id >0) {

        //cek pernah isi atau belum
        
        
        $query =    "SELECT *
                    from siswa
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN kog_psi 
                    ON siswa_id = kog_psi_siswa_id
                    LEFT JOIN topik
                    ON kog_psi_topik_id = topik_id
                    WHERE siswa_id_kelas = $kelas_id AND topik_id = $topik_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        
        //jika belum pernah isi
        if($resultCheck == 0){
            
            echo"<input type='hidden' id='topik_text' value=$topik_id>";
            echo"<input type='hidden' id='kelas_text' value=$kelas_id>";
            
            $query =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang, siswa_agama
                    FROM siswa
                    WHERE siswa_id_kelas = {$kelas_id}";

            $query_afektif_info = mysqli_query($conn, $query);
            
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai TEKAN SAVE untuk menyimpan nilai
                </div>';
            
            echo return_info_warna_agama();

            echo"<div style='overflow-x:auto;'>
                <table class='table table-sm table-responsive table-bordered mt-3'><thead>
                  <tr>
                    <th colspan='2'></th>
                    <th colspan='3'>Kognitif</th>
                    <th colspan='3'>Keterampilan</th>
                  </tr>
                  <tr>
                    <th colspan='2'>Persentase</th>
                    ";
                        echo "<td><select id='option_kquiz' class='1-100'></select></td>";
                        echo "<td><select id='option_ktest' class='1-100'></select></td>";
                        echo "<td><select id='option_kass' class='1-100'></select></td>";
                        echo "<td><select id='option_pquiz' class='1-100'></select></td>";
                        echo "<td><select id='option_ptest' class='1-100'></select></td>";
                        echo "<td><select id='option_pass' class='1-100'></select></td>";
            
            echo'</tr>
                
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Quiz</th>
                    <th>Test</th>
                    <th>Ass</th>
                    <th>Quiz</th>
                    <th>Test</th>
                    <th>Ass</th>
                  </tr>
            </thead>
            <tbody>';
                $absen = 1;
                while($row = mysqli_fetch_array($query_afektif_info)){
                    $nama_belakang = $row['siswa_nama_belakang'];
                    echo '<tr class="'.return_warna_tabel_agama($row['siswa_agama']).'">';
                    echo'<td>';
                    echo"{$absen}</td>";
                    echo'<td>';
                    
                    if(strlen($nama_belakang) > 0){
                        echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                    }else{
                        echo"{$row['siswa_nama_depan']}</td>";
                    }
                    
                    echo "<div id = 'input_afek'>";
                    //KOGNITIF
                    
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='kin' id='{$row['siswa_id']},1' value='0' min='-99' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='kin2' id='{$row['siswa_id']},2' value='0' min=-99 max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='kin3' id='{$row['siswa_id']},3' value='0' min='-99' max='100'></td>";
                 
                        
                    //PSIKOMOTOR
                    
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='pin' id='{$row['siswa_id']},4' value='0' min='-99' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='pin2' id='{$row['siswa_id']},5' value='0' min='-99' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='pin3' id='{$row['siswa_id']},6' value='0' min='-99' max='100'></td>";
                        
                    echo "</div>";
                    echo '</tr>';
                    
                    $absen++;
                }
            echo'</tbody></table></div>';
            
            
            echo"<input type='hidden' id='status' value='baru'>";
            
            echo '<input type = "button" id="proses_nilai" value="SAVE" class="btn btn-success mt-2">';
        }
        elseif($resultCheck > 1){
            $query_cek_rev =    "SELECT *
                                FROM kog_psi_revisi
                                LEFT JOIN kog_psi
                                ON kog_psi_id_fk = kog_psi_id
                                LEFT JOIN siswa
                                ON kog_psi_siswa_id = siswa_id
                                WHERE kog_psi_topik_id = {$topik_id} AND siswa_id_kelas = {$kelas_id} AND rev_status = 0";
            $query_jumlah_baris_rev = mysqli_query($conn, $query_cek_rev);
            $resultrev = mysqli_num_rows($query_jumlah_baris_rev);
            
            if($resultrev > 0){
                echo "<h4 class='text-center bg-danger'>Pengajuan revisi masih diproses</h2>";
            }elseif($resultrev == 0){
                
                //sudah pernah isi nilai
                echo '<div class="alert alert-success alert-dismissible fade show">
                        <button class="close" data-dismiss="alert" type="button">
                            <span>&times;</span>
                        </button>
                        <strong>PERHATIAN:</strong> Proses UPDATE nilai harus disetujui wakakur
                    </div>';

                echo return_info_warna_agama();

                $query_afektif_info = mysqli_query($conn, $query);

                echo"<div style='overflow-x:auto;'>
                    <table class='table table-sm table-responsive table-bordered mt-3'><thead>
                      <tr>
                        <th colspan='2'></th>
                        <th colspan='3'>Kognitif</th>
                        <th colspan='3'>Keterampilan</th>
                      </tr>
                      <tr>
                        <th colspan='2'>Persentase</th>
                        ";
                            echo "<td><select id='option_kquiz' class='1-100'></select></td>";
                            echo "<td><select id='option_ktest' class='1-100'></select></td>";
                            echo "<td><select id='option_kass' class='1-100'></select></td>";
                            echo "<td><select id='option_pquiz' class='1-100'></select></td>";
                            echo "<td><select id='option_ptest' class='1-100'></select></td>";
                            echo "<td><select id='option_pass' class='1-100'></select></td>";

                echo'</tr>

                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Quiz</th>
                        <th>Test</th>
                        <th>Ass</th>
                        <th>Quiz</th>
                        <th>Test</th>
                        <th>Ass</th>
                      </tr>
                </thead>
                <tbody>';
                $absen = 1;
                    while($row = mysqli_fetch_array($query_afektif_info)){
                        $nama_belakang = $row['siswa_nama_belakang'];
                        $temp_kq = $row['kog_quiz_persen'];
                        $temp_kt = $row['kog_test_persen'];
                        $temp_ka = $row['kog_ass_persen'];
                        $temp_pq = $row['psi_quiz_persen'];
                        $temp_pt = $row['psi_test_persen'];
                        $temp_pa = $row['psi_ass_persen'];

                        echo '<tr class="'.return_warna_tabel_agama($row['siswa_agama']).'">';
                        echo'<td>';
                        echo"{$absen}</td>";
                        echo'<td>';
                        if(strlen($nama_belakang) > 0){
                            echo"{$row['siswa_nama_depan']} $nama_belakang[0]</td>";
                        }
                        else{
                            echo"{$row['siswa_nama_depan']}</td>";
                        }
                        

                        echo "<div id = 'input_afek'>";
                        //KOGNITIF

                            echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='kin' id='{$row['kog_psi_id']},1' value='{$row['kog_quiz']}' min='-99' max='100'></td>";
                            echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='kin2' id='{$row['kog_psi_id']},2' value='{$row['kog_test']}' min='-99' max='100'></td>";
                            echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='kin3' id='{$row['kog_psi_id']},3' value='{$row['kog_ass']}' min='-99' max='100'></td>";


                        //PSIKOMOTOR

                            echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='pin' id='{$row['kog_psi_id']},4' value='{$row['psi_quiz']}' min='-99' max='100'></td>";
                            echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='pin2' id='{$row['kog_psi_id']},5' value='{$row['psi_test']}' min='-99' max='100'></td>";
                            echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' name='{$row['siswa_id']}' class='pin3' id='{$row['kog_psi_id']},6' value='{$row['psi_ass']}' min='-99' max='100'></td>";

                        echo "</div>";
                        echo '</tr>';
                        $absen++;
                    }
                echo'</tbody></table></div>';

                echo"<input type='hidden' id='temp_kq' value=$temp_kq>";
                echo"<input type='hidden' id='temp_kt' value=$temp_kt>";
                echo"<input type='hidden' id='temp_ka' value=$temp_ka>";
                echo"<input type='hidden' id='temp_pq' value=$temp_pq>";
                echo"<input type='hidden' id='temp_pt' value=$temp_pt>";
                echo"<input type='hidden' id='temp_pa' value=$temp_pa>";

                //untuk keperluan update nilai
                echo"<input type='hidden' id='topik_text2' value=$topik_id>";
                echo"<input type='hidden' id='kelas_text2' value=$kelas_id>";
                echo"<input type='hidden' id='mapel_text2' value=$mapel_id>";

                echo'<label><b>Alasan Update:</b></label>
                      <input type="text" name="alasan_update" id="alasan_update" placeholder="Masukkan Alasan Update (misal: remidial, salah input)" class="form-control form-control-sm mb-2" required>
                      ';

                echo '<input type = "button" id="proses_update" value="UPDATE" class="btn btn-success mt-2">';
            }
            
        }

        
    }
?>

<script>
        
    $(document).ready(function(){
        
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
                
                if( !$(this).val() ) { 
                    $(this).val(0);    
                }
            });
        }); 
        
        $('.kin').keydown(function (e) {
         if (e.which === 13) {
             var index = $('.kin').index(this) + 1;
             $('.kin').eq(index).focus();
         }
        });
        
        $('.kin2').keydown(function (e) {
         if (e.which === 13) {
             var index = $('.kin2').index(this) + 1;
             $('.kin2').eq(index).focus();
         }
        });
        
        $('.kin3').keydown(function (e) {
         if (e.which === 13) {
             var index = $('.kin3').index(this) + 1;
             $('.kin3').eq(index).focus();
         }
        });
        
        $('.pin').keydown(function (e) {
         if (e.which === 13) {
             var index = $('.pin').index(this) + 1;
             $('.pin').eq(index).focus();
         }
        });
        
        $('.pin2').keydown(function (e) {
         if (e.which === 13) {
             var index = $('.pin2').index(this) + 1;
             $('.pin2').eq(index).focus();
         }
        });
        
        $('.pin3').keydown(function (e) {
         if (e.which === 13) {
             var index = $('.pin3').index(this) + 1;
             $('.pin3').eq(index).focus();
         }
        });
        
        $(function(){
            var $select = $(".1-100");
            for (i=0;i<=100;i++){
                $select.append($('<option></option>').val(i).html(i))
            }
            
            var status;
            status = $('#status').val();
            
            var temp_kq = $('#temp_kq').val();
            var temp_kt = $('#temp_kt').val();
            var temp_ka = $('#temp_ka').val();
            var temp_pq = $('#temp_pq').val();
            var temp_pt = $('#temp_pt').val();
            var temp_pa = $('#temp_pa').val();
            
            if(status != 'baru'){
                $("#option_kquiz").val(temp_kq).change();
                $("#option_ktest").val(temp_kt).change();
                $("#option_kass").val(temp_ka).change();
                $("#option_pquiz").val(temp_pq).change();
                $("#option_ptest").val(temp_pt).change();
                $("#option_pass").val(temp_pa).change();
            }
            
        });
        
        
        $("#proses_nilai").click(function () {
            $("#proses_nilai").attr("disabled", true);
            
            var topik_id = $('#topik_text').val();
            var kelas_id = $('#kelas_text').val();
            
            var arr = [];
            var afek = [];
            var kq = [];
            var kt = [];
            var ka = [];
            var pq = [];
            var pt = [];
            var pa = [];
            var siswa_id = [];
            var insertkp = "insertkp";
            $('input[type=number]').each(function(){
               //pisahkan nama dan masukkan kedalam arr 
               var pisah = $(this).attr("id").split(',');
               
               var siswa_id = pisah[0];
               var nilai_ke = $(this).val();
               var jenis_nilai = pisah[1];
               //0 = siswa_id, 2 = jenis_nilai(1=kquiz, 2=ktest, 3=kass, 4=pquiz, 5=pquiz, 6=pquiz, 7=pquiz) , 1 = nilai value
               arr.push([siswa_id, nilai_ke, jenis_nilai]); 
                 
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
                    
                    //nilai berbeda minggu tandai dengan /
                    str_nilai += '/';
                        
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
                
            }
//            console.log(siswa_id);
//            console.log(afek);
            for (var i = 0; i < afek.length; i++)
            {
                var pisah2 = afek[i].split('/');
                kq.push(pisah2[0]);
                kt.push(pisah2[1]);
                ka.push(pisah2[2]);
                pq.push(pisah2[3]);
                pt.push(pisah2[4]);
                pa.push(pisah2[5]);
            }
//            console.log(kq);
//            console.log(kt);
//            console.log(ka);
//            console.log(pq);
//            console.log(pt);
//            console.log(pa);
            var persen_kquiz = $('#option_kquiz').val();
            var persen_ktest = $('#option_ktest').val();
            var persen_kass = $('#option_kass').val();
            var persen_pquiz = $('#option_pquiz').val();
            var persen_ptest = $('#option_ptest').val();
            var persen_pass = $('#option_pass').val();
            
            var total_persen_kog = parseInt(persen_kquiz) + parseInt(persen_ktest) + parseInt(persen_kass);
            var total_persen_psi = parseInt(persen_pquiz) + parseInt(persen_ptest) + parseInt(persen_pass);
            
            //cek persentase
            if (total_persen_kog != 100 || total_persen_psi != 100){
                alert("Total Persentase harus 100");
                $("#proses_nilai").attr("disabled", false);
            }
            else{
                $.post("kognitif_nilai/proses_kognitif.php",{siswa_id:siswa_id, kelas_id:kelas_id, topik_id:topik_id, persen_kquiz: persen_kquiz, persen_ktest: persen_ktest, persen_kass:persen_kass, persen_pquiz:persen_pquiz, persen_ptest:persen_ptest, persen_pass:persen_pass, kq:kq, kt:kt, ka:ka, pq:pq, pt:pt, pa:pa, insertkp:insertkp}, function(data){
                    $("#container-temp2").html(data);
                    if(data){
                        $("#proses_nilai").attr("disabled", false);
                    }
                    $("#kotak").hide();
                });
            }
            
        });
        
        
        $("#proses_update").click(function () {
            $("#proses_update").attr("disabled", true);
            
            var topik_id = $('#topik_text2').val();
            var mapel_id = $('#mapel_text2').val();
            var kelas_id = $('#kelas_text2').val();
            var alasan_update = $('#alasan_update').val();
            
            var arr = [];
            var afek = [];
            var kq = [];
            var kt = [];
            var ka = [];
            var pq = [];
            var pt = [];
            var pa = [];
            var siswa_id = [];
            var updatekp = "updatekp";
            $('input[type=number]').each(function(){
               //pisahkan nama dan masukkan kedalam arr 
               var pisah = $(this).attr("id").split(',');
               
               var siswa_id = pisah[0];
               var nilai_ke = $(this).val();
               var jenis_nilai = pisah[1];
               //0 = siswa_id, 2 = jenis_nilai(1=kquiz, 2=ktest, 3=kass, 4=pquiz, 5=pquiz, 6=pquiz, 7=pquiz) , 1 = nilai value
               arr.push([siswa_id, nilai_ke, jenis_nilai]); 
                 
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
                    
                    //nilai berbeda minggu tandai dengan /
                    str_nilai += '/';
                        
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
                
            }
//            console.log(siswa_id);
//            console.log(afek);
            for (var i = 0; i < afek.length; i++)
            {
                var pisah2 = afek[i].split('/');
                kq.push(pisah2[0]);
                kt.push(pisah2[1]);
                ka.push(pisah2[2]);
                pq.push(pisah2[3]);
                pt.push(pisah2[4]);
                pa.push(pisah2[5]);
            }
//            console.log(kq);
//            console.log(kt);
//            console.log(ka);
//            console.log(pq);
//            console.log(pt);
//            console.log(pa);
            var persen_kquiz = $('#option_kquiz').val();
            var persen_ktest = $('#option_ktest').val();
            var persen_kass = $('#option_kass').val();
            var persen_pquiz = $('#option_pquiz').val();
            var persen_ptest = $('#option_ptest').val();
            var persen_pass = $('#option_pass').val();
            
            var total_persen_kogu = parseInt(persen_kquiz) + parseInt(persen_ktest) + parseInt(persen_kass);
            var total_persen_psiu = parseInt(persen_pquiz) + parseInt(persen_ptest) + parseInt(persen_pass);
            
            if (total_persen_kogu != 100 || total_persen_psiu != 100){
                alert("Total Persentase harus 100");
                $("#proses_update").attr("disabled", false);
            }else{
                if(alasan_update){
                    $.post("kognitif_nilai/proses_kognitif.php",{alasan_update:alasan_update,mapel_id:mapel_id,kelas_id:kelas_id,siswa_id:siswa_id, topik_id:topik_id, persen_kquiz: persen_kquiz, persen_ktest: persen_ktest, persen_kass:persen_kass, persen_pquiz:persen_pquiz, persen_ptest:persen_ptest, persen_pass:persen_pass, kq:kq, kt:kt, ka:ka, pq:pq, pt:pt, pa:pa, updatekp:updatekp}, function(data){
                        $("#container-temp2").html(data);
                        if(data){
                            $("#proses_update").attr("disabled", false);
                        }
                        $("#kotak").hide();
                    });
                }else{
                    alert("Masukkan alasan update");
                    $("#proses_update").attr("disabled", false);
                }
            }
            
        });
        
        
    });
</script>