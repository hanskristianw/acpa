<?php

    include ("../includes/db_con.php");
    
    $kelas_id = $_POST['option_kelas'];
    $mapel_id = $_POST['option_mapel'];
    //$topik_id = $_POST['option_topik'];
    $resultCheck = -1;
    if($kelas_id > 0 && $mapel_id >0) {

        //cek pernah isi atau belum
        
        $query =    "SELECT *
                    from siswa
                    LEFT JOIN kelas 
                    ON siswa_id_kelas = kelas_id
                    LEFT JOIN kog_psi_ujian 
                    ON siswa_id = kog_psi_ujian_siswa_id
                    WHERE siswa_id_kelas = $kelas_id AND kog_psi_ujian_mapel_id = $mapel_id";

        $query_afektif_info = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($query_afektif_info);
    
        if($resultCheck == 0){
            //jika belum pernah isi
            echo"<input type='hidden' id='mapel_text' value=$mapel_id>";
            echo"<input type='hidden' id='kelas_text' value=$kelas_id>";
            
            $query =    "SELECT siswa_id, siswa_no_induk, siswa_nama_depan, siswa_nama_belakang
                    FROM siswa
                    WHERE siswa_id_kelas = {$kelas_id}";

            $query_afektif_info = mysqli_query($conn, $query);
            
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button class="close" data-dismiss="alert" type="button">
                        <span>&times;</span>
                    </button>
                    <strong>PERHATIAN:</strong> Anda BELUM mempunyai nilai TEKAN SAVE untuk menyimpan nilai
                </div>';
            
            echo"<table class='table table-responsive table-sm table-striped table-bordered mt-3'><thead>
                  <tr>
                    <th colspan='2'></th>
                    <th colspan='2'>Kognitif</th>
                    <th colspan='2'>Ketrampilan</th>
                  </tr>
                  <tr>
                    <th colspan='2'>Persentase</th>
                    ";
                        echo "<td><select id='option_kmid' class='1-100'></select></td>";
                        echo "<td><select id='option_kfinal' class='1-100'></select></td>";
                        echo "<td><select id='option_pmid' class='1-100'></select></td>";
                        echo "<td><select id='option_pfinal' class='1-100'></select></td>";
            
            echo'</tr>
                
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Mid</th>
                    <th>Final</th>
                    <th>Mid</th>
                    <th>Final</th>
                  </tr>
            </thead>
            <tbody>';
            $absen = 1;
                while($row = mysqli_fetch_array($query_afektif_info)){
                    $nama_belakang = $row['siswa_nama_belakang'];
                    
                    echo '<tr>';
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
                    
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='kin' name='{$row['siswa_id']}' id='{$row['siswa_id']},1' value='0' min='1' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='kin2' name='{$row['siswa_id']}' id='{$row['siswa_id']},2' value='0' min='1' max='100'></td>";
                        
                    //PSIKOMOTOR
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='pin' name='{$row['siswa_id']}' id='{$row['siswa_id']},3' value='0' min='1' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='pin2' name='{$row['siswa_id']}' id='{$row['siswa_id']},4' value='0' min='1' max='100'></td>";
                        
                    echo "</div>";
                    echo '</tr>';
                    $absen++;
                }
            echo'</tbody></table>';
            
            
            echo"<input type='hidden' id='status' value='baru'>";
            
            echo '<input type = "button" id="proses_nilai" value="SAVE" class="btn btn-success mt-2">';
        }
        elseif($resultCheck > 1){
            //sudah pernah isi
            
            $query_cek_rev =    "SELECT *
                                FROM kog_psi_ujian_revisi
                                WHERE ujian_rev_mapel_id = {$mapel_id} AND ujian_rev_kelas_id = {$kelas_id} AND ujian_rev_status = 0";
            $query_jumlah_baris_rev = mysqli_query($conn, $query_cek_rev);
            $resultrev = mysqli_num_rows($query_jumlah_baris_rev);
            
            if($resultrev>0){
                echo "<h4 class='text-center bg-danger'>Pengajuan revisi masih diproses</h2>";
            }else{
                echo '<div class="alert alert-success alert-dismissible fade show">
                <button class="close" data-dismiss="alert" type="button">
                    <span>&times;</span>
                </button>
                <strong>PERHATIAN:</strong> Proses update nilai harus disetujui wakasek kurikulum.
                </div>';
            
                $query_afektif_info = mysqli_query($conn, $query);

                echo"<table class='table table-sm table-responsive table-striped table-bordered mt-3'><thead>
                      <tr>
                        <th colspan='2'></th>
                        <th colspan='2'>Kognitif</th>
                        <th colspan='2'>Ketrampilan</th>
                      </tr>
                      <tr>
                        <th colspan='2'>Persentase</th>
                        ";
                            echo "<td><select id='option_kmid' class='1-100'></select></td>";
                            echo "<td><select id='option_kfinal' class='1-100'></select></td>";
                            echo "<td><select id='option_pmid' class='1-100'></select></td>";
                            echo "<td><select id='option_pfinal' class='1-100'></select></td>";

                echo'</tr>

                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Mid</th>
                        <th>Final</th>
                        <th>Mid</th>
                        <th>Final</th>
                      </tr>
                </thead>
                <tbody>';
                $absen = 1;
                while($row = mysqli_fetch_array($query_afektif_info)){
                    
                    $nama_belakang = $row['siswa_nama_belakang'];
                    
                    $temp_kmid = $row['kog_uts_persen'];
                    $temp_kfinal = $row['kog_uas_persen'];
                    $temp_pmid = $row['psi_uts_persen'];
                    $temp_pfinal = $row['psi_uas_persen'];
                    
                    echo '<tr>';
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
                    
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='kin' name='{$row['siswa_id']}' id='{$row['kog_psi_ujian_id']},1' value='{$row['kog_uts']}' min='-99' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='kin2' name='{$row['siswa_id']}' id='{$row['kog_psi_ujian_id']},2' value='{$row['kog_uas']}' min='-99' max='100'></td>";
                 
                        
                    //PSIKOMOTOR
                    
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='pin' name='{$row['siswa_id']}' id='{$row['kog_psi_ujian_id']},4' value='{$row['psi_uts']}' min='-99' max='100'></td>";
                        echo "<td><input type = 'number' onfocus='this.select();' required style='width: 44px;' class='pin2' name='{$row['siswa_id']}' id='{$row['kog_psi_ujian_id']},5' value='{$row['psi_uas']}' min='-99' max='100'></td>";
                        
                    echo "</div>";
                    echo '</tr>';
                    $absen++;
                }
                echo'</tbody></table>';

                echo"<input type='hidden' id='temp_kmid' value=$temp_kmid>";
                echo"<input type='hidden' id='temp_kfinal' value=$temp_kfinal>";
                echo"<input type='hidden' id='temp_pmid' value=$temp_pmid>";
                echo"<input type='hidden' id='temp_pfinal' value=$temp_pfinal>";

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
        
        $(function(){
            var $select = $(".1-100");
            for (i=0;i<=100;i++){
                $select.append($('<option></option>').val(i).html(i))
            }
            
            var status;
            status = $('#status').val();
            
            var temp_kmid = $('#temp_kmid').val();
            var temp_kfinal = $('#temp_kfinal').val();
            var temp_pmid = $('#temp_pmid').val();
            var temp_pfinal = $('#temp_pfinal').val();
            
            if(status != 'baru'){
                $("#option_kmid").val(temp_kmid).change();
                $("#option_kfinal").val(temp_kfinal).change();
                $("#option_pmid").val(temp_pmid).change();
                $("#option_pfinal").val(temp_pfinal).change();
            }
            
        });
        
        
        $("#proses_nilai").click(function () {
            $("#proses_nilai").attr("disabled", true);
            var mapel_id = $('#mapel_text').val();
            var kelas_id = $('#kelas_text').val();
            
            var arr = [];
            var afek = [];
            var kq = [];
            var kt = [];
            var ka = [];
            var pq = [];
            
            var siswa_id = [];
            var insertkpu = "insertkpu";
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
//                pt.push(pisah2[4]);
//                pa.push(pisah2[5]);
            }
            
            var persen_kmid = $('#option_kmid').val();
            var persen_kfinal = $('#option_kfinal').val();
            var persen_pmid = $('#option_pmid').val();
            var persen_pfinal = $('#option_pfinal').val();
            
            
            var total_persen_kog = parseInt(persen_kmid) + parseInt(persen_kfinal);
            var total_persen_psi = parseInt(persen_pmid) + parseInt(persen_pfinal);
            
            if (total_persen_kog != 100 && total_persen_psi != 100){
                alert("Total Persentase harus 100");
            }
            else{
                $.post("uts_uas/proses_uts_uas.php",{siswa_id:siswa_id, kelas_id:kelas_id, persen_kmid: persen_kmid, persen_kfinal: persen_kfinal, persen_pmid:persen_pmid, persen_pfinal:persen_pfinal, kog_uts:kq, kog_uas:kt, psi_uts:ka, psi_uas:pq, mapel_id:mapel_id, insertkpu:insertkpu}, function(data){
                //alert(data);
                $("#container-temp").show();
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
            var mapel_id = $('#option_mapel').val();
            var kelas_id = $('#option_kelas').val();
            
            var arr = [];
            var afek = [];
            var kq = [];
            var kt = [];
            var ka = [];
            var pq = [];
//            var pt = [];
//            var pa = [];
            var siswa_id = [];
            var updatekpu = "updatekpu";
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
                //pt.push(pisah2[4]);
                //pa.push(pisah2[5]);
            }
//            console.log(kq);
//            console.log(kt);
//            console.log(ka);
//            console.log(pq);
//            console.log(pt);
//            console.log(pa);
            var persen_kmid = $('#option_kmid').val();
            var persen_kfinal = $('#option_kfinal').val();
            var persen_pmid = $('#option_pmid').val();
            var persen_pfinal = $('#option_pfinal').val();
            var alasan_update = $('#alasan_update').val();
            
            //alert(alasan_update);

            var total_persen_kog = parseInt(persen_kmid) + parseInt(persen_kfinal);
            var total_persen_psi = parseInt(persen_pmid) + parseInt(persen_pfinal);
            
            if (total_persen_kog != 100 && total_persen_psi != 100){
                alert("Total Persentase harus 100");
            }else{
                if(alasan_update){
                $.post("uts_uas/proses_uts_uas.php",{kelas_id:kelas_id, alasan_update:alasan_update, siswa_id:siswa_id, mapel_id:mapel_id, persen_kmid: persen_kmid, persen_kfinal: persen_kfinal, persen_pmid:persen_pmid, persen_pfinal:persen_pfinal, kog_mid:kq, kog_final:kt, psi_mid:ka, psi_final:pq, updatekpu:updatekpu}, function(data){
                    $("#container-temp2").html(data);
                    if(data){
                        $("#proses_update").attr("disabled", false);
                    }
                    $("#kotak").hide();
                });
                }
                else{
                    alert("Masukkan alasan update");
                }
            }
            
        });
        
        
    });
</script>