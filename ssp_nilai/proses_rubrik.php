<?php

    include_once '../includes/db_con.php';
    include_once '../includes/fungsi_lib.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['d_ssp_id'])){
        echo return_alert("Rubrik hanya dapat dihapus jika tidak mempunyai nilai", "warning");
        $d_ssp_id = mysqli_real_escape_string($conn, $_POST['d_ssp_id']);
        
        $query = "SELECT * FROM d_ssp WHERE d_ssp_id = {$d_ssp_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        $d_ssp_a = mysqli_real_escape_string($conn, $row['d_ssp_a']);
        $d_ssp_b = mysqli_real_escape_string($conn, $row['d_ssp_b']);
        $d_ssp_c = mysqli_real_escape_string($conn, $row['d_ssp_c']);
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete Rubrik</h4>";
        echo "<input type='text' placeholder='Masukkan nama rubrik' rel='".$row['d_ssp_id']."' id='d_ssp_kriteria' class='form-control d_ssp_kriteria mb-3' value='".$row['d_ssp_kriteria']."'>";
        echo "<input type='text' placeholder='Masukkan kalimat jika nilai rubrik A' id='d_ssp_a' class='form-control d_ssp_a mb-3' value='".$d_ssp_a."'>";
        echo "<input type='text' placeholder='Masukkan kalimat jika nilai rubrik B' id='d_ssp_b' class='form-control d_ssp_b mb-3' value='".$d_ssp_b."'>";
        echo "<input type='text' placeholder='Masukkan kalimat jika nilai rubrik C' id='d_ssp_c' class='form-control d_ssp_c mb-3' value='".$d_ssp_c."'>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
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
    if(isset($_POST['deleterubrik'])){
        //echo "hai";
        $d_ssp_id = mysqli_real_escape_string($conn, $_POST['d_ssp_id']);
        
        $query =    "SELECT *
                    FROM ssp_nilai
                    WHERE ssp_nilai_d_ssp_id = $d_ssp_id";

        $result = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck == 0){
            $query2 = "DELETE FROM d_ssp WHERE d_ssp_id = $d_ssp_id";
        
            $result_set = mysqli_query($conn, $query2);
            
            if(!$result_set){
                die("QUERY FAILED".mysqli_error($conn));
            }
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
        var deleterubrik = "delete";
        
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
            if(confirm('Yakin ingin mencoba menghapus rubrik ini?')){
                d_ssp_id =$("#d_ssp_kriteria").attr("rel");
                $.post("ssp_nilai/proses_rubrik.php",{d_ssp_id: d_ssp_id, deleterubrik: deleterubrik}, function(data){
                    
                    $("#container-rubrik").hide();
                });
            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-rubrik").hide();
        });
    });
</script>