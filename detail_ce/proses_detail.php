<?php

    include_once '../includes/db_con.php';
    include_once '../includes/fungsi_lib.php';
    //**********Displaying data ketika user menekan nama user
    if(isset($_POST['d_ce_id'])){
        $d_ce_id = mysqli_real_escape_string($conn, $_POST['d_ce_id']);
        
        $query = "SELECT * FROM d_ce WHERE d_ce_id = {$d_ce_id}";
        $query_ssp_info = mysqli_query($conn, $query);

        if(!$query_ssp_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        $row = mysqli_fetch_array($query_ssp_info);
        
        //container untuk menampilkan pesan sukses
        echo'<p id="feedback" class="bg-success"></p>';
        
        echo return_alert("Indikator yang sudah punya nilai TIDAK DAPAT dihapus", "danger");

        //menampilkan textbox ketika nama user diklik
        echo "<h4 class='mb-3'>Update/Delete INDIKATOR</h4>";
        echo "<input type='text' placeholder='Masukkan aspek CE' rel='".$row['d_ce_id']."' class='form-control ce_nama mb-3' value='".$row['d_ce_nama']."'>";
        
        //menampilkan 3 button
        echo"<input type='button' class='btn btn-success mr-3 update' value='Update'>";
        echo"<input type='button' class='btn btn-danger mr-3 delete' value='Delete'>";
        echo"<input type='button' class='btn btn-close' value='Close'>";
    }
    //**********Update data ketika user menekan tombol update
    if(isset($_POST['updatessp'])){
        $d_ce_id = mysqli_real_escape_string($conn, $_POST['d_ce_id']);
        $d_ce_nama = mysqli_real_escape_string($conn, $_POST['d_ce_nama']);
        
        $query_updatessp = "UPDATE d_ce SET d_ce_nama = '$d_ce_nama' WHERE d_ce_id = $d_ce_id";
        $result_setssp = mysqli_query($conn, $query_updatessp);
        
        if(!$result_setssp){
            die("QUERY FAILED".mysqli_error($conn));
        }
    }
    
    /************************Set guru menjadi tidak aktif************************/
    if(isset($_POST['deleteguru'])){
        $d_ce_id = mysqli_real_escape_string($conn, $_POST['d_ce_id']);
        
        $query =    "SELECT *
                    FROM ce_nilai
                    WHERE ce_nilai_d_ce_id = $d_ce_id";

        $result = mysqli_query($conn, $query);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck == 0){
            $query2 = "DELETE FROM d_ce WHERE d_ce_id = $d_ce_id";
        
            $result_set = mysqli_query($conn, $query2);
            
            if(!$result_set){
                die("QUERY FAILED".mysqli_error($conn));
            }
        }
    }
?>
<script>
    $(document).ready(function(){
        var d_ce_id;
        var d_ce_nama;
        var updatessp = "update";
        var deleteguru = "delete";
        
        /************************UPDATE BUTTON FUNCTION************************/
        $(".update").on('click', function(){
            //mengambil nilai yang dibutuhkan untuk update dari textbox dan combobox
            d_ce_id =$(".ce_nama").attr("rel");
            d_ce_nama =$(".ce_nama").val();


            if ($.trim(d_ce_nama).length === 0){
                // string is invalid
                alert("Isi nama ssp dengan benar!");
            }else{
                //mengirim nilai ke halaman php tujuan
                $.post("detail_ce/proses_detail.php",{d_ce_id: d_ce_id, d_ce_nama:d_ce_nama, updatessp: updatessp}, function(data){
                    $("#feedback").text("Data berhasil diupdate");
                    $('#option_tema_ce2').val(0).change();
                });
            }
            
        });
        
        /************************DELETE BUTTON FUNCTION************************/
        $(".delete").on('click', function(){
            if(confirm('Yakin ingin mencoba menghapus indikator ini?')){
                d_ce_id =$(".ce_nama").attr("rel");
                $.post("detail_ce/proses_detail.php",{d_ce_id: d_ce_id, deleteguru: deleteguru}, function(data){
                    var ce_id = $("#option_tema_ce2").val();
                    $.ajax({
                        url: "detail_ce/display_detail.php",
                        data:'ce_id='+ ce_id,
                        type: "POST",
                        success:function(data){
                            $("#show_ssp").html(data);
                        }
                    });
                    $("#container-ssp").hide();
                });
            }
        });
        
        /************************CLOSE BUTTON FUNCTION************************/
        //jika button close diklik maka container akan ditutup
        $(".btn-close").on('click', function(){
                $("#container-ssp").hide();
        });
    });
</script>