<?php

    include ("../includes/db_con.php");

   
    if(!empty($_POST["mapel_id"])) {
        $query =    "SELECT d_mapel_id_mapel, d_mapel_id_kelas, d_mapel_id_guru, kelas_nama, guru_name
                    FROM d_mapel
                    LEFT JOIN kelas
                    ON d_mapel_id_kelas = kelas_id
                    LEFT JOIN guru
                    ON d_mapel_id_guru = guru_id
                    LEFT JOIN t_ajaran
                    ON kelas_t_ajaran_id = t_ajaran_id
                    WHERE d_mapel_id_mapel = {$_POST["mapel_id"]} AND t_ajaran_active = 1";

        $query_mapel_info = mysqli_query($conn, $query);

        if(!$query_mapel_info){
            die("QUERY FAILED".mysqli_error($conn));
        }

        while($row = mysqli_fetch_array($query_mapel_info)){
            echo"<tr>";
            echo"<td><a rel='".$row['d_mapel_id_mapel']."' rel2='".$row['d_mapel_id_kelas']."' rel3='".$row['d_mapel_id_guru']."' class='link-mapel' href='javascript:void(0)'>{$row['kelas_nama']}</a></td>";
            echo"<td>{$row['guru_name']}</td>";
            echo"</tr>";
        }
    }
?>

<script>

    $(".link-mapel").on('click', function(){
        $("#container-mapel").show();
        
        var id = $(this).attr("rel");
        var id2 = $(this).attr("rel2");
        var id3 = $(this).attr("rel3");
        
        //passing d_mapel_id, id kelas dan id guru
        $.post("mapel/proses_mapel.php",{id: id, id2: id2, id3: id3}, function(data){
            $("#container-mapel").html(data);
        });
        
    });
</script>
