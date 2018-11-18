<?php
    session_start();
    if(!isset($_SESSION['guru_jabatan'])){
        header("Location: ../index.php");
    }
    elseif($_SESSION['guru_jabatan'] != 1){
        header("Location: ../index.php");
    }
    
    include ("../includes/db_con.php");

    $query =    "SELECT guru_id, guru_name, guru_username, jabatan_id, jabatan_nama 
                FROM guru, jabatan 
                WHERE guru_active = 1 AND jabatan_id = guru_jabatan
                ORDER BY guru_name";
    $query_guru_info = mysqli_query($conn, $query);

    if(!$query_guru_info){
        die("QUERY FAILED".mysqli_error($conn));
    }

    while($row = mysqli_fetch_array($query_guru_info)){
        echo"<tr>";
        echo"<td><a rel='".$row['guru_id']."' rel2='".$row['jabatan_id']."' class='link-guru' href='javascript:void(0)'>{$row['guru_name']}</a></td>";
        echo"<td>{$row['guru_username']}</td>";
        echo"<td>{$row['jabatan_nama']}</td>";
        echo"</tr>";
    }
?>

<script>
    //$("#container-guru").hide();

    $(".link-guru").on('click', function(){
        $("#container-guru").show();
        
        var id = $(this).attr("rel");
        var id2 = $(this).attr("rel2");
        
        $.post("guru/proses_guru.php",{id: id, id2:id2}, function(data){
            $("#container-guru").html(data);
        });
        
    });
</script>
