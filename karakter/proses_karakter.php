<?php

    include_once '../includes/db_con.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['karakter_id'])){
        $karakter_id = mysqli_real_escape_string($conn, $_POST['karakter_id']);
        
        $query = "SELECT * FROM karakter WHERE karakter_id = $karakter_id";
        $query_guru_info = mysqli_query($conn, $query);

        if(!$query_guru_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_guru_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete Karakter</h4>";
        echo "<label>Nama Karakter</label>";
        echo "<input type='text' rel='".$row['karakter_id']."' class='form-control karakter_nama mb-3' value='".$row['karakter_nama']."'>";
        echo "<label>Karakter Jika A</label>";
        echo "<input type='text' class='form-control karakter_a mb-3' value='".$row['karakter_a']."'>";
        echo "<label>Karakter Jika B</label>";
        echo "<input type='text' class='form-control karakter_b mb-3' value='".$row['karakter_b']."'>";
        echo "<label>Karakter Jika C</label>";
        echo "<input type='text' class='form-control karakter_c mb-3' value='".$row['karakter_c']."'>";
        echo "<label>Urutan Cetak Pada Rapor</label>";
        echo "<input type='number' class='form-control karakter_urutan mb-3' value='".$row['karakter_urutan']."'>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatekarakter'])){
        $karakter_id = mysqli_real_escape_string($conn, $_POST['karakter_id']);
        $karakter_nama = mysqli_real_escape_string($conn, $_POST['karakter_nama']);
        $karakter_a = mysqli_real_escape_string($conn, $_POST['karakter_a']);
        $karakter_b = mysqli_real_escape_string($conn, $_POST['karakter_b']);
        $karakter_c = mysqli_real_escape_string($conn, $_POST['karakter_c']);
        $karakter_urutan = mysqli_real_escape_string($conn, $_POST['karakter_urutan']);
        
        //update database guru
        $query_updateguru = "UPDATE karakter SET karakter_nama = '$karakter_nama', karakter_a = '$karakter_a', karakter_b = '$karakter_b', karakter_c = '$karakter_c', karakter_urutan = $karakter_urutan WHERE karakter_id = $karakter_id";
        $result_setguru = mysqli_query($conn, $query_updateguru);
        
        if(!$result_setguru){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
    
    /************************Set guru menjadi tidak aktif************************/
    if(isset($_POST['deletekarakter'])){
        $karakter_id = mysqli_real_escape_string($conn, $_POST['karakter_id']);
        
//        $query = "UPDATE guru SET guru_active = 0 WHERE guru_id = $guru_id";
//        
//        $result_set = mysqli_query($conn, $query);
//        
//        if(!$result_set){
//            die("QUERY FAILED".mysqli_error($conn));
//        }
    }
?>
<script>
    $(document).ready(function(){
        var karakter_id;
        var karakter_nama;
        var karakter_a;
        var karakter_b;
        var karakter_c;
        var karakter_urutan;
        var updatekarakter = "update";
        var deletekarakter = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            karakter_id =$(".karakter_nama").attr("rel");
            karakter_nama = $(".karakter_nama").val();
            karakter_a = $(".karakter_a").val();
            karakter_b = $(".karakter_b").val();
            karakter_c = $('.karakter_c').val();
            karakter_urutan = $('.karakter_urutan').val();

            //mengirim nilai ke halaman php tujuan
            $.post("karakter/proses_karakter.php",{karakter_id: karakter_id, karakter_nama:karakter_nama, karakter_a:karakter_a, karakter_b:karakter_b, karakter_c:karakter_c, karakter_urutan:karakter_urutan, updatekarakter: updatekarakter}, function(data){
                $("#feedback").text("Data berhasil diupdate");
                //alert(data);
            });
        });
        
        /************************DELETE BUTTON FUNCTION************************/
        $(".delete").on('click', function(){
            if(confirm('Are you sure you want to delete this')){
                karakter_id = $(".karakter_nama").attr('rel');
                $.post("karakter/proses_karakter.php",{karakter_id: karakter_id, deletekarakter: deletekarakter}, function(data){
                    $("#container-karakter").hide();
                });
            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-karakter").hide();
        });
    });
</script>