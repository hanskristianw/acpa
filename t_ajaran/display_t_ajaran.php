<?php

    include ("../includes/db_con.php");

    $query = "SELECT * FROM t_ajaran where t_ajaran_active = 1";
    $query_t_ajaran_info = mysqli_query($conn, $query);

    if(!$query_t_ajaran_info){
        die("QUERY FAILED".mysqli_error($conn));
    }
    
    //tampilkan tabel pada container
    while($row = mysqli_fetch_array($query_t_ajaran_info)){
        echo"<h4>Tahun ajaran yang aktif adalah: <b>{$row['t_ajaran_nama']} Semester {$row['t_ajaran_semester']}</b></h4>";
    }
?>

<script>
    $(document).ready(function(){
        
        $(".link-t_ajaran").on('click', function(){

            var id = $(this).attr("rel");

            $.post("t_ajaran/proses_t_ajaran.php",{id: id}, function(data){
                //$("#container-guru").html(data);
            });

        });
    });
</script>
