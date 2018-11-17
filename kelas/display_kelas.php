<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: ../index.php");
    }
    
    include ("../includes/db_con.php");

    
//    SELECT kelas_id, kelas_nama, guru_name, t_ajaran_id
//    FROM kelas
//    LEFT JOIN guru
//    ON kelas_wali_guru_id = guru_id
//    LEFT JOIN t_ajaran
//    ON kelas_t_ajaran_id = t_ajaran_id
//    WHERE t_ajaran_active = 1
    
    $query = "SELECT kelas_id, kelas_nama, kelas_jenjang_id, guru_id, guru_name, t_ajaran_id
            FROM kelas 
            LEFT JOIN guru 
            ON kelas_wali_guru_id = guru_id 
            LEFT JOIN t_ajaran 
            ON kelas_t_ajaran_id = t_ajaran_id 
            WHERE t_ajaran_active = 1";
    
    $query_kelas_info = mysqli_query($conn, $query);

    if(!$query_kelas_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    while($row = mysqli_fetch_array($query_kelas_info)){
        echo"<tr>";
        echo"<td><a rel='".$row['kelas_id']."' rel2='".$row['guru_id']."' rel3='".$row['kelas_jenjang_id']."' class='link-kelas' href='javascript:void(0)'>{$row['kelas_nama']}</a></td>";
        echo"<td>{$row['guru_name']}</td>";
        echo"</tr>";
    }
?>

<script>

    $(".link-kelas").on('click', function(){
        $("#container-kelas").show();
        
        var id = $(this).attr("rel");
        var id2 = $(this).attr("rel2");
        var id3 = $(this).attr("rel3");
        
        //passing id kelas dan id guru
        $.post("kelas/proses_kelas.php",{id: id, id2:id2, id3:id3}, function(data){
            $("#container-kelas").html(data);
        });
        
    });
</script>
