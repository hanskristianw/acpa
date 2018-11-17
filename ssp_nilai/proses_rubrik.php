<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['d_ssp_id'])){
        $d_ssp_id = mysqli_real_escape_string($conn, $_POST['d_ssp_id']);
        
        $query = "SELECT * FROM d_ssp WHERE d_ssp_id = {$d_ssp_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete Rubrik</h4>";
        echo "<input type='text' placeholder='Masukkan nama rubrik' rel='".$row['d_ssp_id']."' id='d_ssp_kriteria' class='form-control d_ssp_kriteria mb-3' value='".$row['d_ssp_kriteria']."'>";
        echo "<input type='text' placeholder='Masukkan kalimat jika nilai rubrik A' id='d_ssp_a' class='form-control d_ssp_a mb-3' value='".$row['d_ssp_a']."'>";
        echo "<input type='text' placeholder='Masukkan kalimat jika nilai rubrik B' id='d_ssp_b' class='form-control d_ssp_b mb-3' value='".$row['d_ssp_b']."'>";
        echo "<input type='text' placeholder='Masukkan kalimat jika nilai rubrik C' id='d_ssp_c' class='form-control d_ssp_c mb-3' value='".$row['d_ssp_c']."'>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        //echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updaterubrik'])){
        $d_ssp_id = mysqli_real_escape_string($conn, $_POST['d_ssp_id']);
        $d_ssp_kriteria = mysqli_real_escape_string($conn, $_POST['d_ssp_kriteria']);
        $d_ssp_a = mysqli_real_escape_string($conn, $_POST['d_ssp_a']);
        $d_ssp_b = mysqli_real_escape_string($conn, $_POST['d_ssp_b']);
        $d_ssp_c = mysqli_real_escape_string($conn, $_POST['d_ssp_c']);
        
        $query_updaterubrik = "UPDATE d_ssp SET d_ssp_kriteria = '$d_ssp_kriteria', d_ssp_a = '$d_ssp_a', d_ssp_b = '$d_ssp_b', d_ssp_c = '$d_ssp_c' WHERE d_ssp_id = $d_ssp_id";
        $result_setrubrik = mysqli_query($conn, $query_updaterubrik);
        
        if(!$result_setrubrik){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
    
    /************************Set guru menjadi tidak aktif************************/
    if(isset($_POST['deleteguru'])){
        $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
        
        $query = "UPDATE guru SET guru_active = 0 WHERE guru_id = $guru_id";
        
        $result_set = mysqli_query($conn, $query);
        
        if(!$result_set){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
?>
<script>
    $(document).ready(function(){
        var d_ssp_id;
        var d_ssp_a;
        var d_ssp_b;
        var d_ssp_c;
        var d_ssp_kriteria;
        var updaterubrik = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            d_ssp_id =$("#d_ssp_kriteria").attr("rel");
            d_ssp_kriteria =$("#d_ssp_kriteria").val();
            d_ssp_a =$("#d_ssp_a").val();
            d_ssp_b =$("#d_ssp_b").val();
            d_ssp_c =$("#d_ssp_c").val();

//            alert(d_ssp_id);
//            alert(d_ssp_kriteria);
            if ($.trim(d_ssp_kriteria).length === 0){
                // string is invalid
                alert("Isi nama rubrik dengan benar!");
            }else{
                //mengirim nilai ke halaman php tujuan
                $.post("ssp_nilai/proses_rubrik.php",{d_ssp_id: d_ssp_id, d_ssp_kriteria:d_ssp_kriteria, d_ssp_a:d_ssp_a, d_ssp_b:d_ssp_b, d_ssp_c:d_ssp_c, updaterubrik:updaterubrik}, function(data){
                    $("#feedback").text("Data berhasil diupdate");
                    //alert(data);
                });
            }
            
        });
        
        /************************DELETE BUTTON FUNCTION************************/
        $(".delete").on('click', function(){
//            if(confirm('Are you sure you want to delete this')){
//                guru_id = $(".guru_name").attr('rel');
//                $.post("guru/proses_guru.php",{guru_id: guru_id, deleteguru: deleteguru}, function(data){
//                    $("#container-guru").hide();
//                });
//            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-rubrik").hide();
        });
    });
</script>